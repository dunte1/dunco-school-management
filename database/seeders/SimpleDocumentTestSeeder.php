<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\School;

class SimpleDocumentTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample school
        $school = School::firstOrCreate(
            ['code' => 'DIS001'],
            [
                'name' => 'Dunco International School',
                'logo' => 'schools/dunco-logo.png',
                'theme' => 'blue',
                'motto' => 'Excellence in Education, Character, and Leadership',
                'settings' => [
                    'address' => '123 Education Street, Nairobi, Kenya',
                    'phone' => '+254 700 123 456',
                    'email' => 'info@dunco.edu.ke',
                    'website' => 'www.dunco.edu.ke',
                    'theme_colors' => [
                        'primary' => '#003366',
                        'secondary' => '#666666',
                        'accent' => '#ff6b35'
                    ]
                ]
            ]
        );

        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@dunco.edu.ke'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'school_id' => $school->id,
                'email_verified_at' => now()
            ]
        );

        // Create test student user
        User::firstOrCreate(
            ['email' => 'student@dunco.edu.ke'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password123'),
                'school_id' => $school->id,
                'email_verified_at' => now()
            ]
        );

        // Create test staff user
        User::firstOrCreate(
            ['email' => 'staff@dunco.edu.ke'],
            [
                'name' => 'Dr. Mary Johnson',
                'password' => Hash::make('password123'),
                'school_id' => $school->id,
                'email_verified_at' => now()
            ]
        );

        $this->command->info('Simple document test data created successfully!');
        $this->command->info('School: Dunco International School');
        $this->command->info('Admin Login: admin@dunco.edu.ke / password123');
        $this->command->info('Student Login: student@dunco.edu.ke / password123');
        $this->command->info('Staff Login: staff@dunco.edu.ke / password123');
        $this->command->info('');
        $this->command->info('You can now test the document generation interface at:');
        $this->command->info('/document-generation/');
    }
}
