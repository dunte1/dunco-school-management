<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    echo "Setting up database...\n";
    
    // Create basic tables
    DB::statement('CREATE TABLE IF NOT EXISTS schools (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        code VARCHAR(50) NOT NULL,
        motto TEXT,
        is_active BOOLEAN DEFAULT 1,
        created_at DATETIME,
        updated_at DATETIME
    )');
    
    DB::statement('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        school_id INTEGER,
        is_active BOOLEAN DEFAULT 1,
        created_at DATETIME,
        updated_at DATETIME
    )');
    
    DB::statement('CREATE TABLE IF NOT EXISTS roles (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) UNIQUE NOT NULL,
        display_name VARCHAR(255),
        description TEXT,
        is_system BOOLEAN DEFAULT 0,
        created_at DATETIME,
        updated_at DATETIME
    )');
    
    DB::statement('CREATE TABLE IF NOT EXISTS permissions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) UNIQUE NOT NULL,
        display_name VARCHAR(255),
        description TEXT,
        module VARCHAR(255),
        created_at DATETIME,
        updated_at DATETIME
    )');
    
    DB::statement('CREATE TABLE IF NOT EXISTS role_user (
        role_id INTEGER,
        user_id INTEGER,
        created_at DATETIME,
        updated_at DATETIME,
        PRIMARY KEY (role_id, user_id)
    )');
    
    DB::statement('CREATE TABLE IF NOT EXISTS permission_role (
        permission_id INTEGER,
        role_id INTEGER,
        created_at DATETIME,
        updated_at DATETIME,
        PRIMARY KEY (permission_id, role_id)
    )');
    
    DB::statement('CREATE TABLE IF NOT EXISTS chatbot_conversations (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER,
        session_id VARCHAR(255) UNIQUE NOT NULL,
        is_active BOOLEAN DEFAULT 1,
        title VARCHAR(255),
        metadata TEXT,
        created_at DATETIME,
        updated_at DATETIME
    )');
    
    DB::statement('CREATE TABLE IF NOT EXISTS chatbot_messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        conversation_id INTEGER,
        content TEXT NOT NULL,
        role VARCHAR(50) DEFAULT "user",
        metadata TEXT,
        created_at DATETIME,
        updated_at DATETIME
    )');
    
    echo "Tables created successfully!\n";
    
    // Insert data
    $schoolId = DB::table('schools')->insertGetId([
        'name' => 'Dunco Academy',
        'code' => 'DA001',
        'motto' => 'Excellence in Education',
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    $adminRoleId = DB::table('roles')->insertGetId([
        'name' => 'admin',
        'display_name' => 'System Administrator',
        'description' => 'Full system administrator with all permissions',
        'is_system' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    $adminUserId = DB::table('users')->insertGetId([
        'name' => 'System Administrator',
        'email' => 'admin@dunco.com',
        'password' => Hash::make('password'),
        'school_id' => $schoolId,
        'is_active' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    DB::table('role_user')->insert([
        'role_id' => $adminRoleId,
        'user_id' => $adminUserId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    echo "Database setup completed successfully!\n";
    echo "==========================================\n";
    echo "ADMIN CREDENTIALS:\n";
    echo "Email: admin@dunco.com\n";
    echo "Password: password\n";
    echo "==========================================\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}






