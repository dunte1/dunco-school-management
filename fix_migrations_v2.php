<?php

// Improved script to fix migration conflicts
$migrationFiles = [
    'Modules/HR/database/migrations/2024_06_29_100000_create_communication_tables.php',
    'Modules/HR/database/migrations/2024_07_01_000000_create_staff_table.php',
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

echo "Migration Conflict Fixer v2\n";
echo "==========================\n\n";

foreach ($migrationFiles as $file) {
    if (file_exists($file)) {
        echo "Processing: $file\n";
        
        $content = file_get_contents($file);
        $originalContent = $content;
        
        // Check if it's a create table migration
        if (strpos($content, 'Schema::create(') !== false) {
            // Extract table name from Schema::create('table_name', ...)
            preg_match('/Schema::create\([\'"]([^\'"]+)[\'"]/', $content, $matches);
            if (isset($matches[1])) {
                $tableName = $matches[1];
                
                // Replace Schema::create with conditional create
                $content = preg_replace(
                    '/Schema::create\([\'"]([^\'"]+)[\'"],\s*function\s*\(Blueprint\s*\$table\)\s*{/',
                    'if (!Schema::hasTable(\'$1\')) { Schema::create(\'$1\', function (Blueprint $table) {',
                    $content
                );
                
                // Add closing brace after the table creation
                if (strpos($content, 'if (!Schema::hasTable(') !== false) {
                    $content = preg_replace('/(\s*}\);)(\s*})/', '$1$2', $content);
                    if (strpos($content, '}); }') === false) {
                        $content = str_replace('});', '}); }', $content);
                    }
                }
                
                if ($content !== $originalContent) {
                    file_put_contents($file, $content);
                    echo "  ✓ Fixed table: $tableName\n";
                } else {
                    echo "  - Already fixed\n";
                }
            } else {
                echo "  - Could not extract table name\n";
            }
        } else {
            echo "  - No create table found\n";
        }
    } else {
        echo "  - File not found\n";
    }
}

echo "\nMigration conflicts fixed! You can now run:\n";
echo "php artisan migrate --path=Modules/*/database/migrations\n";
