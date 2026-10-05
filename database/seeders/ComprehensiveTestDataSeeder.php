<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComprehensiveTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Creating comprehensive test data for Dunco School Management System...');

        // Create roles first
        $this->createRoles();
        
        // Create schools
        $schools = $this->createSchools();
        
        // Create super admin
        $this->createSuperAdmin();
        
        // Create school admins and users
        $this->createSchoolAdminsAndUsers($schools);
        
        // Create academic sessions
        $this->createAcademicSessions();
        
        // Create academic classes for each school
        $this->createAcademicClasses($schools);
        
        // Create academic students
        $this->createAcademicStudents($schools);
        
        // Create fees for each school
        $this->createFees($schools);
        
        // Create invoices
        $this->createInvoices();
        
        // Create payments
        $this->createPayments();
        
        // Create bank accounts for each school
        $this->createBankAccounts($schools);
        
        // Create notifications
        $this->createNotifications();

        $this->command->info('✅ Comprehensive test data created successfully!');
        $this->command->info('');
        $this->command->info('🔑 Test Login Credentials:');
        $this->command->info('Super Admin: superadmin@dunco.com / password123');
        $this->command->info('School Admin (Dunco High): admin@dunco.com / password123');
        $this->command->info('School Admin (St. Mary\'s): admin2@dunco.com / password123');
        $this->command->info('School Admin (Nairobi Primary): admin3@dunco.com / password123');
        $this->command->info('Finance Manager: finance@dunco.com / password123');
        $this->command->info('Parent: john.kamau@gmail.com / password123');
        $this->command->info('Teacher: mary.wanjiku@dunco.com / password123');
    }

    private function createRoles()
    {
        $this->command->info('🔐 Creating roles...');
        
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'System-wide super administrator with full access',
                'is_system' => true,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'school_admin',
                'display_name' => 'School Administrator',
                'description' => 'School-level administrator with full school access',
                'is_system' => false,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'finance_manager',
                'display_name' => 'Finance Manager',
                'description' => 'Manage financial operations and payments',
                'is_system' => false,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'teacher',
                'display_name' => 'Teacher',
                'description' => 'Teaching staff with class management access',
                'is_system' => false,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'parent',
                'display_name' => 'Parent/Guardian',
                'description' => 'Parent access to student information and payments',
                'is_system' => false,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                $role
            );
        }
    }

    private function createSchools()
    {
        $this->command->info('🏫 Creating schools...');
        
        $schools = [
            [
                'name' => 'Dunco High School',
                'code' => 'DHS-2024',
                'logo' => null,
                'theme' => 'blue',
                'motto' => 'Excellence Through Knowledge',
                'level' => 'Secondary',
                'phone' => '+254 20 1234567',
                'email' => 'info@dunco.com',
                'address' => 'P.O. Box 12345, Nairobi',
                'domain' => 'duncohigh-2024.duncowebsolutions.co.ke',
                'settings' => json_encode([
                    'currency' => 'KES',
                    'timezone' => 'Africa/Nairobi',
                    'academic_year' => '2024-2025',
                    'term_dates' => [
                        'term1_start' => '2024-01-15',
                        'term1_end' => '2024-04-05',
                        'term2_start' => '2024-05-06',
                        'term2_end' => '2024-08-09',
                        'term3_start' => '2024-09-02',
                        'term3_end' => '2024-11-29'
                    ]
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'St. Mary\'s Academy',
                'code' => 'SMA-2024',
                'logo' => null,
                'theme' => 'green',
                'motto' => 'Faith, Knowledge, Service',
                'level' => 'Secondary',
                'phone' => '+254 20 2345678',
                'email' => 'info@dunco.com',
                'address' => 'P.O. Box 23456, Mombasa',
                'domain' => 'stmarys-2024.duncowebsolutions.co.ke',
                'settings' => json_encode([
                    'currency' => 'KES',
                    'timezone' => 'Africa/Nairobi',
                    'academic_year' => '2024-2025',
                    'term_dates' => [
                        'term1_start' => '2024-01-15',
                        'term1_end' => '2024-04-05',
                        'term2_start' => '2024-05-06',
                        'term2_end' => '2024-08-09',
                        'term3_start' => '2024-09-02',
                        'term3_end' => '2024-11-29'
                    ]
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nairobi Primary School',
                'code' => 'NPS-2024',
                'logo' => null,
                'theme' => 'orange',
                'motto' => 'Building Tomorrow\'s Leaders',
                'level' => 'Primary',
                'phone' => '+254 20 3456789',
                'email' => 'info@dunco.com',
                'address' => 'P.O. Box 34567, Nairobi',
                'domain' => 'nairobiprimary-2024.duncowebsolutions.co.ke',
                'settings' => json_encode([
                    'currency' => 'KES',
                    'timezone' => 'Africa/Nairobi',
                    'academic_year' => '2024-2025',
                    'term_dates' => [
                        'term1_start' => '2024-01-15',
                        'term1_end' => '2024-04-05',
                        'term2_start' => '2024-05-06',
                        'term2_end' => '2024-08-09',
                        'term3_start' => '2024-09-02',
                        'term3_end' => '2024-11-29'
                    ]
                ]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $createdSchools = [];
        foreach ($schools as $school) {
            // Check if school already exists
            $existingSchool = DB::table('schools')->where('code', $school['code'])->first();
            
            if ($existingSchool) {
                // Update existing school
                DB::table('schools')->where('code', $school['code'])->update($school);
                $schoolId = $existingSchool->id;
            } else {
                // Insert new school
                $schoolId = DB::table('schools')->insertGetId($school);
            }
            
            $createdSchools[] = (object) array_merge($school, ['id' => $schoolId]);
        }

        return $createdSchools;
    }

    private function createSuperAdmin()
    {
        $this->command->info('👑 Creating super admin...');
        
        $superAdminRoleId = DB::table('roles')->where('name', 'super_admin')->value('id');
        
        $superAdmin = [
            'name' => 'Super Administrator',
            'email' => 'superadmin@dunco.com',
            'password' => Hash::make('password123'),
            'primary_role_id' => $superAdminRoleId,
            'email_verified_at' => now(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('users')->updateOrInsert(
            ['email' => $superAdmin['email']],
            $superAdmin
        );
    }

    private function createSchoolAdminsAndUsers($schools)
    {
        $this->command->info('👥 Creating school admins and users...');
        
        $schoolAdminRoleId = DB::table('roles')->where('name', 'school_admin')->value('id');
        $financeRoleId = DB::table('roles')->where('name', 'finance_manager')->value('id');
        $teacherRoleId = DB::table('roles')->where('name', 'teacher')->value('id');
        $parentRoleId = DB::table('roles')->where('name', 'parent')->value('id');
        
        $users = [
            // School Admins
            [
                'name' => 'Dr. James Kimani',
                'email' => 'admin@dunco.com',
                'password' => Hash::make('password123'),
                'primary_role_id' => $schoolAdminRoleId,
                'email_verified_at' => now(),
                'is_active' => true,
                'school_id' => $schools[0]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sister Mary Wanjiku',
                'email' => 'admin2@dunco.com',
                'password' => Hash::make('password123'),
                'primary_role_id' => $schoolAdminRoleId,
                'email_verified_at' => now(),
                'is_active' => true,
                'school_id' => $schools[1]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mr. Peter Mwangi',
                'email' => 'admin3@dunco.com',
                'password' => Hash::make('password123'),
                'primary_role_id' => $schoolAdminRoleId,
                'email_verified_at' => now(),
                'is_active' => true,
                'school_id' => $schools[2]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Finance Manager
            [
                'name' => 'Sarah Mwangi',
                'email' => 'finance@dunco.com',
                'password' => Hash::make('password123'),
                'primary_role_id' => $financeRoleId,
                'email_verified_at' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Teachers
            [
                'name' => 'Mary Wanjiku',
                'email' => 'mary.wanjiku@dunco.com',
                'password' => Hash::make('password123'),
                'primary_role_id' => $teacherRoleId,
                'email_verified_at' => now(),
                'is_active' => true,
                'school_id' => $schools[0]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Peter Kiprop',
                'email' => 'peter.kiprop@dunco.com',
                'password' => Hash::make('password123'),
                'primary_role_id' => $teacherRoleId,
                'email_verified_at' => now(),
                'is_active' => true,
                'school_id' => $schools[1]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Grace Akinyi',
                'email' => 'grace.akinyi@dunco.com',
                'password' => Hash::make('password123'),
                'primary_role_id' => $teacherRoleId,
                'email_verified_at' => now(),
                'is_active' => true,
                'school_id' => $schools[2]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Parents
            [
                'name' => 'John Kamau',
                'email' => 'john.kamau@gmail.com',
                'password' => Hash::make('password123'),
                'primary_role_id' => $parentRoleId,
                'email_verified_at' => now(),
                'is_active' => true,
                'school_id' => $schools[0]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Wanjiku',
                'email' => 'jane.wanjiku@gmail.com',
                'password' => Hash::make('password123'),
                'primary_role_id' => $parentRoleId,
                'email_verified_at' => now(),
                'is_active' => true,
                'school_id' => $schools[1]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Michael Otieno',
                'email' => 'michael.otieno@gmail.com',
                'password' => Hash::make('password123'),
                'primary_role_id' => $parentRoleId,
                'email_verified_at' => now(),
                'is_active' => true,
                'school_id' => $schools[2]->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $createdUsers = [];
        foreach ($users as $user) {
            $userId = DB::table('users')->insertGetId($user);
            $createdUsers[] = (object) array_merge($user, ['id' => $userId]);
            
            // Create school_user_admin relationship for school admins
            if ($user['primary_role_id'] == $schoolAdminRoleId && isset($user['school_id'])) {
                DB::table('school_user_admin')->insert([
                    'school_id' => $user['school_id'],
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return $createdUsers;
    }

    private function createAcademicSessions()
    {
        $this->command->info('📅 Creating academic sessions...');
        
        $sessions = [
            [
                'name' => '2024-2025',
                'session_year' => '2024-2025',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'is_active' => true,
                'is_current' => true,
                'description' => 'Academic Year 2024-2025',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($sessions as $session) {
            DB::table('academic_sessions')->updateOrInsert(
                ['name' => $session['name']],
                $session
            );
        }
    }

    private function createAcademicClasses($schools)
    {
        $this->command->info('🏫 Creating academic classes...');
        
        $teacherIds = DB::table('users')
            ->whereIn('email', [
                'mary.wanjiku@dunco.com',
                'peter.kiprop@dunco.com',
                'grace.akinyi@dunco.com'
            ])
            ->pluck('id')
            ->toArray();
        
        $classData = [
            // Dunco High School (Secondary)
            [
                'school_id' => $schools[0]->id,
                'name' => 'Form 1A',
                'code' => 'DHS-2024-F1A',
                'description' => 'Form 1 Class A',
                'capacity' => 40,
                'teacher_id' => $teacherIds[0] ?? null,
                'academic_year' => '2024-2025',
                'is_active' => true,
            ],
            [
                'school_id' => $schools[0]->id,
                'name' => 'Form 2B',
                'code' => 'DHS-2024-F2B',
                'description' => 'Form 2 Class B',
                'capacity' => 35,
                'teacher_id' => $teacherIds[0] ?? null,
                'academic_year' => '2024-2025',
                'is_active' => true,
            ],
            [
                'school_id' => $schools[0]->id,
                'name' => 'Form 3C',
                'code' => 'DHS-2024-F3C',
                'description' => 'Form 3 Class C',
                'capacity' => 30,
                'teacher_id' => $teacherIds[0] ?? null,
                'academic_year' => '2024-2025',
                'is_active' => true,
            ],
            // St. Mary's Academy (Secondary)
            [
                'school_id' => $schools[1]->id,
                'name' => 'Form 1A',
                'code' => 'SMA-2024-F1A',
                'description' => 'Form 1 Class A',
                'capacity' => 35,
                'teacher_id' => $teacherIds[1] ?? null,
                'academic_year' => '2024-2025',
                'is_active' => true,
            ],
            [
                'school_id' => $schools[1]->id,
                'name' => 'Form 2A',
                'code' => 'SMA-2024-F2A',
                'description' => 'Form 2 Class A',
                'capacity' => 32,
                'teacher_id' => $teacherIds[1] ?? null,
                'academic_year' => '2024-2025',
                'is_active' => true,
            ],
            // Nairobi Primary School (Primary)
            [
                'school_id' => $schools[2]->id,
                'name' => 'Class 5A',
                'code' => 'NPS-2024-C5A',
                'description' => 'Class 5 A',
                'capacity' => 45,
                'teacher_id' => $teacherIds[2] ?? null,
                'academic_year' => '2024-2025',
                'is_active' => true,
            ],
            [
                'school_id' => $schools[2]->id,
                'name' => 'Class 6B',
                'code' => 'NPS-2024-C6B',
                'description' => 'Class 6 B',
                'capacity' => 42,
                'teacher_id' => $teacherIds[2] ?? null,
                'academic_year' => '2024-2025',
                'is_active' => true,
            ],
        ];

        foreach ($classData as $class) {
            $class['created_at'] = now();
            $class['updated_at'] = now();
            
            DB::table('academic_classes')->updateOrInsert(
                ['name' => $class['name'], 'school_id' => $class['school_id']],
                $class
            );
        }
    }

    private function createAcademicStudents($schools)
    {
        $this->command->info('🎓 Creating academic students...');
        
        $parentUserIds = DB::table('users')
            ->whereIn('email', [
                'john.kamau@gmail.com',
                'jane.wanjiku@gmail.com',
                'michael.otieno@gmail.com'
            ])
            ->pluck('id')
            ->toArray();
        
        $classIds = DB::table('academic_classes')->pluck('id')->toArray();
        
        $students = [
            // Dunco High School Students
            [
                'school_id' => $schools[0]->id,
                'user_id' => $parentUserIds[0],
                'student_id' => 'DHS-2024-001',
                'name' => 'Alex Kamau',
                'admission_number' => 'DHS-2024-001',
                'class_id' => $classIds[0] ?? 1,
                'admission_date' => '2024-01-15',
                'date_of_birth' => '2008-03-15',
                'gender' => 'male',
                'nationality' => 'Kenyan',
                'phone' => '0712345678',
                'emergency_contact' => '0723456789',
                'enrollment_status' => 'enrolled',
                'is_active' => true,
                'status' => 'active',
            ],
            [
                'school_id' => $schools[0]->id,
                'user_id' => $parentUserIds[0],
                'student_id' => 'DHS-2024-002',
                'name' => 'Grace Wanjiku',
                'admission_number' => 'DHS-2024-002',
                'class_id' => $classIds[0] ?? 1,
                'admission_date' => '2024-01-15',
                'date_of_birth' => '2007-07-22',
                'gender' => 'female',
                'nationality' => 'Kenyan',
                'phone' => '0723456789',
                'emergency_contact' => '0734567890',
                'enrollment_status' => 'enrolled',
                'is_active' => true,
                'status' => 'active',
            ],
            // St. Mary's Academy Students
            [
                'school_id' => $schools[1]->id,
                'user_id' => $parentUserIds[1],
                'student_id' => 'SMA-2024-001',
                'name' => 'Brian Otieno',
                'admission_number' => 'SMA-2024-001',
                'class_id' => $classIds[3] ?? 4,
                'admission_date' => '2024-01-15',
                'date_of_birth' => '2006-11-10',
                'gender' => 'male',
                'nationality' => 'Kenyan',
                'phone' => '0734567890',
                'emergency_contact' => '0745678901',
                'enrollment_status' => 'enrolled',
                'is_active' => true,
                'status' => 'active',
            ],
            [
                'school_id' => $schools[1]->id,
                'user_id' => $parentUserIds[1],
                'student_id' => 'SMA-2024-002',
                'name' => 'Faith Muthoni',
                'admission_number' => 'SMA-2024-002',
                'class_id' => $classIds[4] ?? 5,
                'admission_date' => '2024-01-15',
                'date_of_birth' => '2005-05-18',
                'gender' => 'female',
                'nationality' => 'Kenyan',
                'phone' => '0745678901',
                'emergency_contact' => '0756789012',
                'enrollment_status' => 'enrolled',
                'is_active' => true,
                'status' => 'active',
            ],
            // Nairobi Primary School Students
            [
                'school_id' => $schools[2]->id,
                'user_id' => $parentUserIds[2],
                'student_id' => 'NPS-2024-001',
                'name' => 'Kevin Kipchoge',
                'admission_number' => 'NPS-2024-001',
                'class_id' => $classIds[5] ?? 6,
                'admission_date' => '2024-01-15',
                'date_of_birth' => '2012-09-12',
                'gender' => 'male',
                'nationality' => 'Kenyan',
                'phone' => '0756789012',
                'emergency_contact' => '0767890123',
                'enrollment_status' => 'enrolled',
                'is_active' => true,
                'status' => 'active',
            ],
        ];

        foreach ($students as $student) {
            $student['created_at'] = now();
            $student['updated_at'] = now();
            
            DB::table('academic_students')->updateOrInsert(
                ['admission_number' => $student['admission_number']],
                $student
            );
        }
    }

    private function createFees($schools)
    {
        $this->command->info('💰 Creating fees for each school...');
        
        $feeTypes = [
            'Secondary' => [
                ['name' => 'Tuition Fee', 'amount' => 25000.00, 'description' => 'Termly tuition fee'],
                ['name' => 'Library Fee', 'amount' => 2000.00, 'description' => 'Library and resource fee'],
                ['name' => 'Examination Fee', 'amount' => 3000.00, 'description' => 'End of term examination fee'],
                ['name' => 'Boarding Fee', 'amount' => 15000.00, 'description' => 'Termly boarding fee'],
            ],
            'Primary' => [
                ['name' => 'Tuition Fee', 'amount' => 15000.00, 'description' => 'Termly tuition fee'],
                ['name' => 'Library Fee', 'amount' => 1000.00, 'description' => 'Library and resource fee'],
                ['name' => 'Examination Fee', 'amount' => 1500.00, 'description' => 'End of term examination fee'],
                ['name' => 'Activity Fee', 'amount' => 2000.00, 'description' => 'Co-curricular activities fee'],
            ],
        ];

        foreach ($schools as $school) {
            $fees = $feeTypes[$school->level] ?? $feeTypes['Secondary'];
            
            foreach ($fees as $fee) {
                $feeData = [
                    'school_id' => $school->id,
                    'name' => $fee['name'],
                    'amount' => $fee['amount'],
                    'description' => $fee['description'],
                    'fee_category_id' => null,
                    'fee_type_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                DB::table('fees')->updateOrInsert(
                    ['name' => $fee['name'], 'school_id' => $school->id],
                    $feeData
                );
            }
        }
    }

    private function createInvoices()
    {
        $this->command->info('📄 Creating invoices...');
        
        $students = DB::table('academic_students')->get();
        
        foreach ($students as $student) {
            $fees = DB::table('fees')->where('school_id', $student->school_id)->get();
            $totalAmount = $fees->sum('amount');
            
            $invoiceId = DB::table('invoices')->insertGetId([
                'student_id' => $student->id,
                'total_amount' => $totalAmount,
                'due_date' => now()->addDays(30),
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create invoice items
            foreach ($fees as $fee) {
                DB::table('invoice_items')->insert([
                    'invoice_id' => $invoiceId,
                    'fee_id' => $fee->id,
                    'amount' => $fee->amount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function createPayments()
    {
        $this->command->info('💳 Creating payments...');
        
        $invoices = DB::table('invoices')->take(3)->get(); // Create payments for first 3 invoices
        
        foreach ($invoices as $invoice) {
            $paymentId = DB::table('payments')->insertGetId([
                'invoice_id' => $invoice->id,
                'amount' => $invoice->total_amount,
                'payment_date' => now()->subDays(rand(1, 30)),
                'method' => 'mpesa',
                'status' => 'completed',
                'reference' => 'REF-' . strtoupper(uniqid()),
                'mpesa_transaction_code' => 'MPESA-' . strtoupper(uniqid()),
                'mpesa_phone' => '0712345678',
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]);

            // Update invoice status
            DB::table('invoices')->where('id', $invoice->id)->update(['status' => 'paid']);
        }
    }

    private function createBankAccounts($schools)
    {
        $this->command->info('🏦 Creating bank accounts for each school...');
        
        $bankAccounts = [
            [
                'name' => 'Main School Account',
                'account_number' => '1234567890',
                'bank_name' => 'Equity Bank',
                'balance' => 2500000.00,
            ],
            [
                'name' => 'Fees Collection Account',
                'account_number' => '0987654321',
                'bank_name' => 'KCB Bank',
                'balance' => 1800000.00,
            ],
        ];

        foreach ($schools as $school) {
            foreach ($bankAccounts as $index => $account) {
                $accountData = [
                    'name' => $school->name . ' - ' . $account['name'],
                    'account_number' => $account['account_number'] . $school->id,
                    'bank_name' => $account['bank_name'],
                    'balance' => $account['balance'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                DB::table('bank_accounts')->updateOrInsert(
                    ['account_number' => $accountData['account_number']],
                    $accountData
                );
            }
        }
    }

    private function createNotifications()
    {
        $this->command->info('🔔 Creating notifications...');
        
        $userIds = DB::table('users')->pluck('id')->toArray();
        
        $notifications = [
            [
                'type' => 'system_announcement',
                'title' => 'System Maintenance',
                'data' => json_encode(['message' => 'Scheduled system maintenance will occur tonight from 2 AM to 4 AM.']),
                'notifiable_id' => $userIds[0] ?? 1,
                'notifiable_type' => 'App\\Models\\User',
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'fee_reminder',
                'title' => 'Fee Payment Reminder',
                'data' => json_encode(['message' => 'Please remember to pay the Term 1 2024 fees by the due date.']),
                'notifiable_id' => $userIds[7] ?? 8, // Parent user
                'notifiable_type' => 'App\\Models\\User',
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'payment',
                'title' => 'M-Pesa Payment Available',
                'data' => json_encode(['message' => 'You can now pay school fees conveniently using M-Pesa.']),
                'notifiable_id' => $userIds[7] ?? 8, // Parent user
                'notifiable_type' => 'App\\Models\\User',
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($notifications as $notification) {
            DB::table('notifications')->insert($notification);
        }
    }
}
