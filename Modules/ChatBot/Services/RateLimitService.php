<?php

namespace Modules\ChatBot\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RateLimitService
{
    private $requestsPerMinute;
    private $requestsPerHour;

    public function __construct()
    {
        $this->requestsPerMinute = env('CHATBOT_RATE_LIMIT_PER_MINUTE', 60);
        $this->requestsPerHour = env('CHATBOT_RATE_LIMIT_PER_HOUR', 1000);
    }

    /**
     * Check if user has exceeded rate limit
     */
    public function checkRateLimit($userId)
    {
        try {
            $minuteKey = "chatbot_rate_limit_minute_{$userId}";
            $hourKey = "chatbot_rate_limit_hour_{$userId}";

            $minuteCount = Cache::get($minuteKey, 0);
            $hourCount = Cache::get($hourKey, 0);

            if ($minuteCount >= $this->requestsPerMinute) {
                return [
                    'allowed' => false,
                    'message' => 'Rate limit exceeded. Please wait a minute before trying again.',
                    'retry_after' => 60
                ];
            }

            if ($hourCount >= $this->requestsPerHour) {
                return [
                    'allowed' => false,
                    'message' => 'Hourly rate limit exceeded. Please try again later.',
                    'retry_after' => 3600
                ];
            }

            return [
                'allowed' => true,
                'message' => 'Rate limit OK'
            ];
        } catch (\Exception $e) {
            Log::error('Rate limit check error: ' . $e->getMessage());
            return [
                'allowed' => true,
                'message' => 'Rate limit check failed, allowing request'
            ];
        }
    }

    /**
     * Increment rate limit counter
     */
    public function incrementRateLimit($userId)
    {
        try {
            $minuteKey = "chatbot_rate_limit_minute_{$userId}";
            $hourKey = "chatbot_rate_limit_hour_{$userId}";

            // Increment minute counter
            $minuteCount = Cache::get($minuteKey, 0);
            Cache::put($minuteKey, $minuteCount + 1, 60); // Expire in 1 minute

            // Increment hour counter
            $hourCount = Cache::get($hourKey, 0);
            Cache::put($hourKey, $hourCount + 1, 3600); // Expire in 1 hour

            return true;
        } catch (\Exception $e) {
            Log::error('Rate limit increment error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Reset rate limit for user
     */
    public function resetRateLimit($userId)
    {
        try {
            $minuteKey = "chatbot_rate_limit_minute_{$userId}";
            $hourKey = "chatbot_rate_limit_hour_{$userId}";

            Cache::forget($minuteKey);
            Cache::forget($hourKey);

            return true;
        } catch (\Exception $e) {
            Log::error('Rate limit reset error: ' . $e->getMessage());
            return false;
        }
    }
}