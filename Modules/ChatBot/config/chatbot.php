<?php

return [
    'chatbot' => [
        'name' => 'Dunco AI Assistant',
        'version' => '1.0.0',
        'description' => 'AI-powered chatbot for school management system',
        
        // OpenAI Configuration
    'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
        'max_tokens' => env('OPENAI_MAX_TOKENS', 1000),
        'temperature' => env('OPENAI_TEMPERATURE', 0.7),
            'timeout' => env('OPENAI_TIMEOUT', 30),
        ],
        
        // Chatbot Settings
        'settings' => [
            'welcome_message' => 'Hello! I\'m your AI assistant for Dunco School Management System. I can help you with academic queries, administrative tasks, and general school information. How can I assist you today?',
            'max_message_length' => 1000,
            'conversation_history_limit' => 10,
            'enable_typing_indicator' => true,
            'enable_sound_notifications' => false,
        ],
        
        // Rate Limiting
        'rate_limit' => [
            'enabled' => true,
            'requests_per_minute' => 60,
            'requests_per_hour' => 1000,
        ],
        
        // Features
        'features' => [
            'mathematical_calculations' => true,
            'academic_queries' => true,
            'administrative_help' => true,
            'system_information' => true,
            'conversation_history' => true,
            'export_conversations' => true,
        ],
        
        // UI Settings
    'ui' => [
            'theme' => 'default',
            'primary_color' => '#667eea',
            'secondary_color' => '#764ba2',
            'enable_animations' => true,
            'show_timestamps' => true,
            'show_user_avatars' => true,
        ],
        
        // System Context
        'system_context' => [
            'school_name' => 'Dunco Academy',
            'modules' => [
                'academic' => 'Academic management, grades, schedules',
                'finance' => 'Fee management, payments, billing',
                'library' => 'Book management, borrowing, reservations',
                'examination' => 'Exam schedules, results, proctoring',
                'attendance' => 'Student attendance tracking',
                'hostel' => 'Student accommodation management',
                'transport' => 'School transportation services',
                'cafeteria' => 'Food services and meal management',
            ],
            'user_roles' => [
                'admin' => 'Full system access',
                'teacher' => 'Academic and student management',
                'student' => 'Personal academic information',
                'parent' => 'Child\'s academic and financial information',
            ],
        ],
        
        // Fallback Responses
        'fallback_responses' => [
            'greeting' => [
                'Hello! How can I help you today?',
                'Hi there! I\'m here to assist you with school-related questions.',
                'Hello! What would you like to know about your school management system?',
                'Hi! I can help with academic, financial, and administrative questions.',
            ],
            'help' => [
                'I can help you with:\n\n📚 **Academic**: Grades, schedules, homework, exams\n💰 **Financial**: Fees, payments, scholarships\n📋 **Administrative**: Attendance, transport, events\n🎓 **General**: School policies, contact info\n\nWhat would you like to know about?',
            ],
            'unknown' => [
                'I\'m here to help with school management questions! You can ask me about:\n\n• Grades and academic performance\n• Class schedules and timetables\n• Fees and payments\n• Attendance records\n• School events and policies\n\nFor more complex questions, please visit the specific modules or contact your teachers/administrators.',
            ],
        ],
    ],
]; 