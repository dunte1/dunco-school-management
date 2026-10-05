<?php

namespace Modules\ChatBot\Services;

class TranslationService
{
    /**
     * Get translated text
     */
    public function get($key, $default = null)
    {
        $translations = [
            'error_processing_request' => 'Sorry, I encountered an error processing your request. Please try again.',
            'welcome_message' => 'Hello! I\'m your AI assistant for Dunco School Management System. How can I help you today?',
            'rate_limit_exceeded' => 'You have exceeded the rate limit. Please wait a moment before trying again.',
            'invalid_input' => 'Please provide a valid message.',
            'service_unavailable' => 'The AI service is temporarily unavailable. Please try again later.',
        ];

        return $translations[$key] ?? $default ?? 'An error occurred.';
    }
}