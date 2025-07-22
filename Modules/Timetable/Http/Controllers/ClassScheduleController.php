<?php

namespace Modules\Timetable\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Timetable\Models\ClassSchedule;
use Modules\Timetable\Http\Requests\ClassScheduleRequest;
use Modules\Academic\Models\AcademicClass;
use Modules\HR\Models\Staff;
use Modules\Timetable\Models\Room;
use Modules\Timetable\Models\Timetable;

class ClassScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassSchedule::with(['academicClass', 'teacher', 'room', 'timetable']);
        // Filters
        if ($request->filled('academic_class_id')) {
            $query->where('academic_class_id', $request->academic_class_id);
        }
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }
        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }
        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->paginate(20);
        $classes = AcademicClass::all();
        $teachers = Staff::whereHas('role', function($q) { $q->where('name', 'teacher'); })->get();
        $rooms = Room::all();
        $timetables = Timetable::all();
        return view('timetable::class_schedules.index', compact('schedules', 'classes', 'teachers', 'rooms', 'timetables'));
    }

    public function create()
    {
        $classes = AcademicClass::all();
        $teachers = Staff::whereHas('role', function($q) { $q->where('name', 'teacher'); })->get();
        $rooms = Room::all();
        $timetables = Timetable::all();
        return view('timetable::class_schedules.create', compact('classes', 'teachers', 'rooms', 'timetables'));
    }

    public function store(ClassScheduleRequest $request)
    {
        $schedule = ClassSchedule::create($request->validated());
        return redirect()->route('class_schedules.index')->with('success', 'Class schedule created successfully.');
    }

    public function show(ClassSchedule $class_schedule)
    {
        $class_schedule->load(['academicClass', 'teacher', 'room', 'timetable']);
        return view('timetable::class_schedules.show', compact('class_schedule'));
    }

    public function edit(ClassSchedule $class_schedule)
    {
        $classes = AcademicClass::all();
        $teachers = Staff::whereHas('role', function($q) { $q->where('name', 'teacher'); })->get();
        $rooms = Room::all();
        $timetables = Timetable::all();
        return view('timetable::class_schedules.edit', compact('class_schedule', 'classes', 'teachers', 'rooms', 'timetables'));
    }

    public function update(ClassScheduleRequest $request, ClassSchedule $class_schedule)
    {
        $class_schedule->update($request->validated());
        return redirect()->route('class_schedules.index')->with('success', 'Class schedule updated successfully.');
    }

    public function destroy(ClassSchedule $class_schedule)
    {
        $class_schedule->delete();
        return redirect()->route('class_schedules.index')->with('success', 'Class schedule deleted successfully.');
    }
} 