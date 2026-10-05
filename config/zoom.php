<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Zoom Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for Zoom integration
    | including API credentials, webhook settings, and meeting defaults.
    |
    */

    // Environment: 'sandbox' or 'production'
    'environment' => env('ZOOM_ENVIRONMENT', 'production'),

    // Zoom API Credentials
    'api_key' => env('ZOOM_API_KEY'),
    'api_secret' => env('ZOOM_API_SECRET'),
    'account_id' => env('ZOOM_ACCOUNT_ID'),
    'client_id' => env('ZOOM_CLIENT_ID'),
    'client_secret' => env('ZOOM_CLIENT_SECRET'),

    // Base URLs for different environments
    'base_url' => env('ZOOM_BASE_URL', 'https://api.zoom.us/v2'),
    'oauth_url' => env('ZOOM_OAUTH_URL', 'https://zoom.us/oauth'),
    'webhook_url' => env('ZOOM_WEBHOOK_URL', env('APP_URL') . '/api/zoom/webhook'),

    // Meeting Defaults
    'meeting_defaults' => [
        'type' => 2, // Scheduled meeting
        'duration' => 60, // 60 minutes
        'timezone' => env('APP_TIMEZONE', 'UTC'),
        'settings' => [
            'host_video' => true,
            'participant_video' => true,
            'cn_meeting' => false,
            'in_meeting' => false,
            'join_before_host' => false,
            'jbh_time' => 0, // Join before host time (0 = disabled)
            'mute_upon_entry' => false,
            'watermark' => false,
            'use_pmi' => false,
            'approval_type' => 0, // Automatically approve
            'audio' => 'both', // both, telephony, voip
            'auto_recording' => 'none', // none, local, cloud
            'enforce_login' => false,
            'enforce_login_domains' => '',
            'alternative_hosts' => '',
            'close_registration' => false,
            'show_share_button' => true,
            'allow_multiple_devices' => true,
            'registrants_confirmation_email' => true,
            'waiting_room' => false,
            'request_permission_to_unmute_participants' => false,
            'global_dial_in_countries' => ['US'],
            'registrants_email_notification' => true,
        ],
    ],

    // Webhook Configuration
    'webhook' => [
        'enabled' => env('ZOOM_WEBHOOK_ENABLED', true),
        'secret' => env('ZOOM_WEBHOOK_SECRET'),
        'events' => [
            'meeting.started',
            'meeting.ended',
            'meeting.participant.joined',
            'meeting.participant.left',
            'meeting.recording.completed',
        ],
    ],

    // Recording Settings
    'recording' => [
        'enabled' => env('ZOOM_RECORDING_ENABLED', true),
        'auto_recording' => env('ZOOM_AUTO_RECORDING', 'cloud'), // none, local, cloud
        'cloud_recording_download' => env('ZOOM_CLOUD_RECORDING_DOWNLOAD', true),
        'recording_authentication' => env('ZOOM_RECORDING_AUTH', false),
    ],

    // Security Settings
    'security' => [
        'require_password' => env('ZOOM_REQUIRE_PASSWORD', true),
        'password_length' => env('ZOOM_PASSWORD_LENGTH', 8),
        'waiting_room' => env('ZOOM_WAITING_ROOM', false),
        'require_authentication' => env('ZOOM_REQUIRE_AUTH', false),
        'authentication_domains' => env('ZOOM_AUTH_DOMAINS', ''),
    ],

    // Timeout and Retry Settings
    'timeout' => env('ZOOM_TIMEOUT', 30),
    'max_retries' => env('ZOOM_MAX_RETRIES', 3),
    'retry_delay' => env('ZOOM_RETRY_DELAY', 5),

    // Logging
    'logging' => [
        'enabled' => env('ZOOM_LOGGING_ENABLED', true),
        'log_requests' => env('ZOOM_LOG_REQUESTS', true),
        'log_responses' => env('ZOOM_LOG_RESPONSES', true),
        'log_webhooks' => env('ZOOM_LOG_WEBHOOKS', true),
    ],

    // Rate Limiting
    'rate_limit' => [
        'enabled' => env('ZOOM_RATE_LIMIT_ENABLED', true),
        'max_requests_per_minute' => env('ZOOM_RATE_LIMIT_REQUESTS', 100),
        'max_requests_per_day' => env('ZOOM_RATE_LIMIT_DAILY', 10000),
    ],

    // Error Messages
    'error_messages' => [
        'invalid_credentials' => 'Invalid Zoom API credentials',
        'meeting_not_found' => 'Meeting not found',
        'insufficient_permissions' => 'Insufficient permissions to perform this action',
        'rate_limit_exceeded' => 'Rate limit exceeded. Please try again later',
        'network_error' => 'Network error. Please check your connection',
        'webhook_verification_failed' => 'Webhook verification failed',
    ],

    // Testing Settings
    'testing' => [
        'enabled' => env('ZOOM_TESTING_ENABLED', false),
        'test_user_id' => env('ZOOM_TEST_USER_ID'),
        'test_meeting_id' => env('ZOOM_TEST_MEETING_ID'),
    ],
];
