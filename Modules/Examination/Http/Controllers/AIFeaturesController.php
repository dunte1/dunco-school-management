<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Services\AIQuestionGenerator;
use Modules\Examination\Services\AICheatingDetection;
use Modules\Examination\Models\Question;
use Modules\Examination\Models\QuestionCategory;
use Modules\Examination\Models\ExamAttempt;

class AIFeaturesController extends Controller
{
    private $questionGenerator;
    private $cheatingDetection;

    public function __construct(AIQuestionGenerator $questionGenerator, AICheatingDetection $cheatingDetection)
    {
        $this->questionGenerator = $questionGenerator;
        $this->cheatingDetection = $cheatingDetection;
    }

    public function questionGenerator()
    {
        $categories = QuestionCategory::all();
        return view('examination::ai.question-generator', compact('categories'));
    }

    public function generateQuestions(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
            'count' => 'required|integer|min:1|max:20',
            'difficulty' => 'required|in:easy,medium,hard',
            'question_types' => 'required|array',
            'question_types.*' => 'in:mcq,true_false,essay,short_answer,fill_blank',
            'category_id' => 'required|exists:question_categories,id',
            'subject' => 'nullable|string|max:255',
            'grade_level' => 'nullable|string|max:100',
            'language' => 'nullable|string|max:50'
        ]);

        $options = [
            'count' => $request->count,
            'difficulty' => $request->difficulty,
            'question_types' => $request->question_types,
            'subject' => $request->subject ?? 'General',
            'grade_level' => $request->grade_level ?? 'High School',
            'language' => $request->language ?? 'English'
        ];

        $result = $this->questionGenerator->generateQuestions($request->prompt, $options);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'questions' => $result['questions'],
                'count' => $result['count']
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message']
        ], 400);
    }

    public function saveGeneratedQuestions(Request $request)
    {
        $request->validate([
            'questions' => 'required|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.type' => 'required|string',
            'questions.*.difficulty' => 'required|string',
            'questions.*.marks' => 'required|numeric',
            'category_id' => 'required|exists:question_categories,id'
        ]);

        $savedQuestions = [];
        $errors = [];

        foreach ($request->questions as $index => $questionData) {
            try {
                $question = Question::create([
                    'question_text' => $questionData['question_text'],
                    'type' => $questionData['type'],
                    'category_id' => $request->category_id,
                    'options' => $questionData['options'] ?? null,
                    'correct_answers' => $questionData['correct_answers'] ?? null,
                    'explanation' => $questionData['explanation'] ?? '',
                    'marks' => $questionData['marks'],
                    'difficulty' => $questionData['difficulty'],
                    'tags' => $questionData['tags'] ?? [],
                    'is_active' => true
                ]);

                $savedQuestions[] = $question;
            } catch (\Exception $e) {
                $errors[] = "Question " . ($index + 1) . ": " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'saved_count' => count($savedQuestions),
            'errors' => $errors,
            'questions' => $savedQuestions
        ]);
    }

    public function generateFromSyllabus(Request $request)
    {
        $request->validate([
            'syllabus' => 'required|string|max:5000',
            'count' => 'required|integer|min:1|max:20',
            'difficulty' => 'required|in:easy,medium,hard',
            'question_types' => 'required|array',
            'category_id' => 'required|exists:question_categories,id'
        ]);

        $options = [
            'count' => $request->count,
            'difficulty' => $request->difficulty,
            'question_types' => $request->question_types,
            'subject' => $request->subject ?? 'General',
            'grade_level' => $request->grade_level ?? 'High School'
        ];

        $result = $this->questionGenerator->generateFromSyllabus($request->syllabus, $options);

        return response()->json($result);
    }

    public function generateFromNotes(Request $request)
    {
        $request->validate([
            'notes' => 'required|string|max:5000',
            'count' => 'required|integer|min:1|max:20',
            'difficulty' => 'required|in:easy,medium,hard',
            'question_types' => 'required|array',
            'category_id' => 'required|exists:question_categories,id'
        ]);

        $options = [
            'count' => $request->count,
            'difficulty' => $request->difficulty,
            'question_types' => $request->question_types,
            'subject' => $request->subject ?? 'General',
            'grade_level' => $request->grade_level ?? 'High School'
        ];

        $result = $this->questionGenerator->generateFromNotes($request->notes, $options);

        return response()->json($result);
    }

    public function enhanceQuestion(Request $request, $questionId)
    {
        $request->validate([
            'enhancement_type' => 'required|in:improve_clarity,add_difficulty,simplify,add_explanation'
        ]);

        $result = $this->questionGenerator->enhanceExistingQuestion($questionId, $request->enhancement_type);

        return response()->json($result);
    }

    public function cheatingAnalysis()
    {
        $stats = $this->cheatingDetection->getCheatingStatistics();
        return view('examination::ai.cheating-analysis', compact('stats'));
    }

    public function analyzeAttempt($attemptId)
    {
        try {
            $analysis = $this->cheatingDetection->analyzeProctoringData($attemptId);
            return response()->json([
                'success' => true,
                'analysis' => $analysis
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Analysis failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateDetailedReport($attemptId)
    {
        try {
            $report = $this->cheatingDetection->generateDetailedReport($attemptId);
            return response()->json([
                'success' => true,
                'report' => $report
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Report generation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function batchAnalyze(Request $request)
    {
        $request->validate([
            'attempt_ids' => 'required|array',
            'attempt_ids.*' => 'exists:exam_attempts,id'
        ]);

        $results = $this->cheatingDetection->batchAnalyzeAttempts($request->attempt_ids);

        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }

    public function getCheatingStats(Request $request)
    {
        $dateRange = null;
        if ($request->has('start_date') && $request->has('end_date')) {
            $dateRange = [$request->start_date, $request->end_date];
        }

        $stats = $this->cheatingDetection->getCheatingStatistics($dateRange);

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    public function adaptiveQuestions(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'topic' => 'required|string|max:255',
            'count' => 'required|integer|min:1|max:10',
            'category_id' => 'required|exists:question_categories,id'
        ]);

        // Get student's performance history
        $performance = $this->getStudentPerformance($request->student_id);

        $options = [
            'count' => $request->count,
            'question_types' => $request->question_types ?? ['mcq', 'short_answer'],
            'category_id' => $request->category_id
        ];

        $result = $this->questionGenerator->generateAdaptiveQuestions($performance, $request->topic, $options);

        return response()->json($result);
    }

    private function getStudentPerformance($studentId)
    {
        $attempts = ExamAttempt::where('student_id', $studentId)
            ->where('status', 'submitted')
            ->with('exam')
            ->get();

        if ($attempts->isEmpty()) {
            return ['average_score' => 50]; // Default for new students
        }

        $totalScore = 0;
        $totalMarks = 0;

        foreach ($attempts as $attempt) {
            $totalScore += $attempt->obtained_marks ?? 0;
            $totalMarks += $attempt->total_marks ?? 1;
        }

        $averageScore = $totalMarks > 0 ? ($totalScore / $totalMarks) * 100 : 50;

        return [
            'average_score' => round($averageScore, 2),
            'total_attempts' => $attempts->count(),
            'recent_performance' => $attempts->take(5)->pluck('obtained_marks')->toArray()
        ];
    }

    public function aiDashboard()
    {
        $stats = $this->cheatingDetection->getCheatingStatistics();
        $recentQuestions = Question::where('created_at', '>=', now()->subDays(7))
            ->whereNotNull('tags')
            ->whereJsonContains('tags', 'ai_generated')
            ->count();

        return view('examination::ai.dashboard', compact('stats', 'recentQuestions'));
    }
}
