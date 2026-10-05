<?php

namespace Modules\ChatBot\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Modules\ChatBot\Models\Conversation;
use Modules\ChatBot\Models\Message;

class AnalyticsService
{
    /**
     * Get chatbot usage statistics
     *
     * @return array
     */
    public function getUsageStatistics()
    {
        $cacheKey = 'chatbot_usage_statistics';
        
        return Cache::remember($cacheKey, 300, function () {
            $totalConversations = Conversation::count();
            $totalMessages = Message::count();
            $todayConversations = Conversation::whereDate('created_at', today())->count();
            $todayMessages = Message::whereDate('created_at', today())->count();
            
            // Get message counts by role
            $messageCountsByRole = Message::select('role', DB::raw('count(*) as count'))
                ->groupBy('role')
                ->pluck('count', 'role')
                ->toArray();
            
            // Get conversations by user role (if available)
            // Fixed: Properly join with roles table using primary_role_id
            $conversationsByUserRole = [];
            try {
                $conversationsByUserRole = DB::table('chatbot_conversations as c')
                    ->join('users as u', 'c.user_id', '=', 'u.id')
                    ->join('roles as r', 'u.primary_role_id', '=', 'r.id')
                    ->select('r.name', DB::raw('count(*) as count'))
                    ->groupBy('r.name')
                    ->pluck('count', 'r.name')
                    ->toArray();
            } catch (\Exception $e) {
                // If there's an error, just return empty array
                $conversationsByUserRole = [];
            }
            
            // Get average messages per conversation
            $avgMessagesPerConversation = Conversation::withCount('messages')
                ->get()
                ->avg('messages_count');
            
            return [
                'total_conversations' => $totalConversations,
                'total_messages' => $totalMessages,
                'today_conversations' => $todayConversations,
                'today_messages' => $todayMessages,
                'messages_by_role' => $messageCountsByRole,
                'conversations_by_user_role' => $conversationsByUserRole,
                'avg_messages_per_conversation' => round($avgMessagesPerConversation, 2),
            ];
        });
    }

    /**
     * Get popular intents/statistics
     *
     * @return array
     */
    public function getPopularIntents()
    {
        $cacheKey = 'chatbot_popular_intents';
        
        return Cache::remember($cacheKey, 300, function () {
            // This would require storing intent data in the messages table
            // For now, we'll return sample data
            return [
                'academic_grades' => 1250,
                'academic_schedule' => 980,
                'financial_fees' => 750,
                'administrative_attendance' => 620,
                'general_help' => 450,
                'general_query' => 320,
            ];
        });
    }

    /**
     * Get user engagement metrics
     *
     * @return array
     */
    public function getUserEngagement()
    {
        $cacheKey = 'chatbot_user_engagement';
        
        return Cache::remember($cacheKey, 300, function () {
            // Get active users (users with conversations in the last 30 days)
            $activeUsers = Conversation::where('created_at', '>=', now()->subDays(30))
                ->distinct('user_id')
                ->count('user_id');
            
            // Get returning users (users with more than one conversation)
            $returningUsers = Conversation::select('user_id')
                ->groupBy('user_id')
                ->havingRaw('COUNT(*) > 1')
                ->count();
            
            // Get total users who have used chatbot
            $totalUsers = Conversation::distinct('user_id')->count('user_id');
            
            return [
                'active_users' => $activeUsers,
                'returning_users' => $returningUsers,
                'total_users' => $totalUsers,
                'engagement_rate' => $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0,
            ];
        });
    }

    /**
     * Get response time statistics
     *
     * @return array
     */
    public function getResponseTimeStats()
    {
        $cacheKey = 'chatbot_response_time_stats';
        
        return Cache::remember($cacheKey, 300, function () {
            // This would require storing timestamps for request/response
            // For now, we'll return sample data
            return [
                'avg_response_time' => 1.2, // seconds
                'min_response_time' => 0.5, // seconds
                'max_response_time' => 5.0, // seconds
                'response_time_distribution' => [
                    '0-1s' => 65,
                    '1-2s' => 25,
                    '2-3s' => 7,
                    '3-5s' => 3,
                ],
            ];
        });
    }

    /**
     * Get error statistics
     *
     * @return array
     */
    public function getErrorStatistics()
    {
        $cacheKey = 'chatbot_error_statistics';
        
        return Cache::remember($cacheKey, 300, function () {
            // This would require logging errors
            // For now, we'll return sample data
            return [
                'total_errors' => 42,
                'error_rate' => 2.3, // percentage
                'common_errors' => [
                    'api_connection_failed' => 15,
                    'rate_limit_exceeded' => 12,
                    'invalid_input' => 8,
                    'processing_error' => 7,
                ],
            ];
        });
    }

    /**
     * Get conversation length distribution
     *
     * @return array
     */
    public function getConversationLengthDistribution()
    {
        $cacheKey = 'chatbot_conversation_length_distribution';
        
        return Cache::remember($cacheKey, 300, function () {
            $conversations = Conversation::withCount('messages')->get();
            
            $distribution = [
                '1-5' => 0,
                '6-10' => 0,
                '11-20' => 0,
                '21-50' => 0,
                '50+' => 0,
            ];
            
            foreach ($conversations as $conversation) {
                $count = $conversation->messages_count;
                
                if ($count <= 5) {
                    $distribution['1-5']++;
                } elseif ($count <= 10) {
                    $distribution['6-10']++;
                } elseif ($count <= 20) {
                    $distribution['11-20']++;
                } elseif ($count <= 50) {
                    $distribution['21-50']++;
                } else {
                    $distribution['50+']++;
                }
            }
            
            return $distribution;
        });
    }

    /**
     * Log chatbot interaction for analytics
     *
     * @param array $data
     * @return void
     */
    public function logInteraction($data)
    {
        // Store interaction data for analytics
        // This could be stored in a separate analytics table
        $logData = [
            'user_id' => $data['user_id'] ?? null,
            'conversation_id' => $data['conversation_id'] ?? null,
            'message' => $data['message'] ?? '',
            'response' => $data['response'] ?? '',
            'intent' => $data['intent'] ?? null,
            'response_time' => $data['response_time'] ?? 0,
            'tokens_used' => $data['tokens_used'] ?? 0,
            'success' => $data['success'] ?? true,
            'error_message' => $data['error_message'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        
        // In a real implementation, we would store this in a database
        // For now, we'll just log it
        \Log::info('ChatBot Interaction', $logData);
    }

    /**
     * Get analytics data for a specific date range
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getAnalyticsForDateRange($startDate, $endDate)
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);
        
        $conversations = Conversation::whereBetween('created_at', [$start, $end])->count();
        $messages = Message::whereBetween('created_at', [$start, $end])->count();
        
        return [
            'conversations' => $conversations,
            'messages' => $messages,
            'date_range' => [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
            ],
        ];
    }

    /**
     * Clear analytics cache
     *
     * @return void
     */
    public function clearCache()
    {
        Cache::forget('chatbot_usage_statistics');
        Cache::forget('chatbot_popular_intents');
        Cache::forget('chatbot_user_engagement');
        Cache::forget('chatbot_response_time_stats');
        Cache::forget('chatbot_error_statistics');
        Cache::forget('chatbot_conversation_length_distribution');
    }
}