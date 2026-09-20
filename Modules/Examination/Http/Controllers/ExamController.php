<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Examination\Models\Exam;
use Modules\Examination\Models\ExamType;
use Modules\Examination\Models\QuestionCategory;
use Modules\Examination\Models\Question;
use Modules\Examination\Models\ExamResult;
use Modules\Examination\Models\ExamAnswer;

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
            'max_attempts' => 'nullable|integer|min:1',
            'allow_retake' => 'boolean',
        ]);

        $data['status'] = 'draft';
        $data['is_active'] = true;

        Exam::create($data);

        return redirect()->route('examination.exams.index')->with('success', 'Exam created successfully.');
    }

    public function show($id)
    {
        $exam = Exam::with(['type', 'questions.category', 'results.student'])->findOrFail($id);
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
            'max_attempts' => 'nullable|integer|min:1',
            'allow_retake' => 'boolean',
        ]);

        $exam->update($data);
        return redirect()->route('examination.exams.index')->with('success', 'Exam updated successfully.');
    }

    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);

        $hasAttempts = $exam->attempts()->count() > 0;
        if ($hasAttempts) {
            return redirect()->back()->with('error', 'Cannot delete exam with existing attempts. Archive it instead.');
        }

        $exam->questions()->detach();
        $exam->delete();
        return redirect()->route('examination.exams.index')->with('success', 'Exam deleted successfully.');
    }

    public function publish($exam)
    {
        $exam = Exam::findOrFail($exam);

        if ($exam->questions()->count() === 0) {
            return redirect()->back()->with('error', 'Cannot publish exam with no questions. Add questions first.');
        }

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
        $exam = Exam::with('questions')->findOrFail($exam);
        $categories = QuestionCategory::orderBy('name')->get();

        $availableQuestions = Question::where('is_active', true)
            ->whereNotIn('id', $exam->questions->pluck('id'))
            ->with('category')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('examination::exams.add-questions', compact('exam', 'categories', 'availableQuestions'));
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

        $request = request();
        $categoryId = $request->input('category_id');
        $difficulty = $request->input('difficulty');
        $count = $request->input('count', 10);

        $query = Question::where('is_active', true)
            ->whereNotIn('id', $exam->questions->pluck('id'));

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        if ($difficulty) {
            $query->where('difficulty', $difficulty);
        }

        $questions = $query->inRandomOrder()->limit($count)->get();

        if ($questions->isEmpty()) {
            return redirect()->back()->with('error', 'No questions available matching the criteria.');
        }

        $order = $exam->questions()->count() + 1;
        foreach ($questions as $question) {
            $exam->questions()->attach($question->id, [
                'order' => $order++,
                'marks' => $question->marks,
                'is_required' => true,
            ]);
        }

        return redirect()->back()->with('success', "Added {$questions->count()} random questions to the exam.");
    }

    public function results($exam)
    {
        $results = ExamResult::with('student')
            ->where('exam_id', $exam)
            ->orderByDesc('percentage')
            ->get();
        $examModel = Exam::find($exam);

        $stats = [
            'total_students' => $results->count(),
            'average_percentage' => $results->avg('percentage'),
            'highest_percentage' => $results->max('percentage'),
            'lowest_percentage' => $results->min('percentage'),
            'pass_count' => $results->where('result_status', 'pass')->count(),
            'fail_count' => $results->where('result_status', 'fail')->count(),
            'pass_rate' => $results->count() > 0
                ? round(($results->where('result_status', 'pass')->count() / $results->count()) * 100, 1)
                : 0,
        ];

        return view('examination::exams.results', compact('results', 'examModel', 'stats'));
    }

    public function exportResults($exam)
    {
        $results = ExamResult::with('student')->where('exam_id', $exam)->get();
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
        $exams = Exam::where('is_active', true)
            ->where('start_date', '>=', now())
            ->orderBy('start_date')
            ->paginate(20);
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
        $pendingGrading = ExamAnswer::where('is_graded', false)
            ->whereHas('attempt', function ($q) {
                $q->where('status', 'submitted');
            })
            ->with(['attempt.exam', 'question', 'attempt.student'])
            ->orderByDesc('answered_at')
            ->paginate(20);

        return view('examination::teacher.grade', compact('pendingGrading'));
    }

    public function gradeAnswer(Request $request, $answerId)
    {
        $answer = ExamAnswer::with(['question', 'attempt'])->findOrFail($answerId);

        $request->validate([
            'marks_obtained' => 'required|numeric|min:0|max:' . $answer->max_marks,
            'feedback' => 'nullable|string|max:2000',
        ]);

        $answer->update([
            'marks_obtained' => $request->marks_obtained,
            'feedback' => $request->feedback,
            'is_graded' => true,
            'is_correct' => $request->marks_obtained > 0,
            'auto_grade_data' => array_merge($answer->auto_grade_data ?? [], [
                'manual_grade' => true,
                'graded_by' => auth()->id(),
                'graded_at' => now()->toIso8601String(),
            ]),
        ]);

        $this->recalculateAttemptResult($answer->attempt);

        return redirect()->back()->with('success', 'Answer graded successfully.');
    }

    public function examAnalytics()
    {
        $stats = [
            'total_exams' => Exam::count(),
            'published' => Exam::where('status', 'published')->count(),
            'completed' => Exam::where('status', 'completed')->count(),
            'ongoing' => Exam::where('status', 'ongoing')->count(),
            'total_attempts' => \Modules\Examination\Models\ExamAttempt::count(),
            'average_score' => ExamResult::avg('percentage'),
            'overall_pass_rate' => ExamResult::count() > 0
                ? round((ExamResult::where('result_status', 'pass')->count() / ExamResult::count()) * 100, 1)
                : 0,
        ];

        $recentResults = ExamResult::with(['exam', 'student'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('examination::teacher.analytics', compact('stats', 'recentResults'));
    }

    public function adminDashboard()
    {
        $stats = [
            'total_exams' => Exam::count(),
            'total_types' => ExamType::count(),
            'active_exams' => Exam::where('status', 'ongoing')->count(),
            'total_questions' => Question::count(),
            'total_attempts' => \Modules\Examination\Models\ExamAttempt::count(),
            'published_exams' => Exam::where('status', 'published')->count(),
            'completed_exams' => Exam::where('status', 'completed')->count(),
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

    protected function recalculateAttemptResult($attempt)
    {
        $answers = $attempt->answers()->get();
        $totalObtained = $answers->sum('marks_obtained');
        $totalPossible = $answers->sum('max_marks');

        $exam = $attempt->exam;
        if ($totalPossible > 0) {
            $totalPossible = (float) $exam->total_marks;
        }

        $percentage = $totalPossible > 0 ? round(($totalObtained / $totalPossible) * 100, 2) : 0;
        $passingPercentage = $totalPossible > 0
            ? round(($exam->passing_marks / $totalPossible) * 100, 2)
            : 0;

        $grade = $this->calculateGrade($percentage);
        $resultStatus = $percentage >= $passingPercentage ? 'pass' : 'fail';

        ExamResult::where('exam_attempt_id', $attempt->id)->update([
            'total_marks' => $totalPossible,
            'obtained_marks' => $totalObtained,
            'percentage' => $percentage,
            'grade' => $grade,
            'result_status' => $resultStatus,
        ]);

        $attempt->update([
            'total_marks' => $totalPossible,
            'obtained_marks' => $totalObtained,
        ]);
    }

    protected function calculateGrade($percentage): string
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 30) return 'D';
        return 'F';
    }
}
