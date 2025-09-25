<?php

namespace Modules\Timetable\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Modules\Timetable\Models\ClassSchedule;
use Modules\Timetable\Http\Requests\ClassScheduleRequest;
use Modules\Academic\Models\AcademicClass;
use App\Models\Teacher;
use Modules\Timetable\Models\Room;
use Modules\Timetable\Models\Timetable;
use Barryvdh\DomPDF\Facade\Pdf;

class ClassScheduleController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (Schema::hasTable('class_schedules')) {
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
            } else {
                $schedules = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            }

            if (Schema::hasTable('academic_classes')) {
                $classes = AcademicClass::all();
            } else {
                $classes = collect();
            }

            if (Schema::hasTable('teachers')) {
                $teachers = Teacher::all();
            } else {
                $teachers = collect();
            }

            if (Schema::hasTable('rooms')) {
                $rooms = Room::all();
            } else {
                $rooms = collect();
            }

            if (Schema::hasTable('timetables')) {
                $timetables = Timetable::all();
            } else {
                $timetables = collect();
            }

        } catch (\Exception $e) {
            $schedules = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            $classes = collect();
            $teachers = collect();
            $rooms = collect();
            $timetables = collect();
        }

        return view('timetable::class_schedules.index', compact('schedules', 'classes', 'teachers', 'rooms', 'timetables'));
    }

    public function create()
    {
        try {
            if (Schema::hasTable('academic_classes')) {
                $classes = AcademicClass::all();
            } else {
                $classes = collect();
            }

            if (Schema::hasTable('teachers')) {
                $teachers = Teacher::all();
            } else {
                $teachers = collect();
            }

            if (Schema::hasTable('rooms')) {
                $rooms = Room::all();
            } else {
                $rooms = collect();
            }

            if (Schema::hasTable('timetables')) {
                $timetables = Timetable::all();
            } else {
                $timetables = collect();
            }

        } catch (\Exception $e) {
            $classes = collect();
            $teachers = collect();
            $rooms = collect();
            $timetables = collect();
        }

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
        $teachers = Teacher::all();
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

    public function exportPdf(Request $request)
    {
        try {
            $query = ClassSchedule::with(['academicClass', 'teacher', 'room', 'timetable']);
            
            // Apply filters
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
            
            $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();
            
            // Generate PDF using a simple HTML view
            $pdf = Pdf::loadView('timetable::class_schedules.pdf', compact('schedules'));
            
            return $pdf->download('class-schedules-' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            // If PDF generation fails, redirect back with error message
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
} 