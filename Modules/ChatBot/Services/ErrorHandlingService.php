<?php

namespace Modules\ChatBot\Services;

use Illuminate\Support\Facades\Log;

class ErrorHandlingService
{
    /**
     * Log an error with context
     */
    public function logError(\Exception $exception, $context = '', $data = [])
    {
        try {
            Log::error("ChatBot Error in {$context}: {$exception->getMessage()}", [
                'exception' => $exception,
                'data' => $data,
                'trace' => $exception->getTraceAsString()
            ]);
            
            return true;
        } catch (\Exception $e) {
            // Fallback logging
            Log::error("Failed to log ChatBot error: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Handle API errors gracefully
     */
    public function handleApiError(\Exception $exception)
    {
        $this->logError($exception, 'API_ERROR');
        
        // Return a user-friendly error message
        return "I'm sorry, I'm having trouble connecting to the AI service right now. Please try again in a moment.";
    }

    /**
     * Handle database errors gracefully
     */
    public function handleDatabaseError(\Exception $exception)
    {
        $this->logError($exception, 'DATABASE_ERROR');
        
        // Return a user-friendly error message
        return "I'm experiencing some technical difficulties. Please try again later.";
    }
}