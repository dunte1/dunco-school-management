<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ChatBot Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for the ChatBot module
    |
    */

    'chatbot' => [
        'enabled' => env('CHATBOT_ENABLED', true),
        'welcome_message' => env('CHATBOT_WELCOME_MESSAGE', 'Hello! I\'m your AI assistant. How can I help you today?'),
        'fallback_mode' => env('CHATBOT_FALLBACK_MODE', true),
        'rate_limit' => [
            'requests_per_minute' => env('CHATBOT_RATE_LIMIT', 60),
        ],
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY', ''),
        'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
        'max_tokens' => env('OPENAI_MAX_TOKENS', 1000),
        'temperature' => env('OPENAI_TEMPERATURE', 0.7),
    ],

    'features' => [
        'voice_to_text' => env('CHATBOT_VOICE_TO_TEXT', true),
        'multi_language' => env('CHATBOT_MULTI_LANGUAGE', true),
        'analytics' => env('CHATBOT_ANALYTICS', true),
        'premium_features' => env('CHATBOT_PREMIUM_FEATURES', true),
    ],

    'session' => [
        'timeout' => env('CHATBOT_SESSION_TIMEOUT', 3600), // 1 hour
        'max_messages' => env('CHATBOT_MAX_MESSAGES', 100),
    ],

    'ui' => [
        'theme' => env('CHATBOT_THEME', 'light'),
        'animations' => env('CHATBOT_ANIMATIONS', true),
        'sound_enabled' => env('CHATBOT_SOUND_ENABLED', true),
    ],

    'languages' => [
        'en' => 'English',
        'es' => 'Español',
        'fr' => 'Français',
        'de' => 'Deutsch',
        'ar' => 'العربية',
        'zh' => '中文',
    ],

    'suggestions' => [
        'academic' => [
            'Check my grades',
            'View my schedule',
            'Find study materials',
            'Ask about homework',
            'Exam schedule',
            'Library resources'
        ],
        'financial' => [
            'Check fee balance',
            'Payment methods',
            'Scholarship info',
            'Payment history',
            'Fee structure',
            'Payment deadline'
        ],
        'administrative' => [
            'Attendance status',
            'Transport info',
            'Cafeteria menu',
            'School events',
            'Contact teachers',
            'School policies'
        ],
        'general' => [
            'How can I help you?',
            'What would you like to know?',
            'Need assistance?',
            'Ask me anything!'
        ]
    ]
]; 