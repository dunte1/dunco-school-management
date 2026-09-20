<?php

namespace Modules\Timetable\Services;

use Modules\Timetable\Models\ClassSchedule;
use Modules\Timetable\Models\Room;
use Modules\Timetable\Models\RoomAllocation;
use Modules\Timetable\Models\TeacherAvailability;
use Modules\Timetable\Models\Timetable;
use Modules\Academic\Models\AcademicClass;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TimetableAutoGenerator
{
    protected $constraints;
    protected $softConstraints;
    protected $weights;
    protected $existingSchedules;
    protected $teacherAvailabilities;
    protected $roomAllocations;

    protected const DEFAULT_WEIGHTS = [
        'teacher_preference' => 10,
        'room_capacity' => 5,
        'teacher_workload_balance' => 8,
        'room_proximity' => 3,
        'time_preference' => 6,
        'avoid_gaps' => 4,
        'lunch_break' => 7,
    ];

    public function __construct($constraints = [], $softConstraints = [], $weights = [])
    {
        $this->constraints = $constraints;
        $this->softConstraints = $softConstraints;
        $this->weights = array_merge(self::DEFAULT_WEIGHTS, $weights);
        $this->existingSchedules = collect();
        $this->teacherAvailabilities = collect();
        $this->roomAllocations = collect();
    }

    /**
     * Pre-load existing data for constraint checking
     */
    public function loadExistingData(int $timetableId): void
    {
        $this->existingSchedules = ClassSchedule::where('timetable_id', $timetableId)->get();

        $teacherIds = $this->existingSchedules->pluck('teacher_id')->unique()->filter()->toArray();
        $this->teacherAvailabilities = TeacherAvailability::whereIn('teacher_id', $teacherIds)->get()
            ->groupBy('teacher_id');

        $roomIds = $this->existingSchedules->pluck('room_id')->unique()->filter()->toArray();
        $this->roomAllocations = RoomAllocation::whereIn('room_id', $roomIds)
            ->where('status', 'active')
            ->get()
            ->groupBy('room_id');
    }

    /**
     * Generate a timetable solution with scoring
     */
    public function generate($classes, $teachers, $rooms, $days, $timeSlots, ?int $timetableId = null)
    {
        if ($timetableId) {
            $this->loadExistingData($timetableId);
        }

        $results = [];
        $violations = [];
        $score = 0;

        $teacherSchedules = [];
        $roomSchedules = [];

        foreach ($classes as $class) {
            $assigned = false;
            $bestAssignment = null;
            $bestScore = -1;

            foreach ($days as $day) {
                foreach ($timeSlots as $slot) {
                    foreach ($teachers as $teacher) {
                        foreach ($rooms as $room) {
                            if (!$this->checkHardConstraints($class, $teacher, $room, $day, $slot, $teacherSchedules, $roomSchedules)) {
                                continue;
                            }

                            $softScore = $this->scoreSoftConstraints($class, $teacher, $room, $day, $slot);

                            if ($softScore > $bestScore) {
                                $bestScore = $softScore;
                                $bestAssignment = [
                                    'class_id' => $class->id,
                                    'class_name' => $class->name ?? "Class {$class->id}",
                                    'teacher_id' => $teacher->id,
                                    'teacher_name' => $teacher->name ?? "Teacher {$teacher->id}",
                                    'room_id' => $room->id,
                                    'room_name' => $room->name ?? "Room {$room->id}",
                                    'day_of_week' => $day,
                                    'start_time' => $slot['start'],
                                    'end_time' => $slot['end'],
                                    'soft_score' => $softScore,
                                ];
                            }
                        }
                    }
                }
            }

            if ($bestAssignment) {
                $results[] = $bestAssignment;
                $score += $bestScore;
                $assigned = true;

                $tid = $bestAssignment['teacher_id'];
                $rid = $bestAssignment['room_id'];
                $teacherSchedules[$tid][] = $bestAssignment;
                $roomSchedules[$rid][] = $bestAssignment;
            }

            if (!$assigned) {
                $reason = $this->determineUnassignableReason($class, $teachers, $rooms, $days, $timeSlots);
                $violations[] = [
                    'class_id' => $class->id,
                    'class_name' => $class->name ?? "Class {$class->id}",
                    'reason' => $reason,
                ];
            }
        }

        return [
            'assignments' => $results,
            'score' => $score,
            'violations' => $violations,
            'total_classes' => count($classes),
            'assigned' => count($results),
            'unassigned' => count($violations),
            'utilization' => count($classes) > 0 ? round((count($results) / count($classes)) * 100, 1) : 0,
        ];
    }

    /**
     * Check all hard constraints. Returns false if any constraint is violated.
     */
    protected function checkHardConstraints($class, $teacher, $room, $day, $slot, array $teacherSchedules, array $roomSchedules): bool
    {
        $start = $slot['start'];
        $end = $slot['end'];

        // 1. Time slot validity: end must be after start
        if ($start >= $end) {
            return false;
        }

        // 2. Teacher double-booking: no overlapping schedules for same teacher on same day
        $teacherDaySchedules = $teacherSchedules[$teacher->id] ?? [];
        foreach ($teacherDaySchedules as $existing) {
            if ($existing['day_of_week'] === $day) {
                if (!($existing['end_time'] <= $start || $existing['start_time'] >= $end)) {
                    return false;
                }
            }
        }

        // 3. Room double-booking: no overlapping schedules for same room on same day
        $roomDaySchedules = $roomSchedules[$room->id] ?? [];
        foreach ($roomDaySchedules as $existing) {
            if ($existing['day_of_week'] === $day) {
                if (!($existing['end_time'] <= $start || $existing['start_time'] >= $end)) {
                    return false;
                }
            }
        }

        // 4. Check existing database schedules for conflicts (teacher)
        $existingTeacherConflict = $this->existingSchedules
            ->where('teacher_id', $teacher->id)
            ->where('day_of_week', $day)
            ->filter(function ($s) use ($start, $end) {
                return !($s->end_time <= $start || $s->start_time >= $end);
            })
            ->isNotEmpty();
        if ($existingTeacherConflict) {
            return false;
        }

        // 5. Check existing database schedules for conflicts (room)
        $existingRoomConflict = $this->existingSchedules
            ->where('room_id', $room->id)
            ->where('day_of_week', $day)
            ->filter(function ($s) use ($start, $end) {
                return !($s->end_time <= $start || $s->start_time >= $end);
            })
            ->isNotEmpty();
        if ($existingRoomConflict) {
            return false;
        }

        // 6. Teacher availability: if availability records exist, the slot must fall within one
        $teacherAvail = $this->teacherAvailabilities->get($teacher->id, collect());
        $availForDay = $teacherAvail->where('day_of_week', $day);
        if ($availForDay->isNotEmpty()) {
            $hasAvailability = $availForDay->contains(function ($a) use ($start, $end) {
                $unavailable = $a->unavailable ?? false;
                if ($unavailable) {
                    return $start >= $a->start_time && $end <= $a->end_time;
                }
                return $start >= $a->start_time && $end <= $a->end_time;
            });
            // Check if teacher is marked unavailable for this slot
            $isUnavailable = $availForDay->contains(function ($a) use ($start, $end) {
                return ($a->unavailable ?? false) && $start >= $a->start_time && $end <= $a->end_time;
            });
            if ($isUnavailable) {
                return false;
            }
            if (!$hasAvailability && !$isUnavailable) {
                // Teacher has availability records but none cover this slot
                return false;
            }
        }

        // 7. Room capacity: ensure room can hold the class (if class has student count)
        if (isset($class->student_count) && isset($room->capacity)) {
            if ($room->capacity < $class->student_count) {
                return false;
            }
        }

        // 8. Room type compatibility: check if room type matches class needs (e.g., Lab)
        if (isset($class->requires_lab) && $class->requires_lab) {
            $roomType = strtolower($room->type ?? '');
            if (!in_array($roomType, ['lab', 'computer lab', 'science lab', 'laboratory'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Score soft constraints. Higher score = better fit.
     */
    protected function scoreSoftConstraints($class, $teacher, $room, $day, $slot): int
    {
        $score = 0;
        $start = $slot['start'];
        $end = $slot['end'];

        // 1. Teacher preference: prefer slots the teacher has marked as preferred
        $teacherAvail = $this->teacherAvailabilities->get($teacher->id, collect());
        $preferredSlot = $teacherAvail->where('day_of_week', $day)->contains(function ($a) use ($start, $end) {
            return ($a->preferred ?? false) && $start >= $a->start_time && $end <= $a->end_time;
        });
        if ($preferredSlot) {
            $score += $this->weights['teacher_preference'];
        }

        // 2. Room capacity: prefer rooms that fit class size well (not too big, not too small)
        if (isset($class->student_count) && isset($room->capacity) && $room->capacity > 0) {
            $ratio = $class->student_count / $room->capacity;
            if ($ratio >= 0.6 && $ratio <= 0.9) {
                $score += $this->weights['room_capacity'];
            } elseif ($ratio >= 0.4 && $ratio <= 1.0) {
                $score += (int)($this->weights['room_capacity'] * 0.5);
            }
        }

        // 3. Teacher workload balance: prefer teachers with fewer assigned periods
        $teacherExistingCount = $this->existingSchedules->where('teacher_id', $teacher->id)->count();
        if ($teacherExistingCount < 5) {
            $score += $this->weights['teacher_workload_balance'];
        } elseif ($teacherExistingCount < 8) {
            $score += (int)($this->weights['teacher_workload_balance'] * 0.5);
        }

        // 4. Time preference: prefer morning slots (08:00-12:00) for core subjects
        $hour = (int)substr($start, 0, 2);
        if ($hour >= 8 && $hour < 12) {
            $score += $this->weights['time_preference'];
        } elseif ($hour >= 13 && $hour < 15) {
            $score += (int)($this->weights['time_preference'] * 0.5);
        }

        // 5. Avoid gaps: prefer consecutive slots for the same class on same day
        $daySchedulesForClass = $this->existingSchedules
            ->where('class_id', $class->id)
            ->where('day_of_week', $day);
        foreach ($daySchedulesForClass as $existing) {
            if ($existing->end_time === $start || $existing->start_time === $end) {
                $score += $this->weights['avoid_gaps'];
                break;
            }
        }

        // 6. Lunch break: penalize scheduling during lunch (12:00-13:00)
        if ($hour === 12) {
            $score -= $this->weights['lunch_break'];
        }

        // 7. Room utilization: prefer less-used rooms
        $roomUsageCount = $this->existingSchedules->where('room_id', $room->id)->count();
        if ($roomUsageCount < 3) {
            $score += 2;
        }

        // 8. Room equipment bonus: if class needs specific equipment and room has it
        if (isset($class->requires_projector) && $class->requires_projector) {
            $equipment = strtolower($room->equipment ?? '');
            if (str_contains($equipment, 'projector')) {
                $score += 3;
            }
        }

        return max(0, $score);
    }

    /**
     * Determine why a class couldn't be assigned
     */
    protected function determineUnassignableReason($class, $teachers, $rooms, $days, $timeSlots): string
    {
        if ($teachers->isEmpty()) {
            return 'No teachers available';
        }
        if ($rooms->isEmpty()) {
            return 'No rooms available';
        }
        if ($days->isEmpty() || $timeSlots->isEmpty()) {
            return 'No available time slots';
        }

        // Check if all teachers are fully booked
        $allTeachersBusy = true;
        foreach ($teachers as $teacher) {
            $teacherDaySchedules = $this->existingSchedules->where('teacher_id', $teacher->id);
            if ($teacherDaySchedules->count() < count($days) * count($timeSlots)) {
                $allTeachersBusy = false;
                break;
            }
        }
        if ($allTeachersBusy) {
            return 'All teachers are fully booked';
        }

        // Check if all rooms are fully booked
        $allRoomsBusy = true;
        foreach ($rooms as $room) {
            $roomDaySchedules = $this->existingSchedules->where('room_id', $room->id);
            if ($roomDaySchedules->count() < count($days) * count($timeSlots)) {
                $allRoomsBusy = false;
                break;
            }
        }
        if ($allRoomsBusy) {
            return 'All rooms are fully booked';
        }

        // Check if teacher availability blocks all slots
        if (isset($class->teacher_id)) {
            $teacherAvail = $this->teacherAvailabilities->get($class->teacher_id, collect());
            if ($teacherAvail->isNotEmpty()) {
                $allSlotsBlocked = true;
                foreach ($days as $day) {
                    foreach ($timeSlots as $slot) {
                        $availForDay = $teacherAvail->where('day_of_week', $day);
                        $isAvailable = $availForDay->contains(function ($a) use ($slot) {
                            return !($a->unavailable ?? false) &&
                                   $slot['start'] >= $a->start_time &&
                                   $slot['end'] <= $a->end_time;
                        });
                        if ($isAvailable) {
                            $allSlotsBlocked = false;
                            break 2;
                        }
                    }
                }
                if ($allSlotsBlocked) {
                    return 'Teacher availability does not cover any available slots';
                }
            }
        }

        return 'No feasible slot found due to constraint conflicts';
    }

    /**
     * Approve a timetable: mark all its schedules as approved
     */
    public static function approveTimetable(int $timetableId, int $approvedBy): bool
    {
        try {
            $count = ClassSchedule::where('timetable_id', $timetableId)->update([
                'status' => 'approved',
                'approved_by' => $approvedBy,
                'approved_at' => now(),
            ]);

            Timetable::where('id', $timetableId)->update([
                'status' => 'approved',
                'approved_by' => $approvedBy,
                'approved_at' => now(),
            ]);

            return $count > 0;
        } catch (\Exception $e) {
            Log::error("Failed to approve timetable {$timetableId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reject a timetable: mark all its schedules as rejected
     */
    public static function rejectTimetable(int $timetableId, int $rejectedBy, string $reason = ''): bool
    {
        try {
            $count = ClassSchedule::where('timetable_id', $timetableId)->update([
                'status' => 'rejected',
                'rejected_by' => $rejectedBy,
                'rejected_at' => now(),
                'rejection_reason' => $reason,
            ]);

            Timetable::where('id', $timetableId)->update([
                'status' => 'rejected',
                'rejected_by' => $rejectedBy,
                'rejected_at' => now(),
                'rejection_reason' => $reason,
            ]);

            return $count > 0;
        } catch (\Exception $e) {
            Log::error("Failed to reject timetable {$timetableId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get conflict report for a timetable
     */
    public static function getConflictReport(int $timetableId): array
    {
        $schedules = ClassSchedule::where('timetable_id', $timetableId)
            ->with(['teacher', 'room', 'academicClass'])
            ->get();

        $conflicts = [];

        foreach ($schedules as $i => $s1) {
            foreach ($schedules as $j => $s2) {
                if ($i >= $j) continue;
                if ($s1->day_of_week !== $s2->day_of_week) continue;
                if (!($s1->end_time <= $s2->start_time || $s2->end_time <= $s1->start_time)) {
                    $type = null;
                    if ($s1->teacher_id === $s2->teacher_id) {
                        $type = 'teacher_overlap';
                    } elseif ($s1->room_id === $s2->room_id) {
                        $type = 'room_overlap';
                    }
                    if ($type) {
                        $conflicts[] = [
                            'type' => $type,
                            'schedule1_id' => $s1->id,
                            'schedule2_id' => $s2->id,
                            'day' => $s1->day_of_week,
                            'time1' => $s1->start_time . '-' . $s1->end_time,
                            'time2' => $s2->start_time . '-' . $s2->end_time,
                            'teacher' => $s1->teacher->name ?? 'N/A',
                            'room' => $s1->room->name ?? 'N/A',
                        ];
                    }
                }
            }
        }

        return $conflicts;
    }
}
