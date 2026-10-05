<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating sample data for Dunco School Management System...');

        // Create users
        $this->createUsers();
        
        // Create students
        $this->createStudents();
        
        // Create classes
        $this->createClasses();
        
        // Create subjects
        $this->createSubjects();
        
        // Create fees
        $this->createFees();
        
        // Create invoices
        $this->createInvoices();
        
        // Create payments
        $this->createPayments();
        
        // Create receipts
        $this->createReceipts();
        
        // Create bank accounts
        $this->createBankAccounts();
        
        // Create staff
        $this->createStaff();
        
        // Create academic records
        $this->createAcademicRecords();
        
        // Create attendance records
        $this->createAttendanceRecords();
        
        // Create notifications
        $this->createNotifications();

        $this->command->info('Sample data created successfully!');
    }

    private function createUsers()
    {
        $this->command->info('Creating users...');
        
        $users = [
            // System Administrator
            [
                'name' => 'System Administrator',
                'email' => 'admin@duncowebsolutions.co.ke',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Finance Manager
            [
                'name' => 'Sarah Mwangi',
                'email' => 'finance@duncowebsolutions.co.ke',
                'password' => Hash::make('password123'),
                'role' => 'finance_manager',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Academic Head
            [
                'name' => 'Dr. James Kimani',
                'email' => 'academic@duncowebsolutions.co.ke',
                'password' => Hash::make('password123'),
                'role' => 'academic_head',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Teachers
            [
                'name' => 'Mary Wanjiku',
                'email' => 'mary.wanjiku@duncowebsolutions.co.ke',
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Peter Kiprop',
                'email' => 'peter.kiprop@duncowebsolutions.co.ke',
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Grace Akinyi',
                'email' => 'grace.akinyi@duncowebsolutions.co.ke',
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Parents
            [
                'name' => 'John Kamau',
                'email' => 'john.kamau@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'parent',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Wanjiku',
                'email' => 'jane.wanjiku@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'parent',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Michael Otieno',
                'email' => 'michael.otieno@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'parent',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Susan Muthoni',
                'email' => 'susan.muthoni@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'parent',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'David Kipchoge',
                'email' => 'david.kipchoge@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'parent',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }

    private function createStudents()
    {
        $this->command->info('Creating students...');
        
        $students = [
            [
                'admission_number' => 'STU-2024-001',
                'first_name' => 'Alex',
                'last_name' => 'Kamau',
                'date_of_birth' => '2008-03-15',
                'gender' => 'Male',
                'phone' => '0712345678',
                'email' => 'alex.kamau@student.duncowebsolutions.co.ke',
                'parent_id' => 7, // John Kamau
                'class_id' => 1,
                'admission_date' => '2024-01-15',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admission_number' => 'STU-2024-002',
                'first_name' => 'Grace',
                'last_name' => 'Wanjiku',
                'date_of_birth' => '2007-07-22',
                'gender' => 'Female',
                'phone' => '0723456789',
                'email' => 'grace.wanjiku@student.duncowebsolutions.co.ke',
                'parent_id' => 8, // Jane Wanjiku
                'class_id' => 1,
                'admission_date' => '2024-01-15',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admission_number' => 'STU-2024-003',
                'first_name' => 'Brian',
                'last_name' => 'Otieno',
                'date_of_birth' => '2006-11-10',
                'gender' => 'Male',
                'phone' => '0734567890',
                'email' => 'brian.otieno@student.duncowebsolutions.co.ke',
                'parent_id' => 9, // Michael Otieno
                'class_id' => 2,
                'admission_date' => '2024-01-15',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admission_number' => 'STU-2024-004',
                'first_name' => 'Faith',
                'last_name' => 'Muthoni',
                'date_of_birth' => '2005-05-18',
                'gender' => 'Female',
                'phone' => '0745678901',
                'email' => 'faith.muthoni@student.duncowebsolutions.co.ke',
                'parent_id' => 10, // Susan Muthoni
                'class_id' => 2,
                'admission_date' => '2024-01-15',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admission_number' => 'STU-2024-005',
                'first_name' => 'Kevin',
                'last_name' => 'Kipchoge',
                'date_of_birth' => '2004-09-12',
                'gender' => 'Male',
                'phone' => '0756789012',
                'email' => 'kevin.kipchoge@student.duncowebsolutions.co.ke',
                'parent_id' => 11, // David Kipchoge
                'class_id' => 3,
                'admission_date' => '2024-01-15',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admission_number' => 'STU-2024-006',
                'first_name' => 'Mercy',
                'last_name' => 'Njeri',
                'date_of_birth' => '2007-12-03',
                'gender' => 'Female',
                'phone' => '0767890123',
                'email' => 'mercy.njeri@student.duncowebsolutions.co.ke',
                'parent_id' => 7, // John Kamau
                'class_id' => 1,
                'admission_date' => '2024-01-15',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admission_number' => 'STU-2024-007',
                'first_name' => 'Samuel',
                'last_name' => 'Mwangi',
                'date_of_birth' => '2006-08-25',
                'gender' => 'Male',
                'phone' => '0778901234',
                'email' => 'samuel.mwangi@student.duncowebsolutions.co.ke',
                'parent_id' => 8, // Jane Wanjiku
                'class_id' => 2,
                'admission_date' => '2024-01-15',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'admission_number' => 'STU-2024-008',
                'first_name' => 'Priscilla',
                'last_name' => 'Akinyi',
                'date_of_birth' => '2005-04-14',
                'gender' => 'Female',
                'phone' => '0789012345',
                'email' => 'priscilla.akinyi@student.duncowebsolutions.co.ke',
                'parent_id' => 9, // Michael Otieno
                'class_id' => 3,
                'admission_date' => '2024-01-15',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($students as $student) {
            DB::table('students')->insert($student);
        }
    }

    private function createClasses()
    {
        $this->command->info('Creating classes...');
        
        $classes = [
            [
                'name' => 'Form 1A',
                'level' => 'Form 1',
                'capacity' => 40,
                'class_teacher_id' => 4, // Mary Wanjiku
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Form 2B',
                'level' => 'Form 2',
                'capacity' => 35,
                'class_teacher_id' => 5, // Peter Kiprop
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Form 3C',
                'level' => 'Form 3',
                'capacity' => 30,
                'class_teacher_id' => 6, // Grace Akinyi
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Form 4A',
                'level' => 'Form 4',
                'capacity' => 25,
                'class_teacher_id' => 4, // Mary Wanjiku
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($classes as $class) {
            DB::table('classes')->insert($class);
        }
    }

    private function createSubjects()
    {
        $this->command->info('Creating subjects...');
        
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'English', 'code' => 'ENG', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kiswahili', 'code' => 'KIS', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Physics', 'code' => 'PHY', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chemistry', 'code' => 'CHEM', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Biology', 'code' => 'BIO', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'History', 'code' => 'HIST', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Geography', 'code' => 'GEO', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Computer Studies', 'code' => 'COMP', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Business Studies', 'code' => 'BUS', 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($subjects as $subject) {
            DB::table('subjects')->insert($subject);
        }
    }

    private function createFees()
    {
        $this->command->info('Creating fees...');
        
        $fees = [
            [
                'name' => 'Tuition Fee',
                'description' => 'Termly tuition fee',
                'amount' => 25000.00,
                'type' => 'tuition',
                'frequency' => 'termly',
                'is_mandatory' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Library Fee',
                'description' => 'Library and resource fee',
                'amount' => 2000.00,
                'type' => 'library',
                'frequency' => 'termly',
                'is_mandatory' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Examination Fee',
                'description' => 'End of term examination fee',
                'amount' => 3000.00,
                'type' => 'examination',
                'frequency' => 'termly',
                'is_mandatory' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Transport Fee',
                'description' => 'School transport fee',
                'amount' => 8000.00,
                'type' => 'transport',
                'frequency' => 'termly',
                'is_mandatory' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sports Fee',
                'description' => 'Sports and games fee',
                'amount' => 1500.00,
                'type' => 'sports',
                'frequency' => 'termly',
                'is_mandatory' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Development Fee',
                'description' => 'School development fee',
                'amount' => 5000.00,
                'type' => 'development',
                'frequency' => 'annually',
                'is_mandatory' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($fees as $fee) {
            DB::table('fees')->insert($fee);
        }
    }

    private function createInvoices()
    {
        $this->command->info('Creating invoices...');
        
        $invoices = [];
        $students = DB::table('students')->get();
        $fees = DB::table('fees')->get();
        
        foreach ($students as $student) {
            // Create termly invoices
            $termlyFees = $fees->where('frequency', 'termly');
            $totalAmount = $termlyFees->sum('amount');
            
            $invoices[] = [
                'invoice_number' => 'INV-' . $student->admission_number . '-2024-T1',
                'student_id' => $student->id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'due_date' => now()->addDays(30),
                'term' => 'Term 1 2024',
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Create some paid invoices
            if (rand(0, 1)) {
                $invoices[] = [
                    'invoice_number' => 'INV-' . $student->admission_number . '-2023-T3',
                    'student_id' => $student->id,
                    'total_amount' => $totalAmount,
                    'status' => 'paid',
                    'due_date' => now()->subDays(30),
                    'term' => 'Term 3 2023',
                    'created_at' => now()->subDays(60),
                    'updated_at' => now()->subDays(30),
                ];
            }
        }
        
        foreach ($invoices as $invoice) {
            DB::table('invoices')->insert($invoice);
        }
    }

    private function createPayments()
    {
        $this->command->info('Creating payments...');
        
        $payments = [];
        $invoices = DB::table('invoices')->where('status', 'paid')->get();
        
        foreach ($invoices as $invoice) {
            $paymentMethods = ['mpesa', 'bank_transfer', 'cash', 'cheque'];
            $statuses = ['completed', 'pending', 'failed'];
            
            $payments[] = [
                'invoice_id' => $invoice->id,
                'amount' => $invoice->total_amount,
                'payment_date' => $invoice->updated_at,
                'method' => $paymentMethods[array_rand($paymentMethods)],
                'status' => 'completed',
                'reference' => 'REF-' . strtoupper(uniqid()),
                'mpesa_transaction_id' => $invoice->method === 'mpesa' ? 'MPESA-' . strtoupper(uniqid()) : null,
                'created_at' => $invoice->updated_at,
                'updated_at' => $invoice->updated_at,
            ];
        }
        
        // Create some pending payments
        $pendingInvoices = DB::table('invoices')->where('status', 'pending')->take(3)->get();
        foreach ($pendingInvoices as $invoice) {
            $payments[] = [
                'invoice_id' => $invoice->id,
                'amount' => $invoice->total_amount,
                'payment_date' => now(),
                'method' => 'mpesa',
                'status' => 'pending',
                'reference' => 'REF-' . strtoupper(uniqid()),
                'mpesa_transaction_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        foreach ($payments as $payment) {
            DB::table('payments')->insert($payment);
        }
    }

    private function createReceipts()
    {
        $this->command->info('Creating receipts...');
        
        $receipts = [];
        $payments = DB::table('payments')->where('status', 'completed')->get();
        
        foreach ($payments as $payment) {
            $receipts[] = [
                'receipt_number' => 'RCP-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'status' => 'issued',
                'printed_at' => $payment->payment_date,
                'created_at' => $payment->created_at,
                'updated_at' => $payment->updated_at,
            ];
        }
        
        foreach ($receipts as $receipt) {
            DB::table('receipts')->insert($receipt);
        }
    }

    private function createBankAccounts()
    {
        $this->command->info('Creating bank accounts...');
        
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
            [
                'name' => 'Development Fund Account',
                'account_number' => '1122334455',
                'bank_name' => 'Cooperative Bank',
                'branch' => 'Karen',
                'balance' => 950000.00,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($bankAccounts as $account) {
            DB::table('bank_accounts')->insert($account);
        }
    }

    private function createStaff()
    {
        $this->command->info('Creating staff...');
        
        $staff = [
            [
                'employee_id' => 'EMP-001',
                'first_name' => 'Mary',
                'last_name' => 'Wanjiku',
                'email' => 'mary.wanjiku@duncowebsolutions.co.ke',
                'phone' => '0711111111',
                'position' => 'Mathematics Teacher',
                'department' => 'Mathematics',
                'salary' => 45000.00,
                'hire_date' => '2023-01-15',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'employee_id' => 'EMP-002',
                'first_name' => 'Peter',
                'last_name' => 'Kiprop',
                'email' => 'peter.kiprop@duncowebsolutions.co.ke',
                'phone' => '0722222222',
                'position' => 'Physics Teacher',
                'department' => 'Sciences',
                'salary' => 42000.00,
                'hire_date' => '2023-02-01',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'employee_id' => 'EMP-003',
                'first_name' => 'Grace',
                'last_name' => 'Akinyi',
                'email' => 'grace.akinyi@duncowebsolutions.co.ke',
                'phone' => '0733333333',
                'position' => 'English Teacher',
                'department' => 'Languages',
                'salary' => 40000.00,
                'hire_date' => '2023-01-20',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($staff as $member) {
            DB::table('staff')->insert($member);
        }
    }

    private function createAcademicRecords()
    {
        $this->command->info('Creating academic records...');
        
        $students = DB::table('students')->get();
        $subjects = DB::table('subjects')->get();
        
        foreach ($students as $student) {
            foreach ($subjects as $subject) {
                DB::table('academic_records')->insert([
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'term' => 'Term 1 2024',
                    'year' => 2024,
                    'cat_marks' => rand(20, 40),
                    'exam_marks' => rand(30, 70),
                    'total_marks' => 0, // Will be calculated
                    'grade' => 'A', // Will be calculated
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function createAttendanceRecords()
    {
        $this->command->info('Creating attendance records...');
        
        $students = DB::table('students')->get();
        $startDate = now()->subDays(30);
        
        foreach ($students as $student) {
            for ($i = 0; $i < 30; $i++) {
                $date = $startDate->copy()->addDays($i);
                if ($date->isWeekday()) {
                    DB::table('attendance')->insert([
                        'student_id' => $student->id,
                        'date' => $date->format('Y-m-d'),
                        'status' => rand(0, 10) > 1 ? 'present' : 'absent',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function createNotifications()
    {
        $this->command->info('Creating notifications...');
        
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
                'title' => 'Parent-Teacher Meeting',
                'message' => 'Parent-teacher meeting scheduled for next Friday at 2:00 PM.',
                'type' => 'meeting',
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
