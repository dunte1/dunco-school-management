<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Examination\Models\Exam;
use Modules\Examination\Models\ExamAttempt;
use Modules\Examination\Models\ExamAnswer;
use Modules\Examination\Models\ExamResult;
use Modules\Examination\Models\Question;
use Modules\Examination\Models\ProctoringLog;

class OnlineExamController extends Controller
{
    public function index()
    {
        $exams = Exam::where('is_online', true)
            ->orderByDesc('start_date')
            ->paginate(20);

        return view('examination::online.index', compact('exams'));
    }

    public function startExam($exam)
    {
        $exam = Exam::with('questions')->findOrFail($exam);

        $existingAttempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', auth()->id())
            ->whereIn('status', ['in_progress', 'started'])
            ->first();

        if ($existingAttempt) {
            return redirect()->route('examination.online.take', $exam->id);
        }

        $maxAttempts = $exam->max_attempts ?? 1;
        $attemptsUsed = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', auth()->id())
            ->whereIn('status', ['submitted', 'completed'])
            ->count();

        if ($attemptsUsed >= $maxAttempts && !$exam->allow_retake) {
            return redirect()->back()->with('error', 'You have used all available attempts for this exam.');
        }

        return view('examination::online.start', compact('exam', 'attemptsUsed'));
    }

    public function takeExam($exam)
    {
        $exam = Exam::with('questions')->findOrFail($exam);
        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', auth()->id())
            ->whereIn('status', ['in_progress', 'started'])
            ->latest()
            ->first();

        if (!$attempt) {
            return redirect()->route('examination.online.start', $exam->id)
                ->with('error', 'No active attempt found. Please start an exam first.');
        }

        return view('examination::online.exam', compact('exam', 'attempt'));
    }

    public function getExamData($attempt)
    {
        $attempt = ExamAttempt::with([
            'exam.questions.category',
            'answers.question'
        ])->findOrFail($attempt);

        if ($attempt->student_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this attempt.');
        }

        $questions = $attempt->exam->questions;
        if ($attempt->exam->shuffle_questions) {
            $questions = $questions->shuffle();
        }

        $attempt->setRelation('questions', $questions);

        return response()->json(['data' => $attempt]);
    }

    public function startAttempt(Request $request, $attemptId)
    {
        $attempt = ExamAttempt::findOrFail($attemptId);

        if ($attempt->student_id !== auth()->id()) {
            abort(403);
        }

        if ($attempt->status !== 'started' && $attempt->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Attempt already started or completed.'], 422);
        }

        $now = now();
        $attempt->update([
            'status' => 'in_progress',
            'started_at' => $now,
            'expires_at' => $attempt->exam->duration_minutes
                ? $now->copy()->addMinutes($attempt->exam->duration_minutes)
                : null,
            'device_info' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        return response()->json([
            'success' => true,
            'started_at' => $now->toIso8601String(),
            'expires_at' => $attempt->expires_at ? $attempt->expires_at->toIso8601String() : null,
        ]);
    }

    public function saveAnswer(Request $request, $attemptId)
    {
        $attempt = ExamAttempt::where('id', $attemptId)
            ->where('student_id', auth()->id())
            ->where('status', 'in_progress')
            ->firstOrFail();

        $now = now();
        if ($attempt->expires_at && $now->gt($attempt->expires_at)) {
            $this->finalizeAttempt($attempt);
            return response()->json(['success' => false, 'message' => 'Exam time has expired.'], 422);
        }

        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'student_answer' => 'required',
        ]);

        $answer = ExamAnswer::updateOrCreate(
            [
                'exam_attempt_id' => $attempt->id,
                'question_id' => $request->question_id,
            ],
            [
                'student_answer' => $request->student_answer,
                'essay_answer' => $request->input('essay_answer'),
                'code_answer' => $request->input('code_answer'),
                'answered_at' => $now,
            ]
        );

        if ($request->has('time_spent_seconds')) {
            $answer->update(['time_spent_seconds' => $request->time_spent_seconds]);
        }

        return response()->json([
            'success' => true,
            'answer_id' => $answer->id,
            'saved_at' => $now->toIso8601String(),
        ]);
    }

    public function submitExam(Request $request, $attemptId)
    {
        $attempt = ExamAttempt::where('id', $attemptId)
            ->where('student_id', auth()->id())
            ->where('status', 'in_progress')
            ->firstOrFail();

        $this->finalizeAttempt($attempt);

        if ($attempt->exam->show_results_immediately) {
            return response()->json([
                'success' => true,
                'redirect' => route('examination.online.result', $attempt->id),
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Exam submitted successfully.']);
    }

    public function logProctoringEvent(Request $request, $attemptId)
    {
        $attempt = ExamAttempt::where('id', $attemptId)
            ->where('student_id', auth()->id())
            ->firstOrFail();

        $request->validate([
            'event_type' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'severity' => 'nullable|string|in:low,medium,high,critical',
        ]);

        $log = ProctoringLog::create([
            'exam_attempt_id' => $attempt->id,
            'event_type' => $request->event_type,
            'description' => $request->description,
            'severity' => $request->severity ?? 'low',
            'event_data' => [
                'timestamp' => now()->toIso8601String(),
                'ip' => $request->ip(),
            ],
        ]);

        return response()->json(['success' => true, 'log_id' => $log->id]);
    }

    public function getProctoringStatus($attemptId)
    {
        $attempt = ExamAttempt::with('proctoringLogs')->findOrFail($attemptId);
        $violations = $attempt->proctoringLogs()->where('severity', 'high')->count();

        return response()->json([
            'status' => $attempt->status,
            'violations' => $violations,
            'flagged' => $violations >= 3,
        ]);
    }

    public function examResult($attemptId)
    {
        $attempt = ExamAttempt::with(['exam', 'answers.question'])->findOrFail($attemptId);
        $answers = $attempt->answers;
        $result = $attempt->result;
        $showCorrect = false;
        $studentFile = null;

        return view('examination::online.result', compact('attempt', 'answers', 'result', 'showCorrect', 'studentFile'));
    }

    public function downloadAnswerFile($answer)
    {
        $answer = ExamAnswer::findOrFail($answer);

        if ($answer->student_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'teacher'])) {
            abort(403);
        }

        if (!$answer->file_path || !\Illuminate\Support\Facades\Storage::disk('local')->exists($answer->file_path)) {
            abort(404, 'Answer file not found.');
        }

        return \Illuminate\Support\Facades\Storage::disk('local')->download($answer->file_path);
    }

    public function heartbeat(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|exists:exam_attempts,id',
        ]);

        $attempt = ExamAttempt::where('id', $request->attempt_id)
            ->where('student_id', auth()->id())
            ->first();

        if (!$attempt) {
            return response()->json(['status' => 'not_found'], 404);
        }

        $expired = $attempt->expires_at && now()->gt($attempt->expires_at);
        if ($expired && $attempt->status === 'in_progress') {
            $this->finalizeAttempt($attempt);
            return response()->json(['status' => 'expired', 'message' => 'Exam time expired.']);
        }

        $remaining = $attempt->expires_at
            ? max(0, $attempt->expires_at->diffInSeconds(now()))
            : null;

        return response()->json([
            'status' => $attempt->status,
            'remaining_seconds' => $remaining,
            'alive' => true,
        ]);
    }

    public function screenshot(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|exists:exam_attempts,id',
            'image' => 'nullable|string',
        ]);

        $attempt = ExamAttempt::where('id', $request->attempt_id)
            ->where('student_id', auth()->id())
            ->first();

        if (!$attempt) {
            return response()->json(['success' => false, 'message' => 'Attempt not found.'], 404);
        }

        if ($request->has('image')) {
            $imageData = $request->input('image');
            $decoded = base64_decode(explode(',', $imageData)[1] ?? '');
            $filename = 'screenshots/' . $attempt->id . '/' . time() . '.png';
            \Illuminate\Support\Facades\Storage::disk('local')->put($filename, $decoded);

            ProctoringLog::create([
                'exam_attempt_id' => $attempt->id,
                'event_type' => 'screenshot',
                'description' => 'Screenshot captured',
                'event_data' => ['path' => $filename],
                'severity' => 'low',
            ]);
        }

        return response()->json(['status' => 'captured']);
    }

    public function autoSave(Request $request, $attemptId)
    {
        $attempt = ExamAttempt::where('id', $attemptId)
            ->where('student_id', auth()->id())
            ->where('status', 'in_progress')
            ->first();

        if (!$attempt) {
            return response()->json(['success' => false, 'message' => 'No active attempt.'], 404);
        }

        $answers = $request->input('answers', []);
        $saved = 0;

        foreach ($answers as $answerData) {
            if (!isset($answerData['question_id'])) continue;

            ExamAnswer::updateOrCreate(
                [
                    'exam_attempt_id' => $attempt->id,
                    'question_id' => $answerData['question_id'],
                ],
                [
                    'student_answer' => $answerData['student_answer'] ?? null,
                    'essay_answer' => $answerData['essay_answer'] ?? null,
                    'code_answer' => $answerData['code_answer'] ?? null,
                    'answered_at' => now(),
                ]
            );
            $saved++;
        }

        return response()->json([
            'success' => true,
            'saved_count' => $saved,
            'saved_at' => now()->toIso8601String(),
        ]);
    }

    public function examStatus($attemptId)
    {
        $attempt = ExamAttempt::with('exam')->findOrFail($attemptId);

        if ($attempt->student_id !== auth()->id()) {
            abort(403);
        }

        $expired = $attempt->expires_at && now()->gt($attempt->expires_at);
        if ($expired && $attempt->status === 'in_progress') {
            $this->finalizeAttempt($attempt);
        }

        $answeredCount = $attempt->answers()->count();
        $totalQuestions = $attempt->exam->questions()->count();

        return response()->json([
            'status' => $attempt->status,
            'started_at' => $attempt->started_at,
            'expires_at' => $attempt->expires_at,
            'answered_count' => $answeredCount,
            'total_questions' => $totalQuestions,
            'remaining_seconds' => $attempt->expires_at
                ? max(0, $attempt->expires_at->diffInSeconds(now()))
                : null,
        ]);
    }

    public function submit(Request $request, $examId)
    {
        $attempt = ExamAttempt::where('exam_id', $examId)
            ->where('student_id', auth()->id())
            ->where('status', 'in_progress')
            ->first();

        if ($attempt) {
            $this->finalizeAttempt($attempt);
        }

        return redirect()->route('examination.online.index')
            ->with('success', 'Exam submitted successfully.');
    }

    public function result($examId)
    {
        $attempt = ExamAttempt::with(['exam', 'answers.question'])
            ->where('exam_id', $examId)
            ->where('student_id', auth()->id())
            ->latest()
            ->first();

        $answers = $attempt ? $attempt->answers : collect();
        $result = $attempt ? $attempt->result : null;
        $showCorrect = false;
        $studentFile = null;

        return view('examination::online.result', compact('attempt', 'answers', 'result', 'showCorrect', 'studentFile'));
    }

    public function proctoring($examId)
    {
        $exam = Exam::find($examId);
        return view('examination::online.proctoring', compact('exam', 'examId'));
    }

    /**
     * Auto-grade answers and finalize the attempt
     */
    protected function finalizeAttempt(ExamAttempt $attempt)
    {
        DB::beginTransaction();
        try {
            $answers = $attempt->answers()->with('question')->get();
            $totalObtained = 0;
            $totalPossible = 0;

            foreach ($answers as $answer) {
                $question = $answer->question;
                if (!$question) continue;

                $totalPossible += (float) $question->marks;

                $isCorrect = false;
                $marksObtained = 0;

                $studentAnswer = is_array($answer->student_answer)
                    ? $answer->student_answer
                    : [$answer->student_answer];

                $correctAnswers = $question->correct_answers ?? [];

                if ($question->type === 'multiple_choice' || $question->type === 'true_false') {
                    $normalized = array_map('strtolower', array_map('trim', $studentAnswer));
                    $normalizedCorrect = array_map('strtolower', array_map('trim', $correctAnswers));
                    $isCorrect = empty(array_diff($normalizedCorrect, $normalized)) && count($normalized) === count($normalizedCorrect);
                    $marksObtained = $isCorrect ? (float) $question->marks : 0;
                } elseif ($question->type === 'fill_blank') {
                    $normalized = strtolower(trim($studentAnswer[0] ?? ''));
                    $isCorrect = in_array($normalized, array_map(function ($a) { return strtolower(trim($a)); }, $correctAnswers));
                    $marksObtained = $isCorrect ? (float) $question->marks : 0;
                } else {
                    $marksObtained = 0;
                }

                $totalObtained += $marksObtained;

                $answer->update([
                    'is_correct' => $isCorrect,
                    'marks_obtained' => $marksObtained,
                    'max_marks' => $question->marks,
                    'is_graded' => true,
                    'auto_grade_data' => [
                        'auto_graded' => true,
                        'graded_at' => now()->toIso8601String(),
                    ],
                ]);
            }

            $negativeMarking = $attempt->exam->negative_marking ?? 0;
            if ($negativeMarking > 0) {
                $wrongCount = $answers->where('is_correct', false)->count();
                $totalObtained = max(0, $totalObtained - ($wrongCount * $negativeMarking));
            }

            $percentage = $totalPossible > 0 ? round(($totalObtained / $totalPossible) * 100, 2) : 0;
            $passingPercentage = $totalPossible > 0
                ? round(($attempt->exam->passing_marks / $totalPossible) * 100, 2)
                : 0;

            $grade = $this->calculateGrade($percentage);
            $resultStatus = $percentage >= $passingPercentage ? 'pass' : 'fail';

            ExamResult::updateOrCreate(
                ['exam_attempt_id' => $attempt->id],
                [
                    'exam_id' => $attempt->exam_id,
                    'student_id' => $attempt->student_id,
                    'total_marks' => $totalPossible,
                    'obtained_marks' => $totalObtained,
                    'percentage' => $percentage,
                    'grade' => $grade,
                    'result_status' => $resultStatus,
                ]
            );

            $timeTaken = $attempt->started_at
                ? round(now()->diffInMinutes($attempt->started_at))
                : 0;

            $attempt->update([
                'status' => 'submitted',
                'submitted_at' => now(),
                'total_marks' => $totalPossible,
                'obtained_marks' => $totalObtained,
                'time_taken_minutes' => $timeTaken,
                'is_graded' => true,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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
