<?php

namespace Modules\Timetable\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Timetable\Models\ClassSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class MyScheduleController extends Controller
{
    /**
     * Return the authenticated user's schedule (student, teacher, or parent)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $date = $request->input('date');
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

        if ($date) {
            $carbon = Carbon::parse($date);
            $dayOfWeek = $carbon->format('l');
            $query->where('day_of_week', $dayOfWeek);
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();
        return response()->json($schedules);
    }

    /**
     * Return the authenticated user's schedules grouped by day (for calendar views)
     */
    public function calendar(Request $request)
    {
        $user = $request->user();
        $start = $request->input('start');
        $end = $request->input('end');
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

        // Filter by date range (if provided)
        if ($start && $end) {
            $startDate = \Carbon\Carbon::parse($start);
            $endDate = \Carbon\Carbon::parse($end);
            $daysOfWeek = [];
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $daysOfWeek[] = $date->format('l');
            }
            $query->whereIn('day_of_week', $daysOfWeek);
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();
        $grouped = $schedules->groupBy('day_of_week');
        return response()->json($grouped);
    }

    /**
     * Export the authenticated user's schedule as a PDF
     */
    public function exportPdf(Request $request)
    {
        $user = $request->user();
        $query = ClassSchedule::query()->with(['class', 'teacher', 'room']);

        if ($user->hasRole('student')) {
            $student = $user->student ?? null;
            if ($student) {
                $classId = $student->class_id;
                $query->where('class_id', $classId);
            } else {
                return response()->json(['error' => 'No student record found.'], 404);
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

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('timetable::class_schedules_pdf', ['schedules' => $schedules]);
        return $pdf->download('my_schedule.pdf');
    }

    /**
     * Export the authenticated user's schedule as a CSV
     */
    public function exportCsv(Request $request)
    {
        $user = $request->user();
        $query = ClassSchedule::query()->with(['class', 'teacher', 'room']);

        if ($user->hasRole('student')) {
            $student = $user->student ?? null;
            if ($student) {
                $classId = $student->class_id;
                $query->where('class_id', $classId);
            } else {
                return response()->json(['error' => 'No student record found.'], 404);
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

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="my_schedule.csv"',
        ];
        $columns = ['class', 'subject', 'room', 'start_time', 'end_time', 'day_of_week'];
        $callback = function() use ($schedules, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($schedules as $schedule) {
                fputcsv($file, [
                    optional($schedule->class)->name,
                    $schedule->subject ?? '',
                    optional($schedule->room)->name,
                    $schedule->start_time,
                    $schedule->end_time,
                    $schedule->day_of_week,
                ]);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
} 