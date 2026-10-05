<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\AcademicClass;
use Modules\Academic\Models\EnrollmentHistory;

class PromoteStudents extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'academic:promote-students {--school=} {--dry-run} {--force}';

    /**
     * The console command description.
     */
    protected $description = 'Auto-promote eligible students to the next class/module/block based on per-school rules and mapping.';

    public function handle(): int
    {
        $schoolIdOption = $this->option('school');
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        $schools = $schoolIdOption
            ? \App\Models\School::where('id', $schoolIdOption)->get()
            : \App\Models\School::all();

        $totalPromoted = 0;
        foreach ($schools as $school) {
            $schoolId = (int) $school->id;
            $promotionAuto = $this->getSchoolSetting($schoolId, 'promotion_auto', '0') === '1';
            if (!$promotionAuto && !$force) {
                $this->line("School {$school->name}: auto-promotion disabled. Skipping.");
                continue;
            }

            // Only run after term/session end unless forced
            $termEnd = $this->getSchoolSetting($schoolId, 'term_end', null);
            $lastRunAt = $this->getSchoolSetting($schoolId, 'promotion_last_run_at', null);
            $now = Carbon::now();
            if (!$force && $termEnd) {
                try {
                    $termEndDate = Carbon::parse($termEnd)->endOfDay();
                    if ($now->lt($termEndDate)) {
                        $this->line("School {$school->name}: term ends at {$termEndDate->toDateString()}, not time yet. Skipping.");
                        continue;
                    }
                } catch (\Throwable $e) {
                    // ignore parse errors
                }
            }
            if (!$force && $lastRunAt) {
                try {
                    $lastRun = Carbon::parse($lastRunAt);
                    if ($lastRun->isSameDay($now)) {
                        $this->line("School {$school->name}: already ran today. Skipping.");
                        continue;
                    }
                } catch (\Throwable $e) {
                    // ignore parse
                }
            }

            $passMark = (float) ($this->getSchoolSetting($schoolId, 'promotion_pass_mark_percent', '0') ?: 0);
            $minSubjects = (int) ($this->getSchoolSetting($schoolId, 'promotion_min_subjects', '0') ?: 0);
            $promotionMapJson = $this->getSchoolSetting($schoolId, 'promotion_map', null);
            $promotionMap = [];
            if ($promotionMapJson) {
                try { $promotionMap = json_decode($promotionMapJson, true) ?: []; } catch (\Throwable $e) { $promotionMap = []; }
            }

            $promotedForSchool = 0;
            $students = Student::query()->where('school_id', $schoolId)->where('is_active', true)->get();

            foreach ($students as $student) {
                $currentClassId = $student->class_id ?: optional($student->current_class)->id;
                if (!$currentClassId) { continue; }

                $nextClassId = $promotionMap[(string) $currentClassId] ?? $promotionMap[$currentClassId] ?? null;
                if (!$nextClassId) {
                    // no explicit mapping; try heuristic: find next active class with same code and higher academic_year
                    $currentClass = AcademicClass::find($currentClassId);
                    if ($currentClass) {
                        $nextClassId = AcademicClass::query()
                            ->where('school_id', $schoolId)
                            ->where('is_active', true)
                            ->when($currentClass->code, fn($q) => $q->where('code', $currentClass->code))
                            ->when($currentClass->academic_year, fn($q) => $q->where('academic_year', '>', $currentClass->academic_year))
                            ->orderBy('academic_year')
                            ->value('id');
                    }
                }

                if (!$nextClassId) { continue; }

                if (!$this->isEligibleForPromotion($student->id, $currentClassId, $passMark, $minSubjects)) {
                    continue;
                }

                if ($dryRun) {
                    $this->line("[DRY-RUN] Promote student #{$student->id} from class {$currentClassId} to {$nextClassId}");
                    $promotedForSchool++;
                    continue;
                }

                DB::transaction(function () use ($student, $currentClassId, $nextClassId) {
                    // deactivate current class on pivot
                    try { $student->classes()->updateExistingPivot($currentClassId, ['is_active' => false]); } catch (\Throwable $e) {}
                    // attach next class
                    try { $student->classes()->attach($nextClassId, ['enrollment_date' => now(), 'is_active' => true]); } catch (\Throwable $e) {}
                    // update student primary class_id
                    $student->class_id = $nextClassId;
                    $student->save();
                    // record in enrollment history
                    EnrollmentHistory::create([
                        'student_id' => $student->id,
                        'class_id' => $nextClassId,
                        'academic_year' => now()->year,
                        'status' => 'promoted',
                        'changed_at' => now(),
                    ]);
                });

                $promotedForSchool++;
            }

            $totalPromoted += $promotedForSchool;
            $this->line("School {$school->name}: promoted {$promotedForSchool} students.");

            if (!$dryRun) {
                \App\Models\SchoolSetting::updateOrCreate(
                    ['school_id' => $schoolId, 'key' => 'promotion_last_run_at'],
                    ['value' => now()->toDateTimeString(), 'type' => 'string', 'description' => 'Last auto-promotion run timestamp']
                );
            }
        }

        $this->info("Total promoted: {$totalPromoted}");
        return Command::SUCCESS;
    }

    private function getSchoolSetting(int $schoolId, string $key, ?string $default = null): ?string
    {
        $val = \App\Models\SchoolSetting::where(['school_id' => $schoolId, 'key' => $key])->value('value');
        return $val !== null ? $val : $default;
    }

    private function isEligibleForPromotion(int $studentId, int $classId, float $passMark, int $minSubjects): bool
    {
        if ($passMark <= 0 && $minSubjects <= 0) { return true; }

        try {
            $query = \Modules\Academic\Models\ExamResult::query()
                ->where('student_id', $studentId)
                ->where('class_id', $classId);

            $results = $query->get(['percentage']);
            if ($results->isEmpty()) {
                // If no data to evaluate, fail the promotion if thresholds are set
                return $passMark <= 0 && $minSubjects <= 0;
            }

            $avg = (float) $results->avg('percentage');
            $passedSubjects = $results->where('percentage', '>=', $passMark)->count();

            $meetsPass = $passMark > 0 ? ($avg >= $passMark) : true;
            $meetsSubjects = $minSubjects > 0 ? ($passedSubjects >= $minSubjects) : true;
            return $meetsPass && $meetsSubjects;
        } catch (\Throwable $e) {
            // If results table is missing or query fails, only allow when thresholds effectively disabled
            return $passMark <= 0 && $minSubjects <= 0;
        }
    }
}


