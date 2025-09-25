<?php

return [
    'name' => 'ChatBot',
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
        'max_tokens' => env('OPENAI_MAX_TOKENS', 1000),
        'temperature' => env('OPENAI_TEMPERATURE', 0.7),
        'organization' => env('OPENAI_ORGANIZATION'),
    ],
    'chatbot' => [
        'enabled' => env('CHATBOT_ENABLED', true),
        'welcome_message' => 'Hello! I\'m your AI assistant. How can I help you today?',
        'max_conversation_length' => 50,
        'session_timeout' => 30, // minutes
        'rate_limit' => [
            'requests_per_minute' => 60,
            'requests_per_hour' => 1000,
        ],
    ],
    'features' => [
        'voice_input' => false,
        'voice_output' => false,
        'file_upload' => true,
        'image_generation' => false,
        'code_generation' => true,
    ],
    'permissions' => [
        'chatbot.view' => 'View chatbot',
        'chatbot.create' => 'Create conversations',
        'chatbot.edit' => 'Edit conversations',
        'chatbot.delete' => 'Delete conversations',
        'chatbot.admin' => 'Manage chatbot settings',
    ],
];
