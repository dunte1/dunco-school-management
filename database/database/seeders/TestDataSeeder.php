<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\School;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $school = School::first();

        // Create test users with different roles
        $testUsers = [
            // Students
            [
                'name' => 'John Student',
                'email' => 'john.student@dunco.com',
                'password' => 'password',
                'role' => 'student',
                'phone' => '+254700123456',
                'address' => '123 Student Street, Nairobi'
            ],
            [
                'name' => 'Sarah Student',
                'email' => 'sarah.student@dunco.com',
                'password' => 'password',
                'role' => 'student',
                'phone' => '+254700123457',
                'address' => '456 Student Avenue, Nairobi'
            ],
            [
                'name' => 'Mike Student',
                'email' => 'mike.student@dunco.com',
                'password' => 'password',
                'role' => 'student',
                'phone' => '+254700123458',
                'address' => '789 Student Road, Nairobi'
            ],

            // Parents
            [
                'name' => 'Mr. John Parent',
                'email' => 'john.parent@dunco.com',
                'password' => 'password',
                'role' => 'parent',
                'phone' => '+254700123459',
                'address' => '123 Parent Street, Nairobi'
            ],
            [
                'name' => 'Mrs. Sarah Parent',
                'email' => 'sarah.parent@dunco.com',
                'password' => 'password',
                'role' => 'parent',
                'phone' => '+254700123460',
                'address' => '456 Parent Avenue, Nairobi'
            ],

            // Teachers
            [
                'name' => 'Mr. David Teacher',
                'email' => 'david.teacher@dunco.com',
                'password' => 'password',
                'role' => 'teacher',
                'phone' => '+254700123461',
                'address' => '123 Teacher Street, Nairobi'
            ],
            [
                'name' => 'Mrs. Jane Teacher',
                'email' => 'jane.teacher@dunco.com',
                'password' => 'password',
                'role' => 'teacher',
                'phone' => '+254700123462',
                'address' => '456 Teacher Avenue, Nairobi'
            ],

            // Academic Staff
            [
                'name' => 'Mr. Academic Admin',
                'email' => 'academic.admin@dunco.com',
                'password' => 'password',
                'role' => 'academic_admin',
                'phone' => '+254700123463',
                'address' => '123 Academic Street, Nairobi'
            ],

            // Finance Staff
            [
                'name' => 'Mr. Finance Manager',
                'email' => 'finance.manager@dunco.com',
                'password' => 'password',
                'role' => 'finance_manager',
                'phone' => '+254700123464',
                'address' => '123 Finance Street, Nairobi'
            ],

            // Library Staff
            [
                'name' => 'Ms. Library Manager',
                'email' => 'library.manager@dunco.com',
                'password' => 'password',
                'role' => 'librarian',
                'phone' => '+254700123465',
                'address' => '123 Library Street, Nairobi'
            ],

            // Hostel Staff
            [
                'name' => 'Mr. Hostel Manager',
                'email' => 'hostel.manager@dunco.com',
                'password' => 'password',
                'role' => 'hostel_manager',
                'phone' => '+254700123466',
                'address' => '123 Hostel Street, Nairobi'
            ],

            // Transport Staff
            [
                'name' => 'Mr. Transport Manager',
                'email' => 'transport.manager@dunco.com',
                'password' => 'password',
                'role' => 'transport_manager',
                'phone' => '+254700123467',
                'address' => '123 Transport Street, Nairobi'
            ],
        ];

        foreach ($testUsers as $userData) {
            $role = Role::where('name', $userData['role'])->first();
            
            if (!$role) {
                $this->command->warn("Role {$userData['role']} not found, skipping user {$userData['email']}");
                continue;
            }

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                    'school_id' => $school->id,
                    'phone' => $userData['phone'],
                    'address' => $userData['address'],
                ]
            );

            // Assign role if not already assigned
            if (!$user->roles()->where('role_id', $role->id)->exists()) {
                $user->roles()->attach($role->id);
            }

            // Create teacher record for teacher users
            if ($userData['role'] === 'teacher') {
                Teacher::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'user_id' => $user->id,
                        'school_id' => $school->id,
                        'employee_number' => 'TCH' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                        'qualification' => 'Bachelor of Education',
                        'experience_years' => 5,
                        'subject_specialization' => 'General',
                    ]
                );
            }

            $this->command->info("Created user: {$userData['email']} with role: {$userData['role']}");
        }

        $this->command->info('Test data seeded successfully!');
        $this->command->info('Total users created: ' . count($testUsers));
        $this->command->info('Test credentials:');
        $this->command->info('- Students: john.student@dunco.com, sarah.student@dunco.com, mike.student@dunco.com (password: password)');
        $this->command->info('- Parents: john.parent@dunco.com, sarah.parent@dunco.com (password: password)');
        $this->command->info('- Teachers: david.teacher@dunco.com, jane.teacher@dunco.com (password: password)');
        $this->command->info('- Staff: academic.admin@dunco.com, finance.manager@dunco.com (password: password)');
    }
}
