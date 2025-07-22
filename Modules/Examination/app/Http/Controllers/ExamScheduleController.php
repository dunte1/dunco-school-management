<?php

namespace Modules\Examination\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\Examination\app\Models\ExamSchedule;
use Modules\Examination\app\Models\Exam;

class ExamScheduleController extends Controller
{
    public function index(): View
    {
        $schedules = ExamSchedule::with(['exam'])
                                ->active()
                                ->orderBy('exam_date')
                                ->orderBy('start_time')
                                ->paginate(15);

        return view('examination::schedules.index', compact('schedules'));
    }

    public function create(): View
    {
        $exams = Exam::active()->get();
        return view('examination::schedules.create', compact('exams'));
    }

    public function store(Request $request): JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'class_name' => 'required|string|max:100',
            'section' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:100',
            'stream' => 'nullable|string|max:100',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'max_students' => 'nullable|integer|min:1',
            'invigilators' => 'nullable|array',
            'instructions' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $schedule = ExamSchedule::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Schedule created successfully',
                'schedule' => $schedule->load('exam')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(ExamSchedule $schedule): View
    {
        $schedule->load('exam');
        return view('examination::schedules.show', compact('schedule'));
    }

    public function edit(ExamSchedule $schedule): View
    {
        $exams = Exam::active()->get();
        return view('examination::schedules.edit', compact('schedule', 'exams'));
    }

    public function update(Request $request, ExamSchedule $schedule): JsonResponse
    {
        $validator = \Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'class_name' => 'required|string|max:100',
            'section' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:100',
            'stream' => 'nullable|string|max:100',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'max_students' => 'nullable|integer|min:1',
            'invigilators' => 'nullable|array',
            'instructions' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $schedule->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Schedule updated successfully',
                'schedule' => $schedule->load('exam')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(ExamSchedule $schedule): JsonResponse
    {
        try {
            $schedule->delete();

            return response()->json([
                'success' => true,
                'message' => 'Schedule deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    public function timetable(): View
    {
        $schedules = ExamSchedule::with(['exam'])
                                ->active()
                                ->upcoming()
                                ->orderBy('exam_date')
                                ->orderBy('start_time')
                                ->get()
                                ->groupBy('exam_date');

        return view('examination::schedules.timetable', compact('schedules'));
    }
} 