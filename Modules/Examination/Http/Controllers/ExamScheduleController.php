<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Examination\Models\Exam;
use Modules\Examination\Models\ExamSchedule;

class ExamScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = ExamSchedule::with('exam');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('class_name', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('room_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('exam_id')) {
            $query->where('exam_id', $request->exam_id);
        }

        if ($request->filled('date_from')) {
            $query->where('exam_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('exam_date', '<=', $request->date_to);
        }

        $schedules = $query->orderByDesc('exam_date')->paginate(15);

        return view('examination::schedules.index', compact('schedules'));
    }

    public function create()
    {
        $exams = Exam::orderBy('name')->get();

        return view('examination::schedules.create', compact('exams'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'class_name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'max_students' => 'nullable|integer|min:1',
            'invigilators' => 'nullable|array',
            'invigilators.*' => 'string|max:255',
            'instructions' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        ExamSchedule::create($data);

        return redirect()->route('examination.schedules.index')
            ->with('success', 'Schedule created successfully!');
    }

    public function show($id)
    {
        $schedule = ExamSchedule::with('exam')->findOrFail($id);

        return view('examination::schedules.show', compact('schedule'));
    }

    public function edit($id)
    {
        $schedule = ExamSchedule::findOrFail($id);
        $exams = Exam::orderBy('name')->get();

        return view('examination::schedules.edit', compact('schedule', 'exams'));
    }

    public function update(Request $request, $id)
    {
        $schedule = ExamSchedule::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'class_name' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'max_students' => 'nullable|integer|min:1',
            'invigilators' => 'nullable|array',
            'invigilators.*' => 'string|max:255',
            'instructions' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        if ($request->has('is_active')) {
            $data['is_active'] = $request->boolean('is_active');
        }

        $schedule->update($data);

        return redirect()->route('examination.schedules.index')
            ->with('success', 'Schedule updated successfully!');
    }

    public function destroy($id)
    {
        $schedule = ExamSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('examination.schedules.index')
            ->with('success', 'Schedule deleted successfully!');
    }

    public function timetable()
    {
        $schedules = ExamSchedule::with('exam')
            ->where('is_active', true)
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function ($schedule) {
                return $schedule->exam_date->format('Y-m-d');
            });

        return view('examination::schedules.timetable', compact('schedules'));
    }
}
