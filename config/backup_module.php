<?php

return [
    'default_disk' => env('BACKUP_DISK', env('FILESYSTEM_DISK', 'local')),
    'retention_days' => env('BACKUP_RETENTION_DAYS', 30),
    'encryption_key' => env('BACKUP_ENCRYPTION_KEY'),
    'notify_emails' => [
        'info@duncowebsolutions.co.ke',
        'dunthecan02@gmail.com',
    ],
    'allowed_disks' => [
        'local', 'public', 's3', 'ftp', 'sftp'
    ],
];


















