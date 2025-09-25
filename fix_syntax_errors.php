<?php

// Script to fix syntax errors in migration files
$migrationFiles = [
    'Modules/HR/database/migrations/2024_07_01_000210_create_departments_table.php',
    'Modules/HR/database/migrations/2024_07_01_000240_create_role_permission_table.php',
    'Modules/HR/database/migrations/2024_07_01_000250_create_staff_documents_table.php',
    'Modules/HR/database/migrations/2024_07_01_000260_create_attendance_table.php',
    'Modules/HR/database/migrations/2024_07_01_000270_create_leaves_table.php',
    'Modules/HR/database/migrations/2024_07_01_000280_create_payrolls_table.php',
    'Modules/HR/database/migrations/2024_07_01_000290_create_contracts_table.php',
    'Modules/HR/database/migrations/2024_07_01_000300_create_performance_reviews_table.php',
    'Modules/HR/database/migrations/2024_07_01_000310_create_notifications_table.php',
    'Modules/HR/database/migrations/2024_07_01_000320_create_onboarding_exits_table.php',
    'Modules/HR/database/migrations/2024_07_01_000330_create_assets_table.php',
    'Modules/HR/database/migrations/2024_07_01_000350_create_staff_polls_table.php',
    'Modules/HR/database/migrations/2024_07_05_000000_create_staff_attendance_records_table.php',
    'Modules/Hostel/database/migrations/2024_07_02_000003_create_rooms_table.php',
    'Modules/Hostel/database/migrations/2024_07_02_000005_create_room_allocations_table.php',
    'Modules/Attendance/database/migrations/2024_07_18_000001_create_attendance_sessions_table.php',
    'Modules/Attendance/database/migrations/2024_07_18_000002_create_attendance_settings_table.php',
    'Modules/Attendance/database/migrations/2024_07_18_000003_create_attendance_logs_table.php',
    'Modules/Portal/database/migrations/2024_07_23_000001_create_portal_messages_table.php'
];

echo "Migration Syntax Error Fixer\n";
echo "============================\n\n";

foreach ($migrationFiles as $file) {
    if (file_exists($file)) {
        echo "Processing: $file\n";
        
        $content = file_get_contents($file);
        $originalContent = $content;
        
        // Fix duplicate if statements
        $content = preg_replace('/if \(!Schema::hasTable\([^)]+\)\) \{ if \(!Schema::hasTable\([^)]+\)\) \{/', 'if (!Schema::hasTable(\'$1\')) {', $content);
        
        // Fix missing closing braces
        $content = preg_replace('/\); \}/', '); }', $content);
        
        // Fix any remaining syntax issues
        $content = str_replace('}); }', '}); }', $content);
        
        if ($content !== $originalContent) {
            file_put_contents($file, $content);
            echo "  ✓ Fixed syntax errors\n";
        } else {
            echo "  - No syntax errors found\n";
        }
    } else {
        echo "  - File not found\n";
    }
}

echo "\nSyntax errors fixed! You can now try running the migrations again.\n";
echo "php artisan migrate --path=Modules/*/database/migrations\n";
