<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

try {
    echo "🔧 Starting credential creation...\n";
    
    // Check if roles table exists
    if (!DB::getSchemaBuilder()->hasTable('roles')) {
        echo "❌ Roles table does not exist. Creating basic roles table...\n";
        
        DB::statement('CREATE TABLE IF NOT EXISTS roles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL UNIQUE,
            display_name VARCHAR(255),
            description TEXT,
            is_system BOOLEAN DEFAULT 0,
            created_at TIMESTAMP,
            updated_at TIMESTAMP
        )');
    }
    
    // Check if role_user table exists
    if (!DB::getSchemaBuilder()->hasTable('role_user')) {
        echo "❌ Role_user table does not exist. Creating role_user table...\n";
        
        DB::statement('CREATE TABLE IF NOT EXISTS role_user (
            role_id INTEGER,
            user_id INTEGER,
            created_at TIMESTAMP,
            updated_at TIMESTAMP,
            PRIMARY KEY (role_id, user_id)
        )');
    }

    // Create admin role if it doesn't exist
    $adminRole = Role::firstOrCreate(
        ['name' => 'admin'],
        [
            'display_name' => 'System Administrator',
            'description' => 'Full system administrator with all permissions',
            'is_system' => true,
        ]
    );

    echo "✅ Admin role created/found: {$adminRole->name}\n";

    // Create admin user
    $adminUser = User::firstOrCreate(
        ['email' => 'admin@dunco.com'],
        [
            'name' => 'System Administrator',
            'password' => Hash::make('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]
    );

    echo "✅ Admin user created/found: {$adminUser->email}\n";

    // Assign admin role to admin user using direct DB insert
    $exists = DB::table('role_user')
        ->where('role_id', $adminRole->id)
        ->where('user_id', $adminUser->id)
        ->exists();
    
    if (!$exists) {
        DB::table('role_user')->insert([
            'role_id' => $adminRole->id,
            'user_id' => $adminUser->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✅ Admin role assigned to admin user\n";
    }

    echo "\n📋 Admin Credentials:\n";
    echo "📧 Email: admin@dunco.com\n";
    echo "🔑 Password: password\n";
    echo "👤 Role: System Administrator\n\n";

    // Create additional test users
    $testUsers = [
        ['name' => 'Teacher User', 'email' => 'teacher@dunco.com', 'role_name' => 'teacher'],
        ['name' => 'Student User', 'email' => 'student@dunco.com', 'role_name' => 'student'],
        ['name' => 'Parent User', 'email' => 'parent@dunco.com', 'role_name' => 'parent'],
    ];

    foreach ($testUsers as $userData) {
        echo "🔧 Creating {$userData['name']}...\n";
        
        // Create role if it doesn't exist
        $role = Role::firstOrCreate(
            ['name' => $userData['role_name']],
            [
                'display_name' => ucfirst($userData['role_name']),
                'description' => ucfirst($userData['role_name']) . ' role',
                'is_system' => true,
            ]
        );

        // Create user
        $user = User::firstOrCreate(
            ['email' => $userData['email']],
            [
                'name' => $userData['name'],
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assign role using direct DB insert
        $exists = DB::table('role_user')
            ->where('role_id', $role->id)
            ->where('user_id', $user->id)
            ->exists();
        
        if (!$exists) {
            DB::table('role_user')->insert([
                'role_id' => $role->id,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✅ {$userData['name']} created: {$userData['email']} / password\n";
    }

    echo "\n🎉 All initial credentials have been created successfully!\n";
    echo "You can now login to the system using any of these credentials.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "📍 File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "🔍 Stack trace:\n" . $e->getTraceAsString() . "\n";
}
