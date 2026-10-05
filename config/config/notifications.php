<?php

return [
    'sms' => [
        // Driver: 'africas_talking' | 'twilio' | 'log'
        'driver' => env('NOTIFICATIONS_SMS_DRIVER', env('SMS_DRIVER', 'log')),
        'africas_talking' => [
            'username' => env('AFRICAS_TALKING_USERNAME', env('AFRICASTALKING_USERNAME')),
            'api_key' => env('AFRICAS_TALKING_API_KEY', env('AFRICASTALKING_API_KEY')),
            'from' => env('AFRICAS_TALKING_FROM', env('AFRICASTALKING_SENDER_ID')),
        ],
        'twilio' => [
            'sid' => env('TWILIO_SMS_SID', env('TWILIO_SID')),
            'token' => env('TWILIO_SMS_TOKEN', env('TWILIO_TOKEN')),
            'from' => env('TWILIO_SMS_FROM', env('TWILIO_FROM')),
        ],
    ],

    'whatsapp' => [
        // Driver: 'twilio' | 'log'
        'driver' => env('NOTIFICATIONS_WHATSAPP_DRIVER', 'log'),
        'twilio' => [
            'sid' => env('TWILIO_WHATSAPP_SID', env('TWILIO_SMS_SID')),
            'token' => env('TWILIO_WHATSAPP_TOKEN', env('TWILIO_SMS_TOKEN')),
            'from' => env('TWILIO_WHATSAPP_FROM', 'whatsapp:+14155238886'),
        ],
    ],
];
