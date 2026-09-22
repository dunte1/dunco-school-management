<?php

return [
    'name' => 'Nursing',

    'clinical_hours' => [
        'required_per_placement' => env('NURSING_REQUIRED_HOURS_PER_PLACEMENT', 200),
        'required_per_semester' => env('NURSING_REQUIRED_HOURS_PER_SEMESTER', 400),
        'max_hours_per_day' => env('NURSING_MAX_HOURS_PER_DAY', 12),
        'min_hours_per_day' => env('NURSING_MIN_HOURS_PER_DAY', 1),
    ],

    'attendance' => [
        'passing_percentage' => env('NURSING_ATTENDANCE_PASSING', 80),
    ],

    'competency' => [
        'require_instructor_approval' => true,
        'allow_self_assessment' => false,
    ],

    'logbook' => [
        'require_evidence' => false,
        'max_hours_per_entry' => 16,
        'allow_backdating_days' => env('NURSING_LOGBOOK_BACKDATING_DAYS', 7),
    ],

    'cpd' => [
        'target_hours_per_year' => env('NURSING_CPD_TARGET_HOURS', 40),
    ],

    'files' => [
        'allowed_types' => ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'],
        'max_upload_size' => env('NURSING_MAX_UPLOAD_SIZE', 10240),
    ],

    'content_review' => [
        'require_review' => true,
        'review_interval_days' => 365,
    ],
];
