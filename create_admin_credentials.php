<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

try {
    // Create admin role if it doesn't exist
    $adminRole = Role::firstOrCreate(
        ['name' => 'admin'],
        [
            'display_name' => 'System Administrator',
            'description' => 'Full system administrator with all permissions',
            'is_system' => true,
        ]
    );

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

    // Assign admin role to admin user
    if (!$adminUser->roles()->where('role_id', $adminRole->id)->exists()) {
        $adminUser->roles()->attach($adminRole->id);
    }

    echo "✅ Admin credentials created successfully!\n";
    echo "📧 Email: admin@dunco.com\n";
    echo "🔑 Password: password\n";
    echo "👤 Role: System Administrator\n\n";

    // Create additional test users
    $testUsers = [
        ['name' => 'Teacher User', 'email' => 'teacher@dunco.com', 'role' => 'teacher'],
        ['name' => 'Student User', 'email' => 'student@dunco.com', 'role' => 'student'],
        ['name' => 'Parent User', 'email' => 'parent@dunco.com', 'role' => 'parent'],
    ];

    foreach ($testUsers as $userData) {
        // Create role if it doesn't exist
        $role = Role::firstOrCreate(
            ['name' => $userData['role']],
            [
                'display_name' => ucfirst($userData['role']),
                'description' => ucfirst($userData['role']) . ' role',
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

        // Assign role
        if (!$user->roles()->where('role_id', $role->id)->exists()) {
            $user->roles()->attach($role->id);
        }

        echo "✅ {$userData['name']} created: {$userData['email']} / password\n";
    }

    echo "\n🎉 All initial credentials have been created successfully!\n";
    echo "You can now login to the system using any of these credentials.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
