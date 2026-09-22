<?php

namespace Modules\Nursing\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Nursing\Models\Placement;
use Modules\Nursing\Models\LogbookEntry;
use Modules\Nursing\Models\StudentSkill;
use Modules\Nursing\Models\ClinicalHours;
use Modules\Nursing\Models\SkillAssessment;
use Modules\Nursing\Models\CpdActivity;
use Modules\Nursing\Models\NursingAuditLog;
use Modules\Academic\Models\Student;
use Dompdf\Dompdf;
use Dompdf\Options;

class NursingReportController extends Controller
{
    public function index()
    {
        return Inertia::render('Nursing/Reports/Index');
    }

    public function studentReport(Student $student)
    {
        $placement = Placement::where('student_id', $student->id)
            ->with(['facility', 'department', 'ward'])
            ->latest()
            ->first();

        $logbooks = LogbookEntry::where('student_id', $student->id)
            ->with('placement.facility')
            ->get();

        $skills = StudentSkill::where('student_id', $student->id)
            ->with('skill.category')
            ->get();

        $hours = ClinicalHours::where('student_id', $student->id)
            ->where('status', 'approved')
            ->get();

        $assessments = SkillAssessment::where('student_id', $student->id)
            ->with('skill')
            ->get();

        $stats = [
            'total_logbooks' => $logbooks->count(),
            'approved_logbooks' => $logbooks->where('status', 'approved')->count(),
            'total_hours' => $hours->sum('hours'),
            'competent_skills' => $skills->where('status', 'competent')->count(),
            'total_skills' => $skills->count(),
            'assessments_count' => $assessments->count(),
            'average_score' => $assessments->avg('percentage'),
        ];

        return Inertia::render('Nursing/Reports/StudentReport', compact('student', 'placement', 'logbooks', 'skills', 'hours', 'assessments', 'stats'));
    }

    public function clinicalHoursReport(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $query = ClinicalHours::where('school_id', $schoolId)
            ->where('status', 'approved')
            ->with(['student', 'placement.facility']);

        if ($request->filled('placement_id')) {
            $query->where('placement_id', $request->placement_id);
        }
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $hours = $query->get();

        $summary = $hours->groupBy('student_id')->map(function ($studentHours) {
            return [
                'student' => $studentHours->first()->student,
                'total_hours' => $studentHours->sum('hours'),
                'days' => $studentHours->count(),
            ];
        });

        return Inertia::render('Nursing/Reports/ClinicalHours', compact('hours', 'summary'));
    }

    public function skillsCompetencyReport(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $skills = \Modules\Nursing\Models\Skill::published()->with('category')->get();
        $studentSkills = StudentSkill::whereHas('student', fn($q) => $q->where('school_id', $schoolId))
            ->with(['student', 'skill.category'])
            ->get();

        $summary = $studentSkills->groupBy('student_id')->map(function ($studentSkillsGroup) {
            return [
                'student' => $studentSkillsGroup->first()->student,
                'competent' => $studentSkillsGroup->where('status', 'competent')->count(),
                'total' => $studentSkillsGroup->count(),
                'percentage' => $studentSkillsGroup->count() > 0
                    ? round(($studentSkillsGroup->where('status', 'competent')->count() / $studentSkillsGroup->count()) * 100, 1)
                    : 0,
            ];
        });

        return Inertia::render('Nursing/Reports/SkillsCompetency', compact('skills', 'studentSkills', 'summary'));
    }

    public function attendanceReport(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $hours = ClinicalHours::where('school_id', $schoolId)
            ->with(['student', 'placement.facility'])
            ->get();

        $placementReport = $hours->groupBy('placement_id')->map(function ($placementHours) {
            return [
                'placement' => $placementHours->first()->placement,
                'total_hours' => $placementHours->sum('hours'),
                'days_present' => $placementHours->count(),
            ];
        });

        return Inertia::render('Nursing/Reports/Attendance', compact('hours', 'placementReport'));
    }

    public function export(Request $request, string $type)
    {
        $request->validate([
            'format' => 'required|in:csv',
        ]);

        $schoolId = Auth::user()->school_id;
        $filename = "nursing_{$type}_report_" . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($type, $schoolId) {
            $handle = fopen('php://output', 'w');

            switch ($type) {
                case 'clinical-hours':
                    fputcsv($handle, ['Student', 'Facility', 'Date', 'Hours', 'Shift', 'Status']);
                    ClinicalHours::where('school_id', $schoolId)
                        ->with(['student', 'placement.facility'])
                        ->orderBy('date')
                        ->each(function ($hour) use ($handle) {
                            fputcsv($handle, [
                                $hour->student->name ?? 'N/A',
                                $hour->placement->facility->name ?? 'N/A',
                                $hour->date->format('Y-m-d'),
                                $hour->hours,
                                $hour->shift ?? 'N/A',
                                $hour->status,
                            ]);
                        });
                    break;

                case 'skills-competency':
                    fputcsv($handle, ['Student', 'Skill', 'Category', 'Status', 'Awarded By', 'Date']);
                    \Modules\Nursing\Models\StudentSkill::whereHas('student', fn($q) => $q->where('school_id', $schoolId))
                        ->with(['student', 'skill.category', 'awardedBy'])
                        ->each(function ($ss) use ($handle) {
                            fputcsv($handle, [
                                $ss->student->name ?? 'N/A',
                                $ss->skill->name ?? 'N/A',
                                $ss->skill->category->name ?? 'N/A',
                                $ss->status,
                                $ss->awardedBy->name ?? 'N/A',
                                $ss->awarded_at?->format('Y-m-d') ?? 'N/A',
                            ]);
                        });
                    break;

                case 'attendance':
                    fputcsv($handle, ['Student', 'Facility', 'Date', 'Hours', 'Shift', 'Status']);
                    ClinicalHours::where('school_id', $schoolId)
                        ->where('status', 'approved')
                        ->with(['student', 'placement.facility'])
                        ->orderBy('date')
                        ->each(function ($hour) use ($handle) {
                            fputcsv($handle, [
                                $hour->student->name ?? 'N/A',
                                $hour->placement->facility->name ?? 'N/A',
                                $hour->date->format('Y-m-d'),
                                $hour->hours,
                                $hour->shift ?? 'N/A',
                                $hour->status,
                            ]);
                        });
                    break;

                case 'students':
                    fputcsv($handle, ['Student ID', 'Name', 'Active Placements', 'Total Hours', 'Competent Skills', 'Total Skills']);
                    $students = \Modules\Academic\Models\Student::where('school_id', $schoolId)
                        ->where('is_active', true)
                        ->get();
                    foreach ($students as $student) {
                        fputcsv($handle, [
                            $student->student_id,
                            $student->name,
                            Placement::where('student_id', $student->id)->where('status', 'active')->count(),
                            ClinicalHours::where('student_id', $student->id)->where('status', 'approved')->sum('hours'),
                            \Modules\Nursing\Models\StudentSkill::where('student_id', $student->id)->where('status', 'competent')->count(),
                            \Modules\Nursing\Models\StudentSkill::where('student_id', $student->id)->count(),
                        ]);
                    }
                    break;

                default:
                    fputcsv($handle, ['Error: Unknown report type']);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
