<?php

namespace Modules\ChatBot\Services;

use Modules\ChatBot\Models\ChatBotFeedback;
use Modules\ChatBot\Models\ChatBotConversation;
use Modules\ChatBot\Models\ChatBotMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeedbackService
{
    /**
     * Record user feedback for a message
     */
    public function recordFeedback($messageId, $userId, $rating, $comment = null)
    {
        try {
            $feedback = ChatBotFeedback::create([
                'message_id' => $messageId,
                'user_id' => $userId,
                'rating' => $rating,
                'comment' => $comment,
                'feedback_type' => 'message'
            ]);

            return [
                'success' => true,
                'feedback_id' => $feedback->id,
                'message' => 'Feedback recorded successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Error recording feedback: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to record feedback'
            ];
        }
    }

    /**
     * Record conversation feedback
     */
    public function recordConversationFeedback($conversationId, $userId, $rating, $comment = null)
    {
        try {
            $feedback = ChatBotFeedback::create([
                'conversation_id' => $conversationId,
                'user_id' => $userId,
                'rating' => $rating,
                'comment' => $comment,
                'feedback_type' => 'conversation'
            ]);

            return [
                'success' => true,
                'feedback_id' => $feedback->id,
                'message' => 'Conversation feedback recorded successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Error recording conversation feedback: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to record conversation feedback'
            ];
        }
    }

    /**
     * Get feedback statistics
     */
    public function getFeedbackStatistics($period = '30d')
    {
        try {
            $dateFilter = $this->getDateFilter($period);
            
            $stats = DB::table('chatbot_feedback')
                ->selectRaw('
                    COUNT(*) as total_feedback,
                    AVG(rating) as average_rating,
                    COUNT(CASE WHEN rating >= 4 THEN 1 END) as positive_feedback,
                    COUNT(CASE WHEN rating <= 2 THEN 1 END) as negative_feedback,
                    COUNT(CASE WHEN rating = 3 THEN 1 END) as neutral_feedback
                ')
                ->when($dateFilter, function($query) use ($dateFilter) {
                    return $query->where('created_at', '>=', $dateFilter);
                })
                ->first();

            $ratingDistribution = DB::table('chatbot_feedback')
                ->selectRaw('rating, COUNT(*) as count')
                ->when($dateFilter, function($query) use ($dateFilter) {
                    return $query->where('created_at', '>=', $dateFilter);
                })
                ->groupBy('rating')
                ->orderBy('rating')
                ->get();

            $feedbackByType = DB::table('chatbot_feedback')
                ->selectRaw('feedback_type, COUNT(*) as count, AVG(rating) as avg_rating')
                ->when($dateFilter, function($query) use ($dateFilter) {
                    return $query->where('created_at', '>=', $dateFilter);
                })
                ->groupBy('feedback_type')
                ->get();

            return [
                'success' => true,
                'data' => [
                    'overview' => $stats,
                    'rating_distribution' => $ratingDistribution,
                    'feedback_by_type' => $feedbackByType,
                    'period' => $period
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Error getting feedback statistics: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to get feedback statistics'
            ];
        }
    }

    /**
     * Get user feedback history
     */
    public function getUserFeedbackHistory($userId, $limit = 20)
    {
        try {
            $feedback = ChatBotFeedback::where('user_id', $userId)
                ->with(['message', 'conversation'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            return [
                'success' => true,
                'data' => $feedback
            ];
        } catch (\Exception $e) {
            Log::error('Error getting user feedback history: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to get user feedback history'
            ];
        }
    }

    /**
     * Get recent feedback comments
     */
    public function getRecentComments($limit = 10)
    {
        try {
            $comments = ChatBotFeedback::whereNotNull('comment')
                ->with(['user', 'message', 'conversation'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            return [
                'success' => true,
                'data' => $comments
            ];
        } catch (\Exception $e) {
            Log::error('Error getting recent comments: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to get recent comments'
            ];
        }
    }

    /**
     * Analyze feedback trends
     */
    public function analyzeFeedbackTrends($period = '30d')
    {
        try {
            $dateFilter = $this->getDateFilter($period);
            
            $trends = DB::table('chatbot_feedback')
                ->selectRaw('
                    DATE(created_at) as date,
                    COUNT(*) as feedback_count,
                    AVG(rating) as average_rating,
                    COUNT(CASE WHEN rating >= 4 THEN 1 END) as positive_count,
                    COUNT(CASE WHEN rating <= 2 THEN 1 END) as negative_count
                ')
                ->when($dateFilter, function($query) use ($dateFilter) {
                    return $query->where('created_at', '>=', $dateFilter);
                })
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->get();

            return [
                'success' => true,
                'data' => $trends
            ];
        } catch (\Exception $e) {
            Log::error('Error analyzing feedback trends: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to analyze feedback trends'
            ];
        }
    }

    /**
     * Get feedback insights
     */
    public function getFeedbackInsights()
    {
        try {
            $insights = [];
            
            // Overall satisfaction
            $overallStats = $this->getFeedbackStatistics('30d');
            if ($overallStats['success']) {
                $avgRating = $overallStats['data']['overview']->average_rating;
                $totalFeedback = $overallStats['data']['overview']->total_feedback;
                
                if ($avgRating >= 4) {
                    $insights[] = "High satisfaction: Average rating {$avgRating}/5 from {$totalFeedback} feedback entries";
                } elseif ($avgRating >= 3) {
                    $insights[] = "Moderate satisfaction: Average rating {$avgRating}/5 from {$totalFeedback} feedback entries";
                } else {
                    $insights[] = "Low satisfaction: Average rating {$avgRating}/5 from {$totalFeedback} feedback entries - needs attention";
                }
            }

            // Recent trends
            $trends = $this->analyzeFeedbackTrends('7d');
            if ($trends['success'] && count($trends['data']) > 1) {
                $recent = $trends['data']->last();
                $previous = $trends['data'][count($trends['data']) - 2];
                
                if ($recent->average_rating > $previous->average_rating) {
                    $insights[] = "Improving trend: Rating increased from {$previous->average_rating} to {$recent->average_rating}";
                } elseif ($recent->average_rating < $previous->average_rating) {
                    $insights[] = "Declining trend: Rating decreased from {$previous->average_rating} to {$recent->average_rating}";
                }
            }

            // Common issues
            $negativeFeedback = ChatBotFeedback::where('rating', '<=', 2)
                ->whereNotNull('comment')
                ->where('created_at', '>=', now()->subDays(7))
                ->get();

            if ($negativeFeedback->count() > 0) {
                $insights[] = "Recent concerns: {$negativeFeedback->count()} negative feedback entries in the last 7 days";
            }

            return [
                'success' => true,
                'data' => $insights
            ];
        } catch (\Exception $e) {
            Log::error('Error getting feedback insights: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to get feedback insights'
            ];
        }
    }

    /**
     * Export feedback data
     */
    public function exportFeedback($format = 'csv', $period = '30d')
    {
        try {
            $dateFilter = $this->getDateFilter($period);
            
            $feedback = ChatBotFeedback::with(['user', 'message', 'conversation'])
                ->when($dateFilter, function($query) use ($dateFilter) {
                    return $query->where('created_at', '>=', $dateFilter);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            if ($format === 'csv') {
                return $this->exportToCsv($feedback);
            } elseif ($format === 'json') {
                return $this->exportToJson($feedback);
            }

            return [
                'success' => false,
                'message' => 'Unsupported export format'
            ];
        } catch (\Exception $e) {
            Log::error('Error exporting feedback: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to export feedback data'
            ];
        }
    }

    /**
     * Get date filter for period
     */
    private function getDateFilter($period)
    {
        switch ($period) {
            case '7d':
                return now()->subDays(7);
            case '30d':
                return now()->subDays(30);
            case '90d':
                return now()->subDays(90);
            case '1y':
                return now()->subYear();
            default:
                return now()->subDays(30);
        }
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($feedback)
    {
        $csv = "ID,User ID,Message ID,Conversation ID,Rating,Comment,Type,Created At\n";
        
        foreach ($feedback as $item) {
            $csv .= implode(',', [
                $item->id,
                $item->user_id,
                $item->message_id ?? '',
                $item->conversation_id ?? '',
                $item->rating,
                '"' . str_replace('"', '""', $item->comment ?? '') . '"',
                $item->feedback_type,
                $item->created_at
            ]) . "\n";
        }

        return [
            'success' => true,
            'data' => $csv,
            'format' => 'csv'
        ];
    }

    /**
     * Export to JSON
     */
    private function exportToJson($feedback)
    {
        return [
            'success' => true,
            'data' => $feedback->toJson(JSON_PRETTY_PRINT),
            'format' => 'json'
        ];
    }
}
