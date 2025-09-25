<?php

return [
    'backup' => [
        'name' => env('APP_NAME', 'laravel'),
        'source' => [
            'files' => [
                'include' => [
                    base_path('app'),
                    base_path('Modules'),
                    base_path('public'),
                    base_path('resources'),
                    base_path('storage'),
                ],
                'exclude' => [
                    base_path('vendor'),
                    base_path('node_modules'),
                ],
                'follow_links' => false,
                'ignore_unreadable_directories' => true,
            ],
            'databases' => [
                env('DB_CONNECTION', 'mysql'),
            ],
        ],
        'destination' => [
            'filename_prefix' => '',
            'disks' => [
                env('BACKUP_DISK', 'local'),
            ],
        ],
    ],
    'monitor_backups' => [
        [
            'name' => env('APP_NAME', 'laravel'),
            'disks' => [env('BACKUP_DISK', 'local')],
            'health_checks' => [
                \Spatie\Backup\Tasks\Monitor\HealthChecks\BackupsAreMade::class,
                \Spatie\Backup\Tasks\Monitor\HealthChecks\BackupsAreNotTooOld::class,
                \Spatie\Backup\Tasks\Monitor\HealthChecks\BackupsAreNotTooLarge::class,
            ],
        ],
    ],
];


