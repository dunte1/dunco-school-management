<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\School;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\AcademicClass;
use Modules\HR\Models\Staff;

class FinalDocumentTestSeeder extends Seeder
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

        // Create sample academic class
        $class = AcademicClass::firstOrCreate(
            ['code' => 'F1A'],
            [
                'school_id' => $school->id,
                'name' => 'Form 1A',
                'academic_year' => '2024',
                'capacity' => 40,
                'is_active' => true
            ]
        );

        // Create sample students with users
        $students = [
            [
                'name' => 'John Doe',
                'admission_number' => 'STU2024001',
                'email' => 'john.doe@student.dunco.edu.ke',
                'gender' => 'male',
                'date_of_birth' => '2008-03-15',
                'phone' => '+254 700 111 001',
                'address' => '123 Student Street, Nairobi',
                'blood_group' => 'O+',
                'emergency_contact' => 'Jane Doe',
                'admission_date' => '2024-01-15'
            ],
            [
                'name' => 'Jane Smith',
                'admission_number' => 'STU2024002',
                'email' => 'jane.smith@student.dunco.edu.ke',
                'gender' => 'female',
                'date_of_birth' => '2008-07-22',
                'phone' => '+254 700 111 003',
                'address' => '456 Student Avenue, Nairobi',
                'blood_group' => 'A+',
                'emergency_contact' => 'John Smith',
                'admission_date' => '2024-01-15'
            ],
            [
                'name' => 'Mike Johnson',
                'admission_number' => 'STU2024003',
                'email' => 'mike.johnson@student.dunco.edu.ke',
                'gender' => 'male',
                'date_of_birth' => '2007-11-08',
                'phone' => '+254 700 111 005',
                'address' => '789 Student Road, Nairobi',
                'blood_group' => 'B+',
                'emergency_contact' => 'Sarah Johnson',
                'admission_date' => '2024-01-15'
            ]
        ];

        foreach ($students as $studentData) {
            // Create user first
            $user = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'password' => Hash::make('password123'),
                    'school_id' => $school->id,
                    'email_verified_at' => now()
                ]
            );

            // Create student record
            Student::firstOrCreate(
                ['admission_number' => $studentData['admission_number']],
                [
                    'school_id' => $school->id,
                    'user_id' => $user->id,
                    'student_id' => $studentData['admission_number'],
                    'name' => $studentData['name'],
                    'admission_number' => $studentData['admission_number'],
                    'admission_date' => $studentData['admission_date'],
                    'date_of_birth' => $studentData['date_of_birth'],
                    'gender' => $studentData['gender'],
                    'blood_group' => $studentData['blood_group'],
                    'address' => $studentData['address'],
                    'phone' => $studentData['phone'],
                    'emergency_contact' => $studentData['emergency_contact'],
                    'class_id' => $class->id,
                    'enrollment_status' => 'enrolled',
                    'is_active' => true
                ]
            );
        }

        // Create sample staff with users
        $staff = [
            [
                'first_name' => 'Mary',
                'last_name' => 'Johnson',
                'staff_id' => 'STAFF2024001',
                'email' => 'mary.johnson@dunco.edu.ke',
                'gender' => 'female',
                'dob' => '1985-04-20',
                'phone' => '+254 700 222 001',
                'address' => '123 Staff Street, Nairobi',
                'job_title' => 'Principal',
                'department_id' => null,
                'status' => 'active'
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Wilson',
                'staff_id' => 'STAFF2024002',
                'email' => 'james.wilson@dunco.edu.ke',
                'gender' => 'male',
                'dob' => '1988-08-10',
                'phone' => '+254 700 222 002',
                'address' => '456 Staff Avenue, Nairobi',
                'job_title' => 'Mathematics Teacher',
                'department_id' => null,
                'status' => 'active'
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Davis',
                'staff_id' => 'STAFF2024003',
                'email' => 'sarah.davis@dunco.edu.ke',
                'gender' => 'female',
                'dob' => '1990-12-05',
                'phone' => '+254 700 222 003',
                'address' => '789 Staff Road, Nairobi',
                'job_title' => 'English Teacher',
                'department_id' => null,
                'status' => 'active'
            ]
        ];

        foreach ($staff as $staffData) {
            // Create user first
            $user = User::firstOrCreate(
                ['email' => $staffData['email']],
                [
                    'name' => $staffData['first_name'] . ' ' . $staffData['last_name'],
                    'password' => Hash::make('password123'),
                    'school_id' => $school->id,
                    'email_verified_at' => now()
                ]
            );

            // Create staff record
            Staff::firstOrCreate(
                ['staff_id' => $staffData['staff_id']],
                [
                    'school_id' => $school->id,
                    'user_id' => $user->id,
                    'first_name' => $staffData['first_name'],
                    'last_name' => $staffData['last_name'],
                    'email' => $staffData['email'],
                    'staff_id' => $staffData['staff_id'],
                    'dob' => $staffData['dob'],
                    'gender' => $staffData['gender'],
                    'phone' => $staffData['phone'],
                    'address' => $staffData['address'],
                    'job_title' => $staffData['job_title'],
                    'department_id' => $staffData['department_id'],
                    'status' => $staffData['status']
                ]
            );
        }

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

        $this->command->info('✅ Document test data created successfully!');
        $this->command->info('');
        $this->command->info('🏫 School: Dunco International School');
        $this->command->info('👥 Students: ' . Student::where('school_id', $school->id)->count() . ' students created');
        $this->command->info('👨‍🏫 Staff: ' . Staff::where('school_id', $school->id)->count() . ' staff members created');
        $this->command->info('');
        $this->command->info('🔑 Login Credentials:');
        $this->command->info('   Admin: admin@dunco.edu.ke / password123');
        $this->command->info('   Student: john.doe@student.dunco.edu.ke / password123');
        $this->command->info('   Staff: mary.johnson@dunco.edu.ke / password123');
        $this->command->info('');
        $this->command->info('📄 Test the document generation interface at:');
        $this->command->info('   /document-generation/');
        $this->command->info('   /document-generation/students');
        $this->command->info('   /document-generation/staff');
        $this->command->info('');
        $this->command->info('🎯 You can now test:');
        $this->command->info('   • Student ID Card generation');
        $this->command->info('   • Staff ID Card generation');
        $this->command->info('   • Document dashboard interface');
        $this->command->info('   • Multi-school branding');
    }
}
