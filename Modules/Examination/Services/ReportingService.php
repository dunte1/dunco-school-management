<?php

namespace Modules\Examination\Services;

use Illuminate\Support\Facades\DB;
use Modules\Examination\Models\Exam;
use Modules\Examination\Models\ExamAttempt;
use Modules\Examination\Models\QuestionGrading;
use Modules\Examination\Models\Question;
use App\Models\User;
use Carbon\Carbon;

class ReportingService
{
    public function getExamAnalytics($examId, $dateRange = null)
    {
        $exam = Exam::findOrFail($examId);
        
        $query = ExamAttempt::where('exam_id', $examId);
        
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        $attempts = $query->get();
        
        return [
            'exam_info' => [
                'name' => $exam->name,
                'total_marks' => $exam->total_marks,
                'duration' => $exam->duration_minutes,
                'total_questions' => $exam->questions()->count(),
            ],
            'participation' => [
                'total_attempts' => $attempts->count(),
                'completed_attempts' => $attempts->where('status', 'submitted')->count(),
                'in_progress' => $attempts->where('status', 'in_progress')->count(),
                'abandoned' => $attempts->where('status', 'abandoned')->count(),
            ],
            'performance' => [
                'average_score' => $this->calculateAverageScore($attempts),
                'highest_score' => $attempts->max('obtained_marks') ?? 0,
                'lowest_score' => $attempts->min('obtained_marks') ?? 0,
                'pass_rate' => $this->calculatePassRate($attempts, $exam->passing_marks),
                'grade_distribution' => $this->getGradeDistribution($attempts, $exam->total_marks),
            ],
            'timing' => [
                'average_time' => $this->calculateAverageTime($attempts),
                'fastest_completion' => $attempts->min('time_taken_minutes') ?? 0,
                'slowest_completion' => $attempts->max('time_taken_minutes') ?? 0,
            ],
            'question_analysis' => $this->getQuestionAnalysis($examId, $attempts),
        ];
    }

    public function getQuestionAnalysis($examId, $attempts = null)
    {
        if (!$attempts) {
            $attempts = ExamAttempt::where('exam_id', $examId)->get();
        }

        $questions = Question::whereHas('examQuestions', function($query) use ($examId) {
            $query->where('exam_id', $examId);
        })->get();

        $analysis = [];

        foreach ($questions as $question) {
            $questionAttempts = $attempts->filter(function($attempt) use ($question) {
                return $attempt->answers()->where('question_id', $question->id)->exists();
            });

            $correctAnswers = $questionAttempts->filter(function($attempt) use ($question) {
                $answer = $attempt->answers()->where('question_id', $question->id)->first();
                return $answer && $this->isAnswerCorrect($answer, $question);
            });

            $analysis[] = [
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'type' => $question->type,
                'marks' => $question->marks,
                'total_attempts' => $questionAttempts->count(),
                'correct_attempts' => $correctAnswers->count(),
                'accuracy_rate' => $questionAttempts->count() > 0 
                    ? round(($correctAnswers->count() / $questionAttempts->count()) * 100, 2) 
                    : 0,
                'average_time' => $this->calculateQuestionAverageTime($question, $questionAttempts),
                'difficulty_level' => $this->calculateQuestionDifficulty($question, $questionAttempts, $correctAnswers),
            ];
        }

        return $analysis;
    }

    public function getStudentPerformance($studentId, $dateRange = null)
    {
        $query = ExamAttempt::where('student_id', $studentId);
        
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        $attempts = $query->with(['exam', 'answers'])->get();

        return [
            'student_info' => [
                'total_exams_taken' => $attempts->count(),
                'completed_exams' => $attempts->where('status', 'submitted')->count(),
                'average_score' => $this->calculateAverageScore($attempts),
                'best_performance' => $attempts->max('obtained_marks') ?? 0,
                'improvement_trend' => $this->calculateImprovementTrend($attempts),
            ],
            'subject_analysis' => $this->getSubjectAnalysis($attempts),
            'time_analysis' => [
                'average_time_per_exam' => $this->calculateAverageTime($attempts),
                'most_productive_time' => $this->getMostProductiveTime($attempts),
                'consistency_score' => $this->calculateConsistencyScore($attempts),
            ],
            'recent_attempts' => $attempts->take(10)->map(function($attempt) {
                return [
                    'exam_name' => $attempt->exam->name,
                    'score' => $attempt->obtained_marks,
                    'total_marks' => $attempt->total_marks,
                    'percentage' => $attempt->total_marks > 0 
                        ? round(($attempt->obtained_marks / $attempt->total_marks) * 100, 2) 
                        : 0,
                    'status' => $attempt->status,
                    'completed_at' => $attempt->submitted_at,
                ];
            }),
        ];
    }

    public function getProctoringReport($examId, $dateRange = null)
    {
        // Get all proctored exams if no specific exam ID
        $examQuery = Exam::where('enable_proctoring', true);
        if ($examId) {
            $examQuery->where('id', $examId);
        }
        $exams = $examQuery->get();

        // Get attempts for proctored exams
        $attemptQuery = ExamAttempt::whereIn('exam_id', $exams->pluck('id'))
            ->with(['exam', 'student', 'proctoringLogs']);

        if ($dateRange) {
            $attemptQuery->whereBetween('created_at', $dateRange);
        }

        $attempts = $attemptQuery->get();

        // Get all violations
        $violations = $attempts->flatMap(function($attempt) {
            return $attempt->proctoringLogs->map(function($log) use ($attempt) {
                return [
                    'id' => $log->id,
                    'student_name' => $attempt->student->name ?? 'Unknown',
                    'student_id' => $attempt->student->student_id ?? 'N/A',
                    'exam_name' => $attempt->exam->name ?? 'Unknown Exam',
                    'exam_date' => $attempt->created_at->format('Y-m-d'),
                    'type' => $log->event_type ?? 'unknown',
                    'severity' => $log->severity ?? 'low',
                    'timestamp' => $log->created_at->format('Y-m-d H:i:s'),
                    'description' => $log->description ?? 'No description available',
                ];
            });
        });

        // Calculate summary statistics
        $totalAttempts = $attempts->count();
        $totalViolations = $violations->count();
        $flaggedAttempts = $attempts->filter(function($attempt) {
            return $attempt->proctoringLogs->count() > 0;
        })->count();
        $cleanAttempts = $totalAttempts - $flaggedAttempts;

        // Get violation types for chart
        $violationTypes = $violations->groupBy('type')->map(function($group) {
            return $group->count();
        });

        // Get violations over time for chart
        $violationsOverTime = $violations->groupBy(function($violation) {
            return \Carbon\Carbon::parse($violation['timestamp'])->format('Y-m-d');
        })->map(function($group) {
            return $group->count();
        });

        return [
            'exams' => $exams,
            'summary' => [
                'total_attempts' => $totalAttempts,
                'total_violations' => $totalViolations,
                'flagged_attempts' => $flaggedAttempts,
                'clean_attempts' => $cleanAttempts,
            ],
            'violations' => $violations->take(50), // Limit to 50 for display
            'chart_data' => [
                'violation_types' => [
                    'labels' => $violationTypes->keys()->toArray(),
                    'data' => $violationTypes->values()->toArray(),
                ],
                'violations_over_time' => [
                    'labels' => $violationsOverTime->keys()->toArray(),
                    'data' => $violationsOverTime->values()->toArray(),
                ],
            ],
        ];
    }

    public function getSystemAnalytics($dateRange = null)
    {
        $query = Exam::query();
        
        if ($dateRange) {
            $query->whereBetween('created_at', $dateRange);
        }

        $exams = $query->get();
        $attempts = ExamAttempt::whereIn('exam_id', $exams->pluck('id'));
        
        if ($dateRange) {
            $attempts->whereBetween('created_at', $dateRange);
        }
        
        $attempts = $attempts->get();

        return [
            'overview' => [
                'total_exams' => $exams->count(),
                'total_attempts' => $attempts->count(),
                'active_exams' => $exams->where('is_active', true)->count(),
                'online_exams' => $exams->where('is_online', true)->count(),
                'proctored_exams' => $exams->where('enable_proctoring', true)->count(),
            ],
            'usage_trends' => $this->getUsageTrends($attempts),
            'performance_metrics' => [
                'average_completion_rate' => $this->calculateCompletionRate($attempts),
                'average_score' => $this->calculateAverageScore($attempts),
                'system_uptime' => $this->calculateSystemUptime(),
            ],
            'popular_features' => $this->getPopularFeatures($exams),
        ];
    }

    private function calculateAverageScore($attempts)
    {
        $completedAttempts = $attempts->where('status', 'submitted');
        
        if ($completedAttempts->isEmpty()) {
            return 0;
        }

        $totalScore = $completedAttempts->sum('obtained_marks');
        $totalMarks = $completedAttempts->sum('total_marks');

        return $totalMarks > 0 ? round(($totalScore / $totalMarks) * 100, 2) : 0;
    }

    private function calculatePassRate($attempts, $passingMarks)
    {
        $completedAttempts = $attempts->where('status', 'submitted');
        
        if ($completedAttempts->isEmpty()) {
            return 0;
        }

        $passedAttempts = $completedAttempts->filter(function($attempt) use ($passingMarks) {
            return $attempt->obtained_marks >= $passingMarks;
        });

        return round(($passedAttempts->count() / $completedAttempts->count()) * 100, 2);
    }

    private function getGradeDistribution($attempts)
    {
        $completedAttempts = $attempts->where('status', 'submitted');
        
        $distribution = [
            'A+' => 0, 'A' => 0, 'B+' => 0, 'B' => 0, 
            'C+' => 0, 'C' => 0, 'D' => 0, 'F' => 0
        ];

        foreach ($completedAttempts as $attempt) {
            $percentage = $attempt->total_marks > 0 
                ? ($attempt->obtained_marks / $attempt->total_marks) * 100 
                : 0;

            if ($percentage >= 90) $distribution['A+']++;
            elseif ($percentage >= 80) $distribution['A']++;
            elseif ($percentage >= 70) $distribution['B+']++;
            elseif ($percentage >= 60) $distribution['B']++;
            elseif ($percentage >= 50) $distribution['C+']++;
            elseif ($percentage >= 40) $distribution['C']++;
            elseif ($percentage >= 30) $distribution['D']++;
            else $distribution['F']++;
        }

        return $distribution;
    }

    private function calculateAverageTime($attempts)
    {
        $completedAttempts = $attempts->where('status', 'submitted')
            ->whereNotNull('time_taken_minutes');
        
        if ($completedAttempts->isEmpty()) {
            return 0;
        }

        return round($completedAttempts->avg('time_taken_minutes'), 2);
    }

    private function calculateQuestionAverageTime($question, $attempts)
    {
        // This would require more detailed timing data
        // For now, return a placeholder
        return 0;
    }

    private function calculateQuestionDifficulty($question, $attempts, $correctAnswers)
    {
        if ($attempts->isEmpty()) {
            return 'unknown';
        }

        $accuracy = $correctAnswers->count() / $attempts->count();

        if ($accuracy >= 0.8) return 'easy';
        if ($accuracy >= 0.6) return 'medium';
        if ($accuracy >= 0.4) return 'hard';
        return 'very_hard';
    }

    private function isAnswerCorrect($answer, $question)
    {
        // This would need to be implemented based on question type
        // For now, return a placeholder
        return false;
    }

    private function calculateImprovementTrend($attempts)
    {
        $sortedAttempts = $attempts->sortBy('created_at');
        
        if ($sortedAttempts->count() < 2) {
            return 'insufficient_data';
        }

        $firstHalf = $sortedAttempts->take(ceil($sortedAttempts->count() / 2));
        $secondHalf = $sortedAttempts->skip(ceil($sortedAttempts->count() / 2));

        $firstHalfAvg = $this->calculateAverageScore($firstHalf);
        $secondHalfAvg = $this->calculateAverageScore($secondHalf);

        if ($secondHalfAvg > $firstHalfAvg + 5) return 'improving';
        if ($secondHalfAvg < $firstHalfAvg - 5) return 'declining';
        return 'stable';
    }

    private function getSubjectAnalysis($attempts)
    {
        // This would require subject categorization
        // For now, return a placeholder
        return [];
    }

    private function getMostProductiveTime($attempts)
    {
        $hourlyPerformance = [];
        
        foreach ($attempts as $attempt) {
            $hour = $attempt->created_at->hour;
            if (!isset($hourlyPerformance[$hour])) {
                $hourlyPerformance[$hour] = [];
            }
            $hourlyPerformance[$hour][] = $attempt->obtained_marks;
        }

        $bestHour = 0;
        $bestAverage = 0;

        foreach ($hourlyPerformance as $hour => $scores) {
            $average = array_sum($scores) / count($scores);
            if ($average > $bestAverage) {
                $bestAverage = $average;
                $bestHour = $hour;
            }
        }

        return $bestHour . ':00';
    }

    private function calculateConsistencyScore($attempts)
    {
        if ($attempts->count() < 2) {
            return 0;
        }

        $scores = $attempts->pluck('obtained_marks')->toArray();
        $mean = array_sum($scores) / count($scores);
        $variance = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $scores)) / count($scores);
        
        $stdDev = sqrt($variance);
        
        // Convert to 0-100 scale (lower std dev = higher consistency)
        return max(0, 100 - ($stdDev * 10));
    }

    private function getHighRiskAttempts($attempts)
    {
        // This would use the AI cheating detection service
        // For now, return a placeholder
        return 0;
    }

    private function getViolationBreakdown($violations)
    {
        return $violations->groupBy('event_type')
            ->map(function($group) {
                return $group->count();
            });
    }

    private function getRiskDistribution($attempts)
    {
        // This would use the AI cheating detection service
        // For now, return a placeholder
        return [
            'low' => 0,
            'medium' => 0,
            'high' => 0,
            'critical' => 0
        ];
    }

    private function getProctoringTimeAnalysis($attempts)
    {
        // This would analyze timing patterns in proctoring data
        // For now, return a placeholder
        return [];
    }

    private function getTopViolators($attempts)
    {
        // This would identify students with most violations
        // For now, return a placeholder
        return [];
    }

    private function getUsageTrends($attempts)
    {
        $dailyUsage = $attempts->groupBy(function($attempt) {
            return $attempt->created_at->format('Y-m-d');
        })->map(function($group) {
            return $group->count();
        });

        return $dailyUsage;
    }

    private function calculateCompletionRate($attempts)
    {
        if ($attempts->isEmpty()) {
            return 0;
        }

        $completed = $attempts->where('status', 'submitted')->count();
        return round(($completed / $attempts->count()) * 100, 2);
    }

    private function calculateSystemUptime()
    {
        // This would calculate actual system uptime
        // For now, return a placeholder
        return 99.9;
    }

    private function getPopularFeatures($exams)
    {
        return [
            'proctoring_enabled' => $exams->where('enable_proctoring', true)->count(),
            'online_exams' => $exams->where('is_online', true)->count(),
            'shuffle_questions' => $exams->where('shuffle_questions', true)->count(),
            'immediate_results' => $exams->where('show_results_immediately', true)->count(),
        ];
    }
}
