<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Modules\Examination\Models\Exam;
use Modules\Examination\Models\ExamAttempt;
use Modules\Examination\Models\ExamAnswer;
use Modules\Examination\Models\ExamResult;
use App\Models\User;

class StudentExamController extends Controller
{
    public function index()
    {
        $studentId = auth()->id();

        $exams = Exam::where('is_active', true)
            ->where('status', 'published')
            ->where('is_online', false)
            ->withCount('questions')
            ->with(['type', 'attempts' => function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            }])
            ->orderBy('start_date')
            ->paginate(15);

        return view('examination::student-exams.index', compact('exams'));
    }

    public function show($id)
    {
        $exam = Exam::with(['type', 'questions', 'schedules'])
            ->findOrFail($id);

        $studentId = auth()->id();
        $attempts = ExamAttempt::where('exam_id', $id)
            ->where('student_id', $studentId)
            ->orderByDesc('created_at')
            ->get();

        $hasCompleted = $attempts->where('status', 'completed')->count() > 0;
        $hasStarted = $attempts->whereIn('status', ['started', 'in_progress'])->first();

        return view('examination::student-exams.show', compact('exam', 'attempts', 'hasCompleted', 'hasStarted'));
    }

    public function start($examId)
    {
        $exam = Exam::findOrFail($examId);
        $studentId = auth()->id();

        $existingActive = ExamAttempt::where('exam_id', $examId)
            ->where('student_id', $studentId)
            ->whereIn('status', ['started', 'in_progress'])
            ->first();

        if ($existingActive) {
            return redirect()->route('examination.student-exams.show', $examId)
                ->with('error', 'You already have an active attempt for this exam.');
        }

        if (!$exam->allow_retake) {
            $completedCount = ExamAttempt::where('exam_id', $examId)
                ->where('student_id', $studentId)
                ->where('status', 'completed')
                ->count();

            if ($completedCount > 0) {
                return redirect()->route('examination.student-exams.show', $examId)
                    ->with('error', 'Retake is not allowed for this exam.');
            }
        }

        if ($exam->max_attempts) {
            $totalAttempts = ExamAttempt::where('exam_id', $examId)
                ->where('student_id', $studentId)
                ->count();

            if ($totalAttempts >= $exam->max_attempts) {
                return redirect()->route('examination.student-exams.show', $examId)
                    ->with('error', 'You have reached the maximum number of attempts for this exam.');
            }
        }

        $attempt = ExamAttempt::create([
            'exam_id' => $examId,
            'student_id' => $studentId,
            'attempt_code' => strtoupper(Str::random(12)),
            'started_at' => now(),
            'expires_at' => $exam->duration_minutes ? now()->addMinutes($exam->duration_minutes) : null,
            'status' => 'started',
            'total_marks' => $exam->total_marks,
        ]);

        return redirect()->route('examination.student-exams.show', $examId)
            ->with('success', 'Exam attempt started. Good luck!');
    }

    public function submit(Request $request, $examId)
    {
        $studentId = auth()->id();

        $attempt = ExamAttempt::where('exam_id', $examId)
            ->where('student_id', $studentId)
            ->whereIn('status', ['started', 'in_progress'])
            ->first();

        if (!$attempt) {
            return redirect()->route('examination.student-exams.index')
                ->with('error', 'No active attempt found for this exam.');
        }

        $answers = $request->input('answers', []);

        foreach ($answers as $questionId => $answerData) {
            $studentAnswer = is_array($answerData) ? $answerData : ['value' => $answerData];

            ExamAnswer::updateOrCreate(
                [
                    'exam_attempt_id' => $attempt->id,
                    'question_id' => $questionId,
                ],
                [
                    'student_answer' => $studentAnswer,
                    'answered_at' => now(),
                ]
            );
        }

        $this->autoGradeAttempt($attempt);

        $attempt->update([
            'status' => 'completed',
            'submitted_at' => now(),
        ]);

        $this->createResult($attempt);

        return redirect()->route('examination.student-exams.result', $examId)
            ->with('success', 'Exam submitted successfully!');
    }

    public function result($examId)
    {
        $studentId = auth()->id();

        $attempt = ExamAttempt::with(['exam', 'answers.question'])
            ->where('exam_id', $examId)
            ->where('student_id', $studentId)
            ->where('status', 'completed')
            ->orderByDesc('submitted_at')
            ->firstOrFail();

        $result = ExamResult::where('exam_attempt_id', $attempt->id)->first();

        return view('examination::student-exams.result', compact('attempt', 'result'));
    }

    public function history()
    {
        $studentId = auth()->id();

        $attempts = ExamAttempt::with(['exam.type', 'result'])
            ->where('student_id', $studentId)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('examination::student-exams.history', compact('attempts'));
    }

    protected function autoGradeAttempt(ExamAttempt $attempt)
    {
        $exam = $attempt->exam;
        $answers = $attempt->answers()->with('question')->get();

        foreach ($answers as $answer) {
            $question = $answer->question;
            if (!$question) {
                continue;
            }

            $correctAnswers = $question->correct_answers ?? [];
            $studentAnswer = $answer->student_answer ?? [];
            $studentValue = $studentAnswer['value'] ?? reset($studentAnswer) ?? '';
            $isCorrect = in_array($studentValue, $correctAnswers);

            $marksObtained = $isCorrect ? $question->marks : 0;
            if ($exam->negative_marking && !$isCorrect) {
                $marksObtained = -$exam->negative_marking;
            }

            $answer->update([
                'marks_obtained' => max(0, $marksObtained),
                'max_marks' => $question->marks,
                'is_correct' => $isCorrect,
                'is_graded' => true,
            ]);
        }
    }

    protected function createResult(ExamAttempt $attempt)
    {
        $answers = $attempt->answers;
        $obtainedMarks = $answers->sum('marks_obtained');
        $totalMarks = $attempt->total_marks;
        $percentage = $totalMarks > 0 ? round(($obtainedMarks / $totalMarks) * 100, 2) : 0;

        $passingPercentage = $totalMarks > 0
            ? round(($attempt->exam->passing_marks / $totalMarks) * 100, 2)
            : 0;

        $resultStatus = $percentage >= $passingPercentage ? 'pass' : 'fail';
        $grade = $this->calculateGrade($percentage);

        ExamResult::create([
            'exam_id' => $attempt->exam_id,
            'student_id' => $attempt->student_id,
            'exam_attempt_id' => $attempt->id,
            'total_marks' => $totalMarks,
            'obtained_marks' => $obtainedMarks,
            'percentage' => $percentage,
            'grade' => $grade,
            'result_status' => $resultStatus,
        ]);

        $attempt->update([
            'obtained_marks' => $obtainedMarks,
            'is_graded' => true,
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
