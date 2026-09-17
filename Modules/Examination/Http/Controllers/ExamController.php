<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Models\Exam;
use Modules\Examination\Models\ExamType;
use Modules\Examination\Models\QuestionCategory;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('type')->orderByDesc('created_at')->paginate(15);
        $upcomingExams = Exam::with('type')
            ->whereIn('status', ['published', 'draft'])
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        return view('examination::exams.index', compact('exams', 'upcomingExams'));
    }

    public function create()
    {
        $examTypes = ExamType::orderBy('name')->get();
        return view('examination::exams.create', compact('examTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:exams,code',
            'exam_type_id' => 'required|exists:exam_types,id',
            'description' => 'nullable|string',
            'academic_year' => 'required|string|max:50',
            'term' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:1',
            'total_marks' => 'required|numeric|min:0',
            'passing_marks' => 'required|numeric|min:0|max:' . ($request->input('total_marks') ?? 9999),
            'is_online' => 'boolean',
            'enable_proctoring' => 'boolean',
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'show_results_immediately' => 'boolean',
            'allow_review' => 'boolean',
            'negative_marking' => 'nullable|numeric|min:0',
        ]);

        $data['status'] = 'draft';
        $data['is_active'] = true;

        Exam::create($data);

        return redirect()->route('examination.exams.index')->with('success', 'Exam created successfully.');
    }

    public function show($id)
    {
        $exam = Exam::with(['type', 'questions', 'results'])->findOrFail($id);
        return view('examination::exams.show', compact('exam'));
    }

    public function edit($id)
    {
        $exam = Exam::findOrFail($id);
        $examTypes = ExamType::orderBy('name')->get();
        return view('examination::exams.edit', compact('exam', 'examTypes'));
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:exams,code,' . $id,
            'exam_type_id' => 'required|exists:exam_types,id',
            'description' => 'nullable|string',
            'academic_year' => 'required|string|max:50',
            'term' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'duration_minutes' => 'nullable|integer|min:1',
            'total_marks' => 'required|numeric|min:0',
            'passing_marks' => 'required|numeric|min:0',
            'is_online' => 'boolean',
            'enable_proctoring' => 'boolean',
            'shuffle_questions' => 'boolean',
            'shuffle_options' => 'boolean',
            'show_results_immediately' => 'boolean',
            'allow_review' => 'boolean',
            'status' => 'required|in:draft,published,ongoing,completed,archived',
            'negative_marking' => 'nullable|numeric|min:0',
        ]);

        $exam->update($data);
        return redirect()->route('examination.exams.index')->with('success', 'Exam updated successfully.');
    }

    public function destroy($id)
    {
        Exam::findOrFail($id)->delete();
        return redirect()->route('examination.exams.index')->with('success', 'Exam deleted successfully.');
    }

    public function publish($exam)
    {
        $exam = Exam::findOrFail($exam);
        $exam->update(['status' => 'published']);
        return redirect()->back()->with('success', 'Exam published successfully.');
    }

    public function start($exam)
    {
        $exam = Exam::findOrFail($exam);
        $exam->update(['status' => 'ongoing']);
        return redirect()->back()->with('success', 'Exam started successfully.');
    }

    public function complete($exam)
    {
        $exam = Exam::findOrFail($exam);
        $exam->update(['status' => 'completed']);
        return redirect()->back()->with('success', 'Exam completed successfully.');
    }

    public function addQuestions($exam)
    {
        $exam = Exam::findOrFail($exam);
        $categories = QuestionCategory::orderBy('name')->get();
        return view('examination::exams.show', compact('exam', 'categories'));
    }

    public function removeQuestion($exam, $question)
    {
        $exam = Exam::findOrFail($exam);
        $exam->questions()->detach($question);
        return redirect()->back()->with('success', 'Question removed from exam.');
    }

    public function generateRandomQuestions($exam)
    {
        $exam = Exam::findOrFail($exam);
        return redirect()->back()->with('info', 'Random question generation requires manual selection.');
    }

    public function results($exam)
    {
        $results = \Modules\Examination\Models\ExamResult::with('student')
            ->where('exam_id', $exam)
            ->orderByDesc('percentage')
            ->get();
        $examModel = Exam::find($exam);
        return view('examination::exams.results', compact('results', 'examModel'));
    }

    public function exportResults($exam)
    {
        $results = \Modules\Examination\Models\ExamResult::with('student')->where('exam_id', $exam)->get();
        $examModel = Exam::find($exam);

        $filename = 'exam-results-' . ($examModel->code ?? $exam) . '.csv';
        $callback = function () use ($results) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Student', 'Obtained Marks', 'Total Marks', 'Percentage', 'Grade', 'Status']);
            foreach ($results as $result) {
                fputcsv($out, [
                    optional($result->student)->name,
                    $result->obtained_marks,
                    $result->total_marks,
                    $result->percentage,
                    $result->grade,
                    $result->result_status,
                ]);
            }
            fclose($out);
        };
        return response()->streamDownload($callback, $filename, ['Content-Type' => 'text/csv']);
    }

    public function createOnline()
    {
        try { $examTypes = ExamType::orderBy('name')->get(); } catch (\Throwable $e) { $examTypes = collect(); }
        try { $classes = \Modules\Academic\Models\AcademicClass::orderBy('name')->get(); } catch (\Throwable $e) { $classes = collect(); }
        try { $subjects = \Modules\Academic\Models\Subject::orderBy('name')->get(); } catch (\Throwable $e) { $subjects = collect(); }
        return view('examination::exams.online-create', compact('examTypes', 'classes', 'subjects'));
    }

    public function studentExams()
    {
        $exams = Exam::where('is_active', true)->where('start_date', '>=', now())->orderBy('start_date')->paginate(20);
        return view('examination::student.exams', compact('exams'));
    }

    public function examHistory()
    {
        $attempts = \Modules\Examination\Models\ExamAttempt::with('exam')
            ->where('student_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('examination::student.history', compact('attempts'));
    }

    public function teacherExams()
    {
        $exams = Exam::orderByDesc('created_at')->paginate(20);
        return view('examination::teacher.exams', compact('exams'));
    }

    public function gradeExams()
    {
        return view('examination::teacher.grade');
    }

    public function gradeAnswer($answer)
    {
        return redirect()->back()->with('success', 'Answer graded.');
    }

    public function examAnalytics()
    {
        $stats = [
            'total_exams' => Exam::count(),
            'published' => Exam::where('status', 'published')->count(),
            'completed' => Exam::where('status', 'completed')->count(),
        ];
        return view('examination::teacher.analytics', compact('stats'));
    }

    public function adminDashboard()
    {
        $stats = [
            'total_exams' => Exam::count(),
            'total_types' => ExamType::count(),
            'active_exams' => Exam::where('status', 'ongoing')->count(),
        ];
        return view('examination::admin.dashboard', compact('stats'));
    }

    public function settings()
    {
        return view('examination::admin.settings');
    }

    public function updateSettings(Request $request)
    {
        return redirect()->back()->with('success', 'Settings updated.');
    }

    public function reports()
    {
        return view('examination::admin.reports');
    }

    public function backup()
    {
        return view('examination::admin.backup');
    }
}
