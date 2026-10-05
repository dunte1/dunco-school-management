<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SimpleTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Creating simple test data for Dunco School Management System...');

        // Create roles first
        $this->createRoles();
        
        // Create users
        $this->createUsers();
        
        // Create academic sessions
        $this->createAcademicSessions();
        
        // Create academic classes
        $this->createAcademicClasses();
        
        // Create academic students
        $this->createAcademicStudents();
        
        // Create fees
        $this->createFees();
        
        // Create invoices
        $this->createInvoices();
        
        // Create payments
        $this->createPayments();
        
        // Create bank accounts
        $this->createBankAccounts();
        
        // Create notifications
        $this->createNotifications();

        $this->command->info('✅ Test data created successfully!');
        $this->command->info('');
        $this->command->info('🔑 Test Login Credentials:');
        $this->command->info('Admin: admin@duncowebsolutions.co.ke / password123');
        $this->command->info('Finance: finance@duncowebsolutions.co.ke / password123');
        $this->command->info('Parent: john.kamau@gmail.com / password123');
        $this->command->info('Teacher: mary.wanjiku@duncowebsolutions.co.ke / password123');
    }

    private function createRoles()
    {
        $this->command->info('🔐 Creating roles...');
        
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'System Administrator',
                'description' => 'Full system access and administration',
                'is_system' => true,
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

    private function createUsers()
    {
        $this->command->info('👥 Creating test users...');
        
        // Get role IDs
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $financeRoleId = DB::table('roles')->where('name', 'finance_manager')->value('id');
        $teacherRoleId = DB::table('roles')->where('name', 'teacher')->value('id');
        $parentRoleId = DB::table('roles')->where('name', 'parent')->value('id');
        
        $users = [
            // System Administrator
            [
                'name' => 'System Administrator',
                'email' => 'admin@duncowebsolutions.co.ke',
                'password' => Hash::make('password123'),
                'primary_role_id' => $adminRoleId,
                'email_verified_at' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Finance Manager
            [
                'name' => 'Sarah Mwangi',
                'email' => 'finance@duncowebsolutions.co.ke',
                'password' => Hash::make('password123'),
                'primary_role_id' => $financeRoleId,
                'email_verified_at' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Teacher
            [
                'name' => 'Mary Wanjiku',
                'email' => 'mary.wanjiku@duncowebsolutions.co.ke',
                'password' => Hash::make('password123'),
                'primary_role_id' => $teacherRoleId,
                'email_verified_at' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Parent
            [
                'name' => 'John Kamau',
                'email' => 'john.kamau@gmail.com',
                'password' => Hash::make('password123'),
                'primary_role_id' => $parentRoleId,
                'email_verified_at' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user
            );
        }
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

    private function createAcademicClasses()
    {
        $this->command->info('🏫 Creating academic classes...');
        
        // Get a school_id (assuming there's at least one school)
        $schoolId = DB::table('schools')->value('id') ?? 1;
        $teacherId = DB::table('users')->where('email', 'mary.wanjiku@duncowebsolutions.co.ke')->value('id');
        
        $classes = [
            [
                'school_id' => $schoolId,
                'name' => 'Form 1A',
                'code' => 'F1A',
                'description' => 'Form 1 Class A',
                'capacity' => 40,
                'teacher_id' => $teacherId,
                'academic_year' => '2024-2025',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'school_id' => $schoolId,
                'name' => 'Form 2B',
                'code' => 'F2B',
                'description' => 'Form 2 Class B',
                'capacity' => 35,
                'teacher_id' => $teacherId,
                'academic_year' => '2024-2025',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'school_id' => $schoolId,
                'name' => 'Form 3C',
                'code' => 'F3C',
                'description' => 'Form 3 Class C',
                'capacity' => 30,
                'teacher_id' => $teacherId,
                'academic_year' => '2024-2025',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($classes as $class) {
            DB::table('academic_classes')->updateOrInsert(
                ['name' => $class['name'], 'school_id' => $class['school_id']],
                $class
            );
        }
    }

    private function createAcademicStudents()
    {
        $this->command->info('🎓 Creating academic students...');
        
        $schoolId = DB::table('schools')->value('id') ?? 1;
        $classIds = DB::table('academic_classes')->where('school_id', $schoolId)->pluck('id')->toArray();
        $parentUserId = DB::table('users')->where('email', 'john.kamau@gmail.com')->value('id');
        
        $students = [
            [
                'school_id' => $schoolId,
                'user_id' => $parentUserId,
                'student_id' => 'STU-2024-001',
                'name' => 'Alex Kamau',
                'admission_number' => 'STU-2024-001',
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
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'school_id' => $schoolId,
                'user_id' => $parentUserId,
                'student_id' => 'STU-2024-002',
                'name' => 'Grace Wanjiku',
                'admission_number' => 'STU-2024-002',
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
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'school_id' => $schoolId,
                'user_id' => $parentUserId,
                'student_id' => 'STU-2024-003',
                'name' => 'Brian Otieno',
                'admission_number' => 'STU-2024-003',
                'class_id' => $classIds[1] ?? 2,
                'admission_date' => '2024-01-15',
                'date_of_birth' => '2006-11-10',
                'gender' => 'male',
                'nationality' => 'Kenyan',
                'phone' => '0734567890',
                'emergency_contact' => '0745678901',
                'enrollment_status' => 'enrolled',
                'is_active' => true,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($students as $student) {
            DB::table('academic_students')->updateOrInsert(
                ['admission_number' => $student['admission_number']],
                $student
            );
        }
    }

    private function createFees()
    {
        $this->command->info('💰 Creating fees...');
        
        $fees = [
            [
                'name' => 'Tuition Fee',
                'amount' => 25000.00,
                'applies_to' => json_encode(['all_classes' => true]),
                'due_date' => now()->addDays(30),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Library Fee',
                'amount' => 2000.00,
                'applies_to' => json_encode(['all_classes' => true]),
                'due_date' => now()->addDays(30),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Examination Fee',
                'amount' => 3000.00,
                'applies_to' => json_encode(['all_classes' => true]),
                'due_date' => now()->addDays(30),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($fees as $fee) {
            DB::table('fee_configurations')->updateOrInsert(
                ['name' => $fee['name']],
                $fee
            );
        }
    }

    private function createInvoices()
    {
        $this->command->info('📄 Creating invoices...');
        
        $students = DB::table('academic_students')->get();
        $fees = DB::table('fee_configurations')->where('is_active', true)->get();
        $totalAmount = $fees->sum('amount');
        
        foreach ($students as $student) {
            $invoiceId = DB::table('invoices')->insertGetId([
                'invoice_number' => 'INV-' . $student->admission_number . '-2024-T1',
                'student_id' => $student->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'due_date' => now()->addDays(30),
                'term' => 'Term 1 2024',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create invoice items
            foreach ($fees as $fee) {
                DB::table('invoice_items')->insert([
                    'invoice_id' => $invoiceId,
                    'fee_id' => $fee->id,
                    'description' => $fee->name,
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
        
        $invoices = DB::table('invoices')->take(2)->get(); // Create payments for first 2 invoices
        
        foreach ($invoices as $invoice) {
            $paymentId = DB::table('payments')->insertGetId([
                'invoice_id' => $invoice->id,
                'amount' => $invoice->total_amount,
                'payment_date' => now()->subDays(rand(1, 30)),
                'method' => 'mpesa',
                'status' => 'completed',
                'reference' => 'REF-' . strtoupper(uniqid()),
                'mpesa_transaction_id' => 'MPESA-' . strtoupper(uniqid()),
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]);

            // Update invoice status
            DB::table('invoices')->where('id', $invoice->id)->update(['status' => 'paid']);
        }
    }

    private function createBankAccounts()
    {
        $this->command->info('🏦 Creating bank accounts...');
        
        $bankAccounts = [
            [
                'name' => 'Main School Account',
                'account_number' => '1234567890',
                'bank_name' => 'Equity Bank',
                'branch' => 'Nairobi CBD',
                'balance' => 2500000.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fees Collection Account',
                'account_number' => '0987654321',
                'bank_name' => 'KCB Bank',
                'branch' => 'Westlands',
                'balance' => 1800000.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($bankAccounts as $account) {
            DB::table('bank_accounts')->updateOrInsert(
                ['account_number' => $account['account_number']],
                $account
            );
        }
    }

    private function createNotifications()
    {
        $this->command->info('🔔 Creating notifications...');
        
        $notifications = [
            [
                'title' => 'Fee Payment Reminder',
                'message' => 'Please remember to pay the Term 1 2024 fees by the due date.',
                'type' => 'fee_reminder',
                'priority' => 'high',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'M-Pesa Payment Available',
                'message' => 'You can now pay school fees conveniently using M-Pesa. Simply follow the instructions on the payment page.',
                'type' => 'payment',
                'priority' => 'medium',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Examination Schedule',
                'message' => 'End of term examinations will begin on Monday next week.',
                'type' => 'examination',
                'priority' => 'high',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($notifications as $notification) {
            DB::table('notifications')->insert($notification);
        }
    }
}
