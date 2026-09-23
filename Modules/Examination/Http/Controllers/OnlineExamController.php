<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
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

        if (!$answer->file_path || !Storage::disk('local')->exists($answer->file_path)) {
            abort(404, 'Answer file not found.');
        }

        return Storage::disk('local')->download($answer->file_path);
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
            Storage::disk('local')->put($filename, $decoded);

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

    // ───────────────────────────────────────────────────────────
    //  NEW API METHODS
    // ───────────────────────────────────────────────────────────

    /**
     * Receive a base64-encoded video frame, log to ProctoringLog if suspicious.
     */
    public function videoFrame(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|exists:exam_attempts,id',
            'frame_data' => 'required|string',
        ]);

        $attempt = ExamAttempt::where('id', $request->attempt_id)
            ->where('student_id', auth()->id())
            ->first();

        if (!$attempt) {
            return response()->json(['success' => false, 'message' => 'Attempt not found.'], 404);
        }

        $frameData = $request->input('frame_data');
        $decoded = base64_decode(explode(',', $frameData)[1] ?? $frameData);
        $filename = 'video-frames/' . $attempt->id . '/' . time() . '.jpg';
        Storage::disk('local')->put($filename, $decoded);

        $suspicious = false;
        $severity = 'low';
        $description = 'Video frame captured';

        if ($request->has('faces_detected') && $request->faces_detected > 1) {
            $suspicious = true;
            $severity = 'high';
            $description = "Multiple faces detected ({$request->faces_detected})";
        }

        $log = ProctoringLog::create([
            'exam_attempt_id' => $attempt->id,
            'event_type' => 'video_frame',
            'description' => $description,
            'severity' => $severity,
            'event_data' => [
                'path' => $filename,
                'faces_detected' => $request->input('faces_detected', 1),
                'timestamp' => now()->toIso8601String(),
            ],
        ]);

        return response()->json([
            'success' => true,
            'suspicious' => $suspicious,
            'log_id' => $log->id,
        ]);
    }

    /**
     * Receive audio sample data, log voice detection events.
     */
    public function audioSample(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|exists:exam_attempts,id',
            'audio_data' => 'required|string',
        ]);

        $attempt = ExamAttempt::where('id', $request->attempt_id)
            ->where('student_id', auth()->id())
            ->first();

        if (!$attempt) {
            return response()->json(['success' => false, 'message' => 'Attempt not found.'], 404);
        }

        $audioData = $request->input('audio_data');
        $decoded = base64_decode(explode(',', $audioData)[1] ?? $audioData);
        $filename = 'audio-samples/' . $attempt->id . '/' . time() . '.webm';
        Storage::disk('local')->put($filename, $decoded);

        $voiceDetected = $request->input('voice_detected', false);
        $severity = $voiceDetected ? 'medium' : 'low';
        $description = $voiceDetected ? 'Voice detected during exam' : 'Audio sample captured';

        $log = ProctoringLog::create([
            'exam_attempt_id' => $attempt->id,
            'event_type' => 'audio_sample',
            'description' => $description,
            'severity' => $severity,
            'event_data' => [
                'path' => $filename,
                'voice_detected' => $voiceDetected,
                'duration_seconds' => $request->input('duration_seconds'),
                'timestamp' => now()->toIso8601String(),
            ],
        ]);

        return response()->json([
            'success' => true,
            'voice_detected' => $voiceDetected,
            'log_id' => $log->id,
        ]);
    }

    /**
     * Return exam progress: answered count, total, percentage, time remaining.
     */
    public function examProgress($attemptId)
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
        $percentage = $totalQuestions > 0 ? round(($answeredCount / $totalQuestions) * 100, 2) : 0;

        return response()->json([
            'answered_count' => $answeredCount,
            'total_questions' => $totalQuestions,
            'percentage_complete' => $percentage,
            'time_remaining_seconds' => $attempt->expires_at
                ? max(0, $attempt->expires_at->diffInSeconds(now()))
                : null,
            'status' => $attempt->status,
        ]);
    }

    /**
     * Store a push notification subscription.
     */
    public function subscribeNotifications(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        $userId = auth()->id();
        $subscriptions = Cache::get("notification_subscriptions:{$userId}", []);
        $endpoint = $request->input('endpoint');

        // Avoid duplicates
        $subscriptions = collect($subscriptions)->reject(function ($sub) use ($endpoint) {
            return $sub['endpoint'] === $endpoint;
        })->values()->toArray();

        $subscriptions[] = [
            'endpoint' => $endpoint,
            'keys' => $request->input('keys'),
            'created_at' => now()->toIso8601String(),
        ];

        Cache::put("notification_subscriptions:{$userId}", $subscriptions, now()->addDays(90));

        return response()->json([
            'success' => true,
            'message' => 'Notification subscription stored.',
        ]);
    }

    /**
     * Remove a push notification subscription.
     */
    public function unsubscribeNotifications(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
        ]);

        $userId = auth()->id();
        $subscriptions = Cache::get("notification_subscriptions:{$userId}", []);

        $subscriptions = collect($subscriptions)->reject(function ($sub) use ($request) {
            return $sub['endpoint'] === $request->input('endpoint');
        })->values()->toArray();

        Cache::put("notification_subscriptions:{$userId}", $subscriptions, now()->addDays(90));

        return response()->json([
            'success' => true,
            'message' => 'Notification subscription removed.',
        ]);
    }

    /**
     * Handle file upload for exam answers.
     */
    public function uploadAttachment(Request $request)
    {
        $request->validate([
            'attempt_id' => 'required|exists:exam_attempts,id',
            'question_id' => 'required|exists:questions,id',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,zip,txt',
        ]);

        $attempt = ExamAttempt::where('id', $request->attempt_id)
            ->where('student_id', auth()->id())
            ->where('status', 'in_progress')
            ->first();

        if (!$attempt) {
            return response()->json(['success' => false, 'message' => 'No active attempt found.'], 404);
        }

        $file = $request->file('file');
        $filename = 'exam-attachments/' . $attempt->id . '/' . $request->question_id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('exam-attachments/' . $attempt->id, $request->question_id . '_' . time() . '.' . $file->getClientOriginalExtension(), 'local');

        ExamAnswer::updateOrCreate(
            [
                'exam_attempt_id' => $attempt->id,
                'question_id' => $request->question_id,
            ],
            [
                'file_path' => $path,
                'answered_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
        ]);
    }

    /**
     * Return live analytics for an exam (teacher/admin only).
     */
    public function liveAnalytics($examId)
    {
        $exam = Exam::findOrFail($examId);

        $activeAttempts = ExamAttempt::where('exam_id', $examId)
            ->where('status', 'in_progress')
            ->count();

        $allAttempts = ExamAttempt::where('exam_id', $examId)
            ->whereIn('status', ['in_progress', 'submitted', 'completed'])
            ->get();

        $avgProgress = 0;
        if ($allAttempts->count() > 0) {
            $totalProgress = $allAttempts->sum(function ($attempt) {
                $total = $attempt->exam->questions()->count();
                if ($total === 0) return 0;
                $answered = $attempt->answers()->count();
                return round(($answered / $total) * 100, 2);
            });
            $avgProgress = round($totalProgress / $allAttempts->count(), 2);
        }

        $avgScore = $allAttempts
            ->where('status', '!=', 'in_progress')
            ->where('obtained_marks', '>', 0)
            ->avg('obtained_marks') ?? 0;

        $proctoringEventsCount = ProctoringLog::whereHas('attempt', function ($q) use ($examId) {
            $q->where('exam_id', $examId);
        })->count();

        return response()->json([
            'exam_id' => $exam->id,
            'exam_name' => $exam->name,
            'active_attempts' => $activeAttempts,
            'average_progress' => $avgProgress,
            'average_score' => round($avgScore, 2),
            'proctoring_events_count' => $proctoringEventsCount,
            'total_attempts' => $allAttempts->count(),
        ]);
    }

    /**
     * Return exam summary stats (teacher/admin only).
     */
    public function examSummary($examId)
    {
        $exam = Exam::findOrFail($examId);

        $attempts = ExamAttempt::where('exam_id', $examId)
            ->whereIn('status', ['submitted', 'completed'])
            ->get();

        $totalAttempts = $attempts->count();
        $completed = $attempts->where('status', 'completed')->count();
        $avgScore = $attempts->avg('obtained_marks') ?? 0;
        $passCount = $attempts->filter(function ($a) use ($exam) {
            return $a->obtained_marks >= $exam->passing_marks;
        })->count();
        $passRate = $totalAttempts > 0 ? round(($passCount / $totalAttempts) * 100, 2) : 0;

        $timeDistribution = [
            'under_15_min' => $attempts->where('time_taken_minutes', '<=', 15)->count(),
            '15_30_min' => $attempts->filter(fn ($a) => $a->time_taken_minutes > 15 && $a->time_taken_minutes <= 30)->count(),
            '30_60_min' => $attempts->filter(fn ($a) => $a->time_taken_minutes > 30 && $a->time_taken_minutes <= 60)->count(),
            'over_60_min' => $attempts->where('time_taken_minutes', '>', 60)->count(),
        ];

        return response()->json([
            'exam_id' => $exam->id,
            'exam_name' => $exam->name,
            'total_attempts' => $totalAttempts,
            'completed' => $completed,
            'average_score' => round($avgScore, 2),
            'pass_rate' => $passRate,
            'time_distribution' => $timeDistribution,
        ]);
    }

    /**
     * Return unresolved proctoring alerts grouped by severity.
     */
    public function proctoringAlerts($examId)
    {
        $alerts = ProctoringLog::whereHas('attempt', function ($q) use ($examId) {
            $q->where('exam_id', $examId);
        })
            ->where('is_resolved', false)
            ->get()
            ->groupBy('severity');

        return response()->json([
            'exam_id' => $examId,
            'alerts' => $alerts->map(function ($group) {
                return $group->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'event_type' => $log->event_type,
                        'description' => $log->description,
                        'event_data' => $log->event_data,
                        'created_at' => $log->created_at,
                    ];
                });
            }),
            'total_unresolved' => $alerts->flatten()->count(),
        ]);
    }

    /**
     * Return published exams available to the authenticated student.
     */
    public function studentExams()
    {
        $exams = Exam::where('is_active', true)
            ->where('status', 'published')
            ->orderByDesc('start_date')
            ->get();

        return response()->json(['data' => $exams]);
    }

    /**
     * Return the student's past exam attempts with results.
     */
    public function examHistory()
    {
        $attempts = ExamAttempt::with(['exam', 'result'])
            ->where('student_id', auth()->id())
            ->whereIn('status', ['submitted', 'completed'])
            ->orderByDesc('submitted_at')
            ->get();

        return response()->json(['data' => $attempts]);
    }

    /**
     * Return published results for the authenticated student.
     */
    public function studentResults()
    {
        $results = ExamResult::with('exam')
            ->where('student_id', auth()->id())
            ->where('is_published', true)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['data' => $results]);
    }

    /**
     * Return ungraded answers for the teacher's exams.
     */
    public function gradeExams()
    {
        $answers = ExamAnswer::with(['attempt.exam', 'question'])
            ->where('is_graded', false)
            ->whereHas('attempt.exam', function ($q) {
                $q->where('is_active', true);
            })
            ->orderByDesc('answered_at')
            ->get();

        return response()->json(['data' => $answers]);
    }

    /**
     * Grade a single answer via API and recalculate the attempt result.
     */
    public function gradeAnswer(Request $request, $answerId)
    {
        $request->validate([
            'marks_obtained' => 'required|numeric|min:0',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $answer = ExamAnswer::with(['attempt', 'question'])->findOrFail($answerId);

        $teacher = auth()->user();
        if (!$answer->attempt->exam || !$teacher->hasAnyRole(['admin', 'teacher'])) {
            abort(403);
        }

        $maxMarks = $answer->question->marks ?? $answer->max_marks ?? 0;
        if ($request->marks_obtained > $maxMarks) {
            return response()->json(['success' => false, 'message' => 'Marks cannot exceed maximum.'], 422);
        }

        $answer->update([
            'marks_obtained' => $request->marks_obtained,
            'max_marks' => $maxMarks,
            'feedback' => $request->input('feedback'),
            'is_graded' => true,
            'is_correct' => $request->marks_obtained > 0,
        ]);

        // Recalculate attempt totals
        $attempt = $answer->attempt;
        $allAnswers = $attempt->answers()->where('is_graded', true)->get();
        $totalObtained = $allAnswers->sum('marks_obtained');
        $totalPossible = $allAnswers->sum('max_marks');

        $attempt->update([
            'obtained_marks' => $totalObtained,
            'total_marks' => $totalPossible,
            'is_graded' => true,
        ]);

        // Update or create result
        $percentage = $totalPossible > 0 ? round(($totalObtained / $totalPossible) * 100, 2) : 0;
        $passingPercentage = $totalPossible > 0
            ? round(($attempt->exam->passing_marks / $totalPossible) * 100, 2)
            : 0;

        ExamResult::updateOrCreate(
            ['exam_attempt_id' => $attempt->id],
            [
                'exam_id' => $attempt->exam_id,
                'student_id' => $attempt->student_id,
                'total_marks' => $totalPossible,
                'obtained_marks' => $totalObtained,
                'percentage' => $percentage,
                'grade' => $this->calculateGrade($percentage),
                'result_status' => $percentage >= $passingPercentage ? 'pass' : 'fail',
            ]
        );

        return response()->json([
            'success' => true,
            'answer_id' => $answer->id,
            'marks_obtained' => $answer->marks_obtained,
            'attempt_total' => $totalObtained,
        ]);
    }

    /**
     * Return analytics for the teacher's exams.
     */
    public function examAnalytics()
    {
        $teacherId = auth()->id();

        $exams = Exam::where('is_active', true)->get();
        $examCount = $exams->count();

        $totalAttempts = ExamAttempt::whereIn('exam_id', $exams->pluck('id'))
            ->whereIn('status', ['submitted', 'completed'])
            ->get();

        $passCount = $totalAttempts->filter(function ($a) use ($exams) {
            $exam = $exams->firstWhere('id', $a->exam_id);
            return $exam && $a->obtained_marks >= $exam->passing_marks;
        })->count();

        $passRate = $totalAttempts->count() > 0
            ? round(($passCount / $totalAttempts->count()) * 100, 2)
            : 0;

        $avgScore = $totalAttempts->avg('obtained_marks') ?? 0;

        return response()->json([
            'total_exams' => $examCount,
            'total_attempts' => $totalAttempts->count(),
            'pass_rate' => $passRate,
            'average_score' => round($avgScore, 2),
        ]);
    }

    /**
     * Return public exam info without requiring authentication.
     */
    public function publicExamInfo($examId)
    {
        $exam = Exam::findOrFail($examId);

        return response()->json([
            'id' => $exam->id,
            'name' => $exam->name,
            'description' => $exam->description,
            'start_date' => $exam->start_date,
            'end_date' => $exam->end_date,
            'duration_minutes' => $exam->duration_minutes,
            'total_marks' => $exam->total_marks,
            'is_online' => $exam->is_online,
            'status' => $exam->status,
        ]);
    }

    /**
     * Accept and store a support message.
     */
    public function contactSupport(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
            'exam_id' => 'nullable|exists:exams,id',
        ]);

        $supportMessage = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'message' => $request->input('message'),
            'exam_id' => $request->input('exam_id'),
            'created_at' => now()->toIso8601String(),
        ];

        $filename = 'support/' . now()->format('Y-m-d_H-i-s') . '_' . uniqid() . '.json';
        Storage::disk('local')->put($filename, json_encode($supportMessage, JSON_PRETTY_PRINT));

        return response()->json([
            'success' => true,
            'message' => 'Support message received. We will get back to you shortly.',
        ]);
    }

    // ───────────────────────────────────────────────────────────
    //  PROTECTED HELPERS
    // ───────────────────────────────────────────────────────────

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
