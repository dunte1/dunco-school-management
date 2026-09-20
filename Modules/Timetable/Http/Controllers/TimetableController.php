<?php

namespace Modules\Timetable\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Modules\Timetable\Models\ClassSchedule;
use Modules\Timetable\Models\Room;
use Modules\Timetable\Models\RoomAllocation;
use Modules\Timetable\Models\Timetable;
use Modules\HR\Models\Staff;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Modules\Timetable\Services\TimetableAutoGenerator;

class TimetableController extends Controller
{
    public function index()
    {
        try {
            if (Schema::hasTable('class_schedules')) {
                $totalSchedules = ClassSchedule::count();
                $schedules = ClassSchedule::all();
            } else {
                $totalSchedules = 0;
                $schedules = collect();
            }

            if (Schema::hasTable('rooms')) {
                $totalRooms = Room::count();
            } else {
                $totalRooms = 0;
            }

            if (Schema::hasTable('room_allocations')) {
                $totalAllocations = RoomAllocation::count();
            } else {
                $totalAllocations = 0;
            }

            if (Schema::hasTable('users')) {
                try {
                    $totalTeachers = \App\Models\User::whereHas('roles', function($q){ $q->where('name', 'teacher'); })->count();
                } catch (\Exception $e) {
                    $totalTeachers = 0;
                }
            } else {
                $totalTeachers = 0;
            }

            if (Schema::hasTable('timetables')) {
                try {
                    $allTimetables = Timetable::all();
                } catch (\Exception $e) {
                    $allTimetables = collect();
                }
            } else {
                $allTimetables = collect();
            }

            if (Schema::hasTable('users')) {
                try {
                    $allTeachers = \App\Models\User::whereHas('roles', function($q){ $q->where('name', 'teacher'); })->get();
                } catch (\Exception $e) {
                    $allTeachers = collect();
                }
            } else {
                $allTeachers = collect();
            }

            if (Schema::hasTable('academic_classes')) {
                try {
                    $allClasses = \Modules\Academic\Models\AcademicClass::all();
                } catch (\Exception $e) {
                    $allClasses = collect();
                }
            } else {
                $allClasses = collect();
            }

            if (Schema::hasTable('rooms')) {
                try {
                    $allRooms = Room::all();
                } catch (\Exception $e) {
                    $allRooms = collect();
                }
            } else {
                $allRooms = collect();
            }

            $conflicts = 0;
            if ($schedules->count() > 0) {
                foreach ($schedules as $i => $s1) {
                    foreach ($schedules as $j => $s2) {
                        if ($i >= $j) continue;
                        if ($s1->day_of_week === $s2->day_of_week) {
                            if ($s1->teacher_id === $s2->teacher_id && !($s1->end_time <= $s2->start_time || $s2->end_time <= $s1->start_time)) {
                                $conflicts++;
                            }
                            if ($s1->room_id === $s2->room_id && !($s1->end_time <= $s2->start_time || $s2->end_time <= $s1->start_time)) {
                                $conflicts++;
                            }
                        }
                    }
                }
            }

            $today = now()->format('l');
            $availableTeachers = $totalTeachers;
            $unavailableTeachers = 0;

            if (Schema::hasTable('class_schedules')) {
                $upcomingSchedules = ClassSchedule::with(['teacher', 'room', 'academicClass'])
                    ->where('day_of_week', '>=', now()->format('l'))
                    ->orderBy('day_of_week')
                    ->orderBy('start_time')
                    ->limit(5)
                    ->get();
            } else {
                $upcomingSchedules = collect();
            }

            $status = 'complete';
            if ($conflicts > 0) {
                $status = 'conflict';
            } elseif ($totalSchedules < ($totalTeachers * 5)) {
                $status = 'partial';
            }

        } catch (\Exception $e) {
            $totalSchedules = 0;
            $totalRooms = 0;
            $totalAllocations = 0;
            $totalTeachers = 0;
            $conflicts = 0;
            $availableTeachers = 0;
            $unavailableTeachers = 0;
            $upcomingSchedules = collect();
            $status = 'not_configured';
            $allTimetables = collect();
            $allTeachers = collect();
            $allClasses = collect();
            $allRooms = collect();
        }

        return view('timetable::dashboard', compact(
            'totalSchedules',
            'totalRooms',
            'totalAllocations',
            'totalTeachers',
            'conflicts',
            'availableTeachers',
            'unavailableTeachers',
            'upcomingSchedules',
            'status',
            'allTimetables',
            'allTeachers',
            'allClasses',
            'allRooms'
        ));
    }

    public function calendar()
    {
        try {
            if (Schema::hasTable('timetables')) {
                $timetables = Timetable::all();
            } else {
                $timetables = collect();
            }
        } catch (\Exception $e) {
            $timetables = collect();
        }

        return view('timetable::calendar', compact('timetables'));
    }

    public function calendarData()
    {
        try {
            if (Schema::hasTable('class_schedules')) {
                $schedules = ClassSchedule::with(['teacher', 'room', 'academicClass'])->get();
                $events = $schedules->map(function($s) {
                    return [
                        'id' => $s->id,
                        'title' => ($s->academicClass->name ?? 'Class') . ' - ' . ($s->teacher->name ?? 'Teacher') . ' @ ' . ($s->room->name ?? 'Room'),
                        'start' => $this->getNextDateForDay($s->day_of_week, $s->start_time),
                        'end' => $this->getNextDateForDay($s->day_of_week, $s->end_time),
                        'extendedProps' => [
                            'class' => $s->academicClass->name ?? '',
                            'teacher' => $s->teacher->name ?? '',
                            'room' => $s->room->name ?? '',
                            'day_of_week' => $s->day_of_week,
                            'start_time' => $s->start_time,
                            'end_time' => $s->end_time,
                            'timetable_id' => $s->timetable_id,
                        ],
                    ];
                });
            } else {
                $events = collect();
            }
        } catch (\Exception $e) {
            $events = collect();
        }

        return response()->json($events);
    }

    public function autoGenerate(Request $request)
    {
        $data = $request->validate([
            'timetable_id' => 'required|integer|exists:timetables,id',
            'class_ids' => 'nullable|array',
            'class_ids.*' => 'integer|exists:academic_classes,id',
            'teacher_ids' => 'nullable|array',
            'teacher_ids.*' => 'integer|exists:users,id',
            'room_ids' => 'nullable|array',
            'room_ids.*' => 'integer|exists:rooms,id',
            'days' => 'nullable|array',
            'days.*' => 'string',
            'time_slots' => 'nullable|string',
            'avoid_double_booking' => 'nullable|boolean',
            'enforce_availability' => 'nullable|boolean',
        ]);

        $classIds = $data['class_ids'] ?? [];
        $teacherIds = $data['teacher_ids'] ?? [];
        $roomIds = $data['room_ids'] ?? [];
        $days = $data['days'] ?? ['Monday','Tuesday','Wednesday','Thursday','Friday'];
        $timeSlots = array_map('trim', explode(',', $data['time_slots'] ?? '08:00-09:00,09:00-10:00,10:00-11:00'));
        $timetableId = $data['timetable_id'];

        $classes = \Modules\Academic\Models\AcademicClass::whereIn('id', $classIds)->get();
        $teachers = \App\Models\User::whereIn('id', $teacherIds)->get();
        $rooms = Room::whereIn('id', $roomIds)->get();

        $slotArr = array_map(function($slot) {
            $parts = explode('-', $slot);
            return ['start' => $parts[0] ?? '', 'end' => $parts[1] ?? ''];
        }, $timeSlots);

        $generator = new TimetableAutoGenerator();
        $result = $generator->generate($classes, $teachers, $rooms, $days, $slotArr, $timetableId);

        AuditLog::log('timetable.schedule.autogenerate', 'Auto-generated timetable preview', null, $data);
        return view('timetable::autogen_summary', [
            'data' => $data,
            'results' => $result['assignments'],
            'unassigned' => $result['violations'],
            'score' => $result['score'],
            'utilization' => $result['utilization'] ?? 0,
        ]);
    }

    public function saveAutoGenerated(Request $request)
    {
        $data = $request->validate([
            'timetable_id' => 'required|integer|exists:timetables,id',
            'assignments' => 'required|string',
        ]);
        $assignments = json_decode($data['assignments'], true);
        $created = 0;
        foreach ($assignments as $row) {
            $exists = ClassSchedule::where([
                'timetable_id' => $data['timetable_id'],
                'academic_class_id' => $row['class_id'],
                'teacher_id' => $row['teacher_id'],
                'room_id' => $row['room_id'],
                'day_of_week' => $row['day_of_week'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
            ])->exists();
            if (!$exists) {
                ClassSchedule::create([
                    'timetable_id' => $data['timetable_id'],
                    'academic_class_id' => $row['class_id'],
                    'teacher_id' => $row['teacher_id'],
                    'room_id' => $row['room_id'],
                    'day_of_week' => $row['day_of_week'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],
                    'status' => 'draft',
                ]);
                $created++;
            }
        }
        AuditLog::log('timetable.schedule.autogenerate.save', 'Saved auto-generated timetable', null, $assignments);
        return redirect()->route('timetables.index')->with('success', "$created schedule(s) generated and saved successfully.");
    }

    public function print(Request $request)
    {
        try {
            if (Schema::hasTable('class_schedules')) {
                $query = ClassSchedule::query();
                if ($request->filled('timetable_id')) {
                    $query->where('timetable_id', $request->timetable_id);
                }
                if ($request->filled('class_id')) {
                    $query->where('academic_class_id', $request->class_id);
                }
                if ($request->filled('teacher_id')) {
                    $query->where('teacher_id', $request->teacher_id);
                }
                if ($request->filled('room_id')) {
                    $query->where('room_id', $request->room_id);
                }
                $schedules = $query->with(['teacher', 'room', 'academicClass'])->orderBy('day_of_week')->orderBy('start_time')->get();
            } else {
                $schedules = collect();
            }
        } catch (\Exception $e) {
            $schedules = collect();
        }

        AuditLog::log('timetable.schedule.print', 'Printed timetable', null, $request->all());
        return view('timetable::print', compact('schedules'));
    }

    public function reports()
    {
        try {
            if (Schema::hasTable('class_schedules')) {
                $schedules = ClassSchedule::with(['teacher', 'room', 'academicClass'])->get();
                $totalSchedules = $schedules->count();
                $teacherWorkload = $schedules->groupBy('teacher_id')->map(function($items, $teacherId) {
                    return [
                        'teacher_id' => $teacherId,
                        'teacher_name' => optional($items->first()->teacher)->name ?? $teacherId,
                        'periods' => $items->count(),
                    ];
                })->sortByDesc('periods');
                $roomUtilization = $schedules->groupBy('room_id')->map(function($items, $roomId) {
                    return [
                        'room_id' => $roomId,
                        'room_name' => optional($items->first()->room)->name ?? $roomId,
                        'periods' => $items->count(),
                    ];
                })->sortByDesc('periods');
                $classDensity = $schedules->groupBy('academic_class_id')->map(function($items, $classId) {
                    return [
                        'class_id' => $classId,
                        'class_name' => optional($items->first()->academicClass)->name ?? $classId,
                        'periods' => $items->count(),
                    ];
                })->sortByDesc('periods');
            } else {
                $totalSchedules = 0;
                $teacherWorkload = collect();
                $roomUtilization = collect();
                $classDensity = collect();
            }
        } catch (\Exception $e) {
            $totalSchedules = 0;
            $teacherWorkload = collect();
            $roomUtilization = collect();
            $classDensity = collect();
        }

        return view('timetable::reports', compact('totalSchedules', 'teacherWorkload', 'roomUtilization', 'classDensity'));
    }

    public function conflicts()
    {
        try {
            if (Schema::hasTable('class_schedules')) {
                $schedules = ClassSchedule::with(['teacher', 'room', 'academicClass'])->get();
                $conflicts = [];

                foreach ($schedules as $s1) {
                    foreach ($schedules as $s2) {
                        if ($s1->id === $s2->id) continue;
                        if ($s1->day_of_week === $s2->day_of_week) {
                            if (!($s1->end_time <= $s2->start_time || $s2->end_time <= $s1->start_time)) {
                                $type = 'Unknown Overlap';
                                if ($s1->teacher_id === $s2->teacher_id) {
                                    $type = 'Teacher Overlap';
                                } elseif ($s1->room_id === $s2->room_id) {
                                    $type = 'Room Overlap';
                                }
                                $conflicts[] = [
                                    'type' => $type,
                                    'schedule1' => $s1,
                                    'schedule2' => $s2,
                                ];
                            }
                        }
                    }
                }
            } else {
                $conflicts = [];
            }
        } catch (\Exception $e) {
            $conflicts = [];
        }

        return view('timetable::conflicts', compact('conflicts'));
    }

    public function analytics()
    {
        try {
            if (Schema::hasTable('class_schedules')) {
                $schedules = ClassSchedule::with(['teacher', 'room', 'academicClass'])->get();
                $trends = $schedules->groupBy(function($s) {
                    return \Carbon\Carbon::parse($s->created_at)->startOfWeek()->format('Y-m-d');
                })->map->count();

                $teacherHeatmap = [];
                foreach ($schedules as $s) {
                    $day = $s->day_of_week;
                    $hour = substr($s->start_time, 0, 2);
                    $teacher = $s->teacher->name ?? $s->teacher_id;
                    $teacherHeatmap[$teacher][$day][$hour] = ($teacherHeatmap[$teacher][$day][$hour] ?? 0) + 1;
                }
                $roomHeatmap = [];
                foreach ($schedules as $s) {
                    $day = $s->day_of_week;
                    $hour = substr($s->start_time, 0, 2);
                    $room = $s->room->name ?? $s->room_id;
                    $roomHeatmap[$room][$day][$hour] = ($roomHeatmap[$room][$day][$hour] ?? 0) + 1;
                }

                $teachers = $schedules->pluck('teacher')->unique('id')->filter();
                $rooms = $schedules->pluck('room')->unique('id')->filter();
                $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
                $hours = ['08','09','10','11','12','13','14','15','16'];
                $teacherFreeBusy = [];
                foreach ($teachers as $teacher) {
                    foreach ($days as $day) {
                        foreach ($hours as $hour) {
                            $busy = $schedules->where('teacher_id', $teacher->id)->where('day_of_week', $day)->filter(function($s) use ($hour) {
                                return substr($s->start_time, 0, 2) == $hour;
                            })->isNotEmpty();
                            $teacherFreeBusy[$teacher->name][$day][$hour] = $busy ? 'Busy' : 'Free';
                        }
                    }
                }
                $roomFreeBusy = [];
                foreach ($rooms as $room) {
                    foreach ($days as $day) {
                        foreach ($hours as $hour) {
                            $busy = $schedules->where('room_id', $room->id)->where('day_of_week', $day)->filter(function($s) use ($hour) {
                                return substr($s->start_time, 0, 2) == $hour;
                            })->isNotEmpty();
                            $roomFreeBusy[$room->name][$day][$hour] = $busy ? 'Busy' : 'Free';
                        }
                    }
                }
            } else {
                $trends = collect();
                $teacherHeatmap = [];
                $roomHeatmap = [];
                $teacherFreeBusy = [];
                $roomFreeBusy = [];
                $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
                $hours = ['08','09','10','11','12','13','14','15','16'];
            }
        } catch (\Exception $e) {
            $trends = collect();
            $teacherHeatmap = [];
            $roomHeatmap = [];
            $teacherFreeBusy = [];
            $roomFreeBusy = [];
            $days = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
            $hours = ['08','09','10','11','12','13','14','15','16'];
        }

        return view('timetable::analytics', compact('trends', 'teacherHeatmap', 'roomHeatmap', 'teacherFreeBusy', 'roomFreeBusy', 'days', 'hours'));
    }

    public function settings()
    {
        $settings = config('timetable');
        return response()->json($settings);
    }

    public function approvalList()
    {
        $timetables = Timetable::withCount('schedules')->latest()->get();
        return view('timetable::approval_list', compact('timetables'));
    }

    public function approve(Request $request, int $id)
    {
        $timetable = Timetable::findOrFail($id);
        $success = TimetableAutoGenerator::approveTimetable($id, auth()->id());

        if ($success) {
            AuditLog::log('timetable.approve', "Approved timetable: {$timetable->name}", null, ['timetable_id' => $id]);
            return back()->with('success', 'Timetable approved successfully.');
        }

        return back()->with('error', 'Failed to approve timetable.');
    }

    public function reject(Request $request, int $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $timetable = Timetable::findOrFail($id);
        $success = TimetableAutoGenerator::rejectTimetable($id, auth()->id(), $request->input('reason'));

        if ($success) {
            AuditLog::log('timetable.reject', "Rejected timetable: {$timetable->name}", null, ['timetable_id' => $id, 'reason' => $request->input('reason')]);
            return back()->with('success', 'Timetable rejected.');
        }

        return back()->with('error', 'Failed to reject timetable.');
    }

    private function getNextDateForDay($dayOfWeek, $time)
    {
        $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        $today = now();
        $targetDay = array_search(ucfirst(strtolower($dayOfWeek)), $days);
        if ($targetDay === false) $targetDay = 1;
        $date = $today->copy()->startOfWeek()->addDays($targetDay);
        return $date->format('Y-m-d') . 'T' . $time;
    }
}
