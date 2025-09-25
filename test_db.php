<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    $userCount = DB::table('users')->count();
    $schoolCount = DB::table('schools')->count();
    $roleCount = DB::table('roles')->count();
    
    echo "Database check results:\n";
    echo "Users: $userCount\n";
    echo "Schools: $schoolCount\n";
    echo "Roles: $roleCount\n";
    
    if ($userCount > 0) {
        $adminUser = DB::table('users')->where('email', 'admin@dunco.com')->first();
        if ($adminUser) {
            echo "Admin user found: {$adminUser->name} ({$adminUser->email})\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error checking database: " . $e->getMessage() . "\n";
}






