<?php

namespace Modules\Examination\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Modules\Examination\app\Models\Exam;
use Modules\Examination\app\Models\ExamAttempt;
use Modules\Examination\app\Models\ExamAnswer;
use Modules\Examination\app\Models\ProctoringLog;

class OnlineExamController extends Controller
{
    public function startExam(Exam $exam): View
    {
        // Check if student can take this exam
        if (!$exam->canBeStarted()) {
            abort(403, 'Exam is not available for taking');
        }

        // Check if student already has an active attempt
        $existingAttempt = $exam->attempts()
                               ->where('student_id', Auth::id())
                               ->whereIn('status', ['started', 'in_progress'])
                               ->first();

        if ($existingAttempt) {
            return view('examination::online.exam', compact('exam', 'existingAttempt'));
        }

        // Create new attempt
        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
            'student_id' => Auth::id(),
            'attempt_code' => Str::random(16),
            'started_at' => now(),
            'expires_at' => now()->addMinutes($exam->duration_minutes ?? 120),
            'status' => 'started',
            'total_marks' => $exam->total_marks,
            'device_info' => [
                'browser' => request()->header('User-Agent'),
                'ip' => request()->ip(),
                'timestamp' => now()->toISOString()
            ]
        ]);

        return view('examination::online.exam', compact('exam', 'attempt'));
    }

    public function getExamData(ExamAttempt $attempt): JsonResponse
    {
        if ($attempt->student_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $exam = $attempt->exam;
        $questions = $exam->questions()
                         ->with('category')
                         ->orderBy('pivot_order')
                         ->get();

        // Shuffle questions if enabled
        if ($exam->shuffle_questions) {
            $questions = $questions->shuffle();
        }

        // Shuffle options if enabled
        if ($exam->shuffle_options) {
            $questions->each(function ($question) {
                if ($question->type === 'mcq' && $question->options) {
                    $question->options = collect($question->options)->shuffle()->toArray();
                }
            });
        }

        return response()->json([
            'exam' => [
                'id' => $exam->id,
                'name' => $exam->name,
                'duration_minutes' => $exam->duration_minutes,
                'total_marks' => $exam->total_marks,
                'enable_proctoring' => $exam->enable_proctoring,
                'allow_review' => $exam->allow_review
            ],
            'attempt' => [
                'id' => $attempt->id,
                'attempt_code' => $attempt->attempt_code,
                'started_at' => $attempt->started_at,
                'expires_at' => $attempt->expires_at,
                'status' => $attempt->status
            ],
            'questions' => $questions->map(function ($question) {
                return [
                    'id' => $question->id,
                    'type' => $question->type,
                    'question_text' => $question->question_text,
                    'options' => $question->options,
                    'marks' => $question->pivot->marks ?? $question->marks,
                    'time_limit_seconds' => $question->time_limit_seconds,
                    'metadata' => $question->metadata,
                    'file_upload' => $question->file_upload, // Add this line
                ];
            })
        ]);
    }

    public function startAttempt(ExamAttempt $attempt): JsonResponse
    {
        if ($attempt->student_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($attempt->status !== 'started') {
            return response()->json(['error' => 'Invalid attempt status'], 400);
        }

        $attempt->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Exam started successfully'
        ]);
    }

    public function saveAnswer(Request $request, ExamAttempt $attempt): JsonResponse
    {
        if ($attempt->student_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = \Validator::make($request->all(), [
            'question_id' => 'required|exists:questions,id',
            'answer' => 'nullable',
            'essay_answer' => 'nullable|string',
            'code_answer' => 'nullable|string',
            'time_spent_seconds' => 'nullable|integer',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $question = \Modules\Examination\app\Models\Question::find($request->question_id);
            $answerData = [
                'exam_attempt_id' => $attempt->id,
                'question_id' => $request->question_id,
                'max_marks' => $question->marks,
                'answered_at' => now(),
                'time_spent_seconds' => $request->time_spent_seconds
            ];

            // Handle file upload for essay/file_upload questions
            if ($question->type === 'essay' && $question->file_upload && $request->hasFile('file')) {
                $file = $request->file('file');
                $filename = 'attempt_' . $attempt->id . '_q_' . $question->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('exam_uploads', $filename);
                $answerData['file_path'] = $path;
            }

            // Handle different question types
            if ($question->type === 'essay') {
                $answerData['essay_answer'] = $request->essay_answer;
            } elseif ($question->type === 'coding') {
                $answerData['code_answer'] = $request->code_answer;
            } else {
                $answerData['student_answer'] = $request->answer;
                // Auto-grade if possible
                if (in_array($question->type, ['mcq', 'true_false', 'fill_blank'])) {
                    $isCorrect = $question->isCorrect($request->answer);
                    $answerData['is_correct'] = $isCorrect;
                    $answerData['marks_obtained'] = $isCorrect ? $question->marks : 0;
                    $answerData['is_graded'] = true;
                }
            }

            // Remove old file if re-uploading
            $existing = ExamAnswer::where('exam_attempt_id', $attempt->id)
                ->where('question_id', $request->question_id)
                ->first();
            if ($existing && isset($answerData['file_path']) && $existing->file_path && $existing->file_path !== $answerData['file_path']) {
                \Storage::delete($existing->file_path);
            }

            ExamAnswer::updateOrCreate(
                [
                    'exam_attempt_id' => $attempt->id,
                    'question_id' => $request->question_id
                ],
                $answerData
            );

            return response()->json([
                'success' => true,
                'message' => 'Answer saved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save answer: ' . $e->getMessage()
            ], 500);
        }
    }

    public function submitExam(ExamAttempt $attempt): JsonResponse
    {
        if ($attempt->student_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if (!$attempt->canSubmit()) {
            return response()->json(['error' => 'Cannot submit exam'], 400);
        }

        try {
            DB::beginTransaction();

            // Calculate total marks
            $totalMarks = $attempt->answers()->sum('marks_obtained');
            
            $attempt->update([
                'status' => 'submitted',
                'submitted_at' => now(),
                'obtained_marks' => $totalMarks,
                'time_taken_minutes' => $attempt->started_at->diffInMinutes(now())
            ]);

            // Create result if exam shows results immediately
            if ($attempt->exam->show_results_immediately) {
                $percentage = ($totalMarks / $attempt->total_marks) * 100;
                $grade = $this->calculateGrade($percentage);
                $status = $percentage >= $attempt->exam->passing_marks ? 'pass' : 'fail';

                ExamResult::create([
                    'exam_id' => $attempt->exam_id,
                    'student_id' => $attempt->student_id,
                    'exam_attempt_id' => $attempt->id,
                    'total_marks' => $attempt->total_marks,
                    'obtained_marks' => $totalMarks,
                    'percentage' => $percentage,
                    'grade' => $grade,
                    'result_status' => $status,
                    'is_published' => true,
                    'published_at' => now()
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Exam submitted successfully',
                'total_marks' => $totalMarks,
                'percentage' => round(($totalMarks / $attempt->total_marks) * 100, 2)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit exam: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logProctoringEvent(Request $request, ExamAttempt $attempt): JsonResponse
    {
        if ($attempt->student_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = \Validator::make($request->all(), [
            'event_type' => 'required|string',
            'description' => 'required|string',
            'event_data' => 'nullable|array',
            'severity' => 'required|in:low,medium,high,critical'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            ProctoringLog::create([
                'exam_attempt_id' => $attempt->id,
                'event_type' => $request->event_type,
                'description' => $request->description,
                'event_data' => $request->event_data,
                'severity' => $request->severity
            ]);

            // Update proctoring data in attempt
            $proctoringData = $attempt->proctoring_data ?? [];
            $proctoringData[] = [
                'timestamp' => now()->toISOString(),
                'event_type' => $request->event_type,
                'severity' => $request->severity,
                'description' => $request->description
            ];

            $attempt->update(['proctoring_data' => $proctoringData]);

            return response()->json([
                'success' => true,
                'message' => 'Proctoring event logged'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to log proctoring event: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProctoringStatus(ExamAttempt $attempt): JsonResponse
    {
        if ($attempt->student_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $suspiciousEvents = $attempt->proctoringLogs()
                                   ->whereIn('severity', ['high', 'critical'])
                                   ->where('is_resolved', false)
                                   ->count();

        return response()->json([
            'suspicious_events' => $suspiciousEvents,
            'is_monitored' => $attempt->exam->enable_proctoring,
            'last_activity' => $attempt->updated_at
        ]);
    }

    public function examResult(ExamAttempt $attempt): View
    {
        if ($attempt->student_id !== Auth::id()) {
            abort(403);
        }

        $result = $attempt->result;
        $answers = $attempt->answers()->with('question')->get();

        return view('examination::online.result', compact('attempt', 'result', 'answers'));
    }

    public function downloadAnswerFile($answerId)
    {
        $answer = \Modules\Examination\app\Models\ExamAnswer::with('attempt')->findOrFail($answerId);
        $user = \Auth::user();
        // Only allow the student who owns the answer or an admin/teacher
        if ($user->id !== $answer->attempt->student_id && !$user->hasRole(['admin', 'teacher'])) {
            abort(403);
        }
        if (!$answer->file_path || !\Storage::exists($answer->file_path)) {
            abort(404, 'File not found');
        }
        $filename = 'exam_answer_' . $answer->id . '_' . basename($answer->file_path);
        return \Storage::download($answer->file_path, $filename);
    }

    private function calculateGrade($percentage): string
    {
        return match(true) {
            $percentage >= 90 => 'A+',
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B+',
            $percentage >= 60 => 'B',
            $percentage >= 50 => 'C+',
            $percentage >= 40 => 'C',
            $percentage >= 30 => 'D',
            default => 'F'
        };
    }
} 