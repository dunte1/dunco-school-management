<?php

namespace Modules\Timetable\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Timetable\Models\ClassSchedule;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Return dashboard summary for the authenticated user
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today();
        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();
        $query = ClassSchedule::query()->with(['class', 'teacher', 'room']);

        if ($user->hasRole('student')) {
            $student = $user->student ?? null;
            if ($student) {
                $classId = $student->class_id;
                $query->where('class_id', $classId);
            } else {
                return response()->json([]);
            }
        } elseif ($user->hasRole('teacher')) {
            $query->where('teacher_id', $user->id);
        } elseif ($user->hasRole('parent')) {
            $studentIds = $user->students->pluck('id');
            $query->whereIn('class_id', function($q) use ($studentIds) {
                $q->select('class_id')
                  ->from('academic_students')
                  ->whereIn('id', $studentIds);
            });
        }

        // Today's schedule
        $todayDay = $today->format('l');
        $todaysSchedule = (clone $query)->where('day_of_week', $todayDay)->orderBy('start_time')->get();

        // This week's schedule
        $weekDays = [];
        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $weekDays[] = $date->format('l');
        }
        $weekSchedule = (clone $query)->whereIn('day_of_week', $weekDays)->get();
        $totalThisWeek = $weekSchedule->count();

        // Next class (today or next in week)
        $now = Carbon::now()->format('H:i');
        $nextClass = $todaysSchedule->first(function($s) use ($now) {
            return $s->start_time > $now;
        });
        if (!$nextClass) {
            // If no more today, get next in week
            $nextClass = $weekSchedule->sortBy(function($s) use ($todayDay, $now, $weekDays) {
                $dayIndex = array_search($s->day_of_week, $weekDays);
                return $dayIndex * 10000 + intval(str_replace(':', '', $s->start_time));
            })->first();
        }

        // Unread notifications
        $unreadNotifications = $user->unreadNotifications()->count();

        // Calculate total teachers scheduled (this week)
        $totalTeachersScheduled = $weekSchedule->pluck('teacher_id')->unique()->count();

        // Calculate total rooms allocated (this week)
        $totalRoomsAllocated = $weekSchedule->pluck('room_id')->unique()->count();

        // Conflict detection (teacher or room double-booked)
        $conflicts = [];
        $byTeacherDay = $weekSchedule->groupBy(['teacher_id', 'day_of_week']);
        foreach ($byTeacherDay as $teacherId => $days) {
            foreach ($days as $day => $schedules) {
                $sorted = $schedules->sortBy('start_time');
                $prev = null;
                foreach ($sorted as $sched) {
                    if ($prev && $sched->start_time < $prev->end_time) {
                        $conflicts[] = [
                            'type' => 'teacher',
                            'teacher_id' => $teacherId,
                            'day' => $day,
                            'conflict' => [$prev->id, $sched->id],
                        ];
                    }
                    $prev = $sched;
                }
            }
        }
        $byRoomDay = $weekSchedule->groupBy(['room_id', 'day_of_week']);
        foreach ($byRoomDay as $roomId => $days) {
            foreach ($days as $day => $schedules) {
                $sorted = $schedules->sortBy('start_time');
                $prev = null;
                foreach ($sorted as $sched) {
                    if ($prev && $sched->start_time < $prev->end_time) {
                        $conflicts[] = [
                            'type' => 'room',
                            'room_id' => $roomId,
                            'day' => $day,
                            'conflict' => [$prev->id, $sched->id],
                        ];
                    }
                    $prev = $sched;
                }
            }
        }
        $conflictCount = count($conflicts);

        // Teacher availability summary (count of teachers with availability set)
        $teacherAvailabilityCount = \Modules\Timetable\Models\TeacherAvailability::distinct('teacher_id')->count('teacher_id');

        // Timetable status (color-coded): complete, partial, conflict
        $status = 'complete';
        if ($conflictCount > 0) {
            $status = 'conflict';
        } elseif ($totalTeachersScheduled < $weekSchedule->count()) {
            $status = 'partial';
        }

        // Quick links (for frontend to render as needed)
        $quickLinks = [
            ['label' => 'Create Schedule', 'url' => '/timetable/class-schedules/create'],
            ['label' => 'Export Timetable', 'url' => '/timetable/export'],
            ['label' => 'Teacher Availability', 'url' => '/timetable/teacher-availability'],
        ];

        return response()->json([
            'next_class' => $nextClass,
            'total_this_week' => $totalThisWeek,
            'unread_notifications' => $unreadNotifications,
            'todays_schedule' => $todaysSchedule,
            'total_teachers_scheduled' => $totalTeachersScheduled,
            'total_rooms_allocated' => $totalRoomsAllocated,
            'conflict_alerts' => $conflictCount,
            'conflict_details' => $conflicts,
            'teacher_availability_summary' => $teacherAvailabilityCount,
            'timetable_status' => $status,
            'quick_links' => $quickLinks,
        ]);
    }
} 