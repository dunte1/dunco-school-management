<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Models\ExamResult;

class ResultController extends Controller
{
    public function index()
    {
        $results = ExamResult::with(['exam', 'student'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('examination::results.index', compact('results'));
    }

    public function create()
    {
        return view('examination::results.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'student_id' => 'required|exists:users,id',
            'exam_attempt_id' => 'nullable|exists:exam_attempts,id',
            'total_marks' => 'required|numeric|min:0',
            'obtained_marks' => 'required|numeric|min:0',
            'grade' => 'nullable|string|max:10',
            'remarks' => 'nullable|string',
        ]);

        $data['percentage'] = $data['total_marks'] > 0
            ? round(($data['obtained_marks'] / $data['total_marks']) * 100, 2)
            : 0;

        ExamResult::create($data);

        return redirect()->route('examination.results.index')->with('success', 'Result recorded.');
    }

    public function show($id)
    {
        $result = ExamResult::with(['exam', 'student'])->findOrFail($id);

        return view('examination::results.show', compact('result'));
    }

    public function edit($id)
    {
        $result = ExamResult::with(['exam', 'student'])->findOrFail($id);

        return view('examination::results.edit', compact('result'));
    }

    public function update(Request $request, $id)
    {
        $result = ExamResult::findOrFail($id);

        $data = $request->validate([
            'total_marks' => 'required|numeric|min:0',
            'obtained_marks' => 'required|numeric|min:0',
            'grade' => 'nullable|string|max:10',
            'remarks' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $data['percentage'] = $data['total_marks'] > 0
            ? round(($data['obtained_marks'] / $data['total_marks']) * 100, 2)
            : 0;

        $result->update($data);

        return redirect()->route('examination.results.index')->with('success', 'Result updated.');
    }

    public function destroy($id)
    {
        ExamResult::findOrFail($id)->delete();

        return redirect()->route('examination.results.index')->with('success', 'Result deleted.');
    }

    public function publishResults($exam)
    {
        ExamResult::where('exam_id', $exam)->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Results published successfully.');
    }

    public function transcript($student)
    {
        $results = ExamResult::with('exam')
            ->where('student_id', $student)
            ->orderByDesc('created_at')
            ->get();

        $studentUser = \App\Models\User::find($student);

        return view('examination::results.transcript', compact('results', 'studentUser'));
    }

    public function rankings($exam)
    {
        $results = ExamResult::with('student')
            ->where('exam_id', $exam)
            ->orderByDesc('percentage')
            ->get();

        $examModel = \Modules\Examination\Models\Exam::find($exam);

        return view('examination::results.rankings', compact('results', 'examModel'));
    }

    public function analytics()
    {
        $stats = [
            'total' => ExamResult::count(),
            'published' => ExamResult::where('is_published', true)->count(),
            'average' => round((float) ExamResult::avg('percentage'), 2),
            'pass' => ExamResult::where('result_status', 'pass')->count(),
        ];

        return view('examination::results.analytics', compact('stats'));
    }

    public function studentResults()
    {
        $results = ExamResult::with('exam')
            ->where('student_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('examination::results.index', compact('results'));
    }
}
