<?php

return [
    /*
    |--------------------------------------------------------------------------
    | M-Pesa Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for M-Pesa integration
    | including API endpoints, credentials, and callback URLs.
    |
    */

    // Environment: 'sandbox' or 'production'
    'environment' => env('MPESA_ENVIRONMENT', 'sandbox'),

    // Base URLs for different environments
    'base_url' => env('MPESA_BASE_URL', 'https://sandbox.safaricom.co.ke'),

    // M-Pesa API Credentials
    'consumer_key' => env('MPESA_CONSUMER_KEY'),
    'consumer_secret' => env('MPESA_CONSUMER_SECRET'),
    'short_code' => env('MPESA_SHORT_CODE'),
    'passkey' => env('MPESA_PASSKEY'),

    // Callback URLs
    'callback_url' => env('MPESA_CALLBACK_URL', env('APP_URL') . '/api/mpesa/callback'),
    'c2b_callback_url' => env('MPESA_C2B_CALLBACK_URL', env('APP_URL') . '/api/mpesa/c2b-callback'),
    'c2b_validation_url' => env('MPESA_C2B_VALIDATION_URL', env('APP_URL') . '/api/mpesa/c2b-validation'),

    // Transaction limits
    'min_amount' => env('MPESA_MIN_AMOUNT', 1),
    'max_amount' => env('MPESA_MAX_AMOUNT', 70000),

    // Timeout settings (in seconds)
    'timeout' => env('MPESA_TIMEOUT', 30),

    // Retry settings
    'max_retries' => env('MPESA_MAX_RETRIES', 3),
    'retry_delay' => env('MPESA_RETRY_DELAY', 5), // seconds

    // Logging
    'log_requests' => env('MPESA_LOG_REQUESTS', true),
    'log_responses' => env('MPESA_LOG_RESPONSES', true),

    // Security
    'verify_ssl' => env('MPESA_VERIFY_SSL', true),

    // Default settings
    'defaults' => [
        'transaction_type' => 'CustomerPayBillOnline',
        'account_reference' => 'School Fees',
        'transaction_description' => 'School Fee Payment',
    ],

    // Phone number formatting
    'phone_format' => [
        'country_code' => '254',
        'remove_prefix' => true, // Remove leading 0
        'min_length' => 9,
        'max_length' => 12,
    ],

    // Payment status mapping
    'status_mapping' => [
        '0' => 'completed',
        '1' => 'failed',
        '2' => 'cancelled',
        '3' => 'timeout',
    ],

    // Error messages
    'error_messages' => [
        'insufficient_funds' => 'Insufficient funds in your M-Pesa account',
        'invalid_phone' => 'Invalid phone number format',
        'transaction_failed' => 'Transaction failed. Please try again',
        'network_error' => 'Network error. Please check your connection',
        'timeout' => 'Transaction timeout. Please try again',
        'cancelled' => 'Transaction was cancelled',
    ],

    // Webhook security
    'webhook_secret' => env('MPESA_WEBHOOK_SECRET'),
    'verify_webhook_signature' => env('MPESA_VERIFY_WEBHOOK', false),

    // Rate limiting
    'rate_limit' => [
        'enabled' => env('MPESA_RATE_LIMIT_ENABLED', true),
        'max_attempts' => env('MPESA_RATE_LIMIT_ATTEMPTS', 10),
        'decay_minutes' => env('MPESA_RATE_LIMIT_DECAY', 60),
    ],

    // Notification settings
    'notifications' => [
        'email_receipt' => env('MPESA_EMAIL_RECEIPT', true),
        'sms_receipt' => env('MPESA_SMS_RECEIPT', false),
        'push_notification' => env('MPESA_PUSH_NOTIFICATION', true),
    ],

    // Database settings
    'database' => [
        'log_transactions' => env('MPESA_LOG_TRANSACTIONS', true),
        'cleanup_old_logs' => env('MPESA_CLEANUP_LOGS', true),
        'log_retention_days' => env('MPESA_LOG_RETENTION_DAYS', 90),
    ],

    // Testing settings
    'testing' => [
        'mock_responses' => env('MPESA_MOCK_RESPONSES', false),
        'test_phone_numbers' => [
            '254708374149', // Safaricom test number
            '254711111111',
            '254722222222',
        ],
        'test_amounts' => [
            'min' => 1,
            'max' => 1000,
        ],
    ],
];
