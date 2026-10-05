<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\School;
use App\Models\Role;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\AcademicClass;
use Modules\Academic\Models\Subject;
use Modules\Academic\Models\Exam;
use Modules\Academic\Models\ExamResult;
use Modules\Finance\Models\Fee;
use Modules\Finance\Models\FeeCategory;
use Modules\Finance\Models\FeeType;
use Modules\Finance\Models\Payment;
use Modules\Finance\Models\Invoice;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $school = School::first();
        
        // Create demo students
        $students = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe.demo@student.com',
                'phone' => '+254700123456',
                'admission_number' => 'STU001',
                'date_of_birth' => '2005-03-15',
                'gender' => 'male',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith.demo@student.com',
                'phone' => '+254700123457',
                'admission_number' => 'STU002',
                'date_of_birth' => '2005-07-22',
                'gender' => 'female',
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike.johnson.demo@student.com',
                'phone' => '+254700123458',
                'admission_number' => 'STU003',
                'date_of_birth' => '2005-11-08',
                'gender' => 'male',
            ],
            [
                'name' => 'Sarah Wilson',
                'email' => 'sarah.wilson.demo@student.com',
                'phone' => '+254700123459',
                'admission_number' => 'STU004',
                'date_of_birth' => '2005-01-30',
                'gender' => 'female',
            ],
        ];

        // Create demo classes (only if they don't exist)
        $classes = [
            ['name' => 'Form 1A', 'code' => 'F1A', 'capacity' => 30],
            ['name' => 'Form 1B', 'code' => 'F1B', 'capacity' => 30],
            ['name' => 'Form 2A', 'code' => 'F2A', 'capacity' => 30],
            ['name' => 'Form 2B', 'code' => 'F2B', 'capacity' => 30],
        ];

        foreach ($classes as $classData) {
            AcademicClass::firstOrCreate(
                ['code' => $classData['code'], 'school_id' => $school->id],
                [
                    'name' => $classData['name'],
                    'capacity' => $classData['capacity'],
                    'academic_year' => '2024-2025',
                ]
            );
        }

        // Create demo subjects
        $subjects = [
            ['name' => 'Mathematics', 'code' => 'MATH'],
            ['name' => 'English', 'code' => 'ENG'],
            ['name' => 'Physics', 'code' => 'PHY'],
            ['name' => 'Chemistry', 'code' => 'CHEM'],
            ['name' => 'Biology', 'code' => 'BIO'],
            ['name' => 'History', 'code' => 'HIST'],
            ['name' => 'Geography', 'code' => 'GEO'],
            ['name' => 'Computer Studies', 'code' => 'COMP'],
        ];

        foreach ($subjects as $subjectData) {
            Subject::firstOrCreate(
                ['code' => $subjectData['code'], 'school_id' => $school->id],
                [
                    'name' => $subjectData['name'],
                    'credits' => 3,
                    'is_active' => true,
                ]
            );
        }

        // Create fee categories and types first
        $feeCategories = [
            ['name' => 'Tuition', 'description' => 'Academic tuition fees'],
            ['name' => 'Library', 'description' => 'Library and resource fees'],
            ['name' => 'Laboratory', 'description' => 'Science laboratory fees'],
            ['name' => 'Sports', 'description' => 'Sports and recreation fees'],
        ];

        foreach ($feeCategories as $categoryData) {
            FeeCategory::firstOrCreate(
                ['name' => $categoryData['name']],
                [
                    'description' => $categoryData['description'],
                ]
            );
        }

        $feeTypes = [
            ['name' => 'Termly', 'description' => 'Charged per term'],
            ['name' => 'Yearly', 'description' => 'Charged annually'],
            ['name' => 'One-time', 'description' => 'One-time payment'],
        ];

        foreach ($feeTypes as $typeData) {
            FeeType::firstOrCreate(
                ['name' => $typeData['name']],
                [
                    'description' => $typeData['description'],
                ]
            );
        }

        // Create demo students with users
        $studentRole = Role::where('name', 'student')->first();
        $class1A = AcademicClass::where('name', 'Form 1A')->first();
        $class1B = AcademicClass::where('name', 'Form 1B')->first();

        foreach ($students as $index => $studentData) {
            $user = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'password' => Hash::make('password'),
                    'phone' => $studentData['phone'],
                    'school_id' => $school->id,
                ]
            );

            if ($studentRole && !$user->roles()->where('role_id', $studentRole->id)->exists()) {
                $user->roles()->attach($studentRole->id);
            }

            $class = $index < 2 ? $class1A : $class1B;
            
            Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'student_id' => $user->id,
                    'school_id' => $school->id,
                    'class_id' => $class->id,
                    'admission_number' => $studentData['admission_number'],
                    'admission_date' => now()->subMonths(rand(1, 6)),
                    'date_of_birth' => $studentData['date_of_birth'],
                    'gender' => $studentData['gender'],
                    'name' => $studentData['name'],
                ]
            );
        }

        // Create demo fees using correct model structure
        $tuitionCategory = FeeCategory::where('name', 'Tuition')->first();
        $libraryCategory = FeeCategory::where('name', 'Library')->first();
        $labCategory = FeeCategory::where('name', 'Laboratory')->first();
        $sportsCategory = FeeCategory::where('name', 'Sports')->first();
        
        $termlyType = FeeType::where('name', 'Termly')->first();
        $yearlyType = FeeType::where('name', 'Yearly')->first();

        $fees = [
            [
                'name' => 'Tuition Fee',
                'amount' => 50000,
                'fee_category_id' => $tuitionCategory->id,
                'fee_type_id' => $termlyType->id,
                'description' => 'Academic tuition fee for the term'
            ],
            [
                'name' => 'Library Fee',
                'amount' => 5000,
                'fee_category_id' => $libraryCategory->id,
                'fee_type_id' => $yearlyType->id,
                'description' => 'Annual library membership fee'
            ],
            [
                'name' => 'Laboratory Fee',
                'amount' => 10000,
                'fee_category_id' => $labCategory->id,
                'fee_type_id' => $termlyType->id,
                'description' => 'Science laboratory usage fee'
            ],
            [
                'name' => 'Sports Fee',
                'amount' => 3000,
                'fee_category_id' => $sportsCategory->id,
                'fee_type_id' => $yearlyType->id,
                'description' => 'Sports and recreation facilities fee'
            ],
        ];

        foreach ($fees as $feeData) {
            Fee::firstOrCreate(
                ['name' => $feeData['name'], 'school_id' => $school->id],
                [
                    'amount' => $feeData['amount'],
                    'fee_category_id' => $feeData['fee_category_id'],
                    'fee_type_id' => $feeData['fee_type_id'],
                    'description' => $feeData['description'],
                ]
            );
        }

        // Create demo exams using correct model structure
        $exams = [
            [
                'name' => 'Mid-Term Mathematics',
                'code' => 'MATH-MID-001',
                'exam_type' => 'midterm',
                'academic_year' => '2024-2025',
                'term' => 'first',
                'start_date' => now()->addDays(rand(5, 30)),
                'end_date' => now()->addDays(rand(35, 60)),
                'duration_minutes' => 120,
                'total_marks' => 100,
                'passing_marks' => 40,
                'status' => 'published',
                'is_active' => true,
            ],
            [
                'name' => 'End of Term English',
                'code' => 'ENG-FINAL-001',
                'exam_type' => 'final',
                'academic_year' => '2024-2025',
                'term' => 'first',
                'start_date' => now()->addDays(rand(5, 30)),
                'end_date' => now()->addDays(rand(35, 60)),
                'duration_minutes' => 150,
                'total_marks' => 100,
                'passing_marks' => 40,
                'status' => 'published',
                'is_active' => true,
            ],
            [
                'name' => 'Physics Test',
                'code' => 'PHY-QUIZ-001',
                'exam_type' => 'quiz',
                'academic_year' => '2024-2025',
                'term' => 'first',
                'start_date' => now()->addDays(rand(5, 30)),
                'end_date' => now()->addDays(rand(35, 60)),
                'duration_minutes' => 60,
                'total_marks' => 50,
                'passing_marks' => 25,
                'status' => 'published',
                'is_active' => true,
            ],
            [
                'name' => 'Chemistry Final',
                'code' => 'CHEM-FINAL-001',
                'exam_type' => 'final',
                'academic_year' => '2024-2025',
                'term' => 'first',
                'start_date' => now()->addDays(rand(5, 30)),
                'end_date' => now()->addDays(rand(35, 60)),
                'duration_minutes' => 120,
                'total_marks' => 100,
                'passing_marks' => 40,
                'status' => 'published',
                'is_active' => true,
            ],
        ];

        foreach ($exams as $examData) {
            Exam::firstOrCreate(
                ['code' => $examData['code'], 'school_id' => $school->id],
                $examData
            );
        }

        // Create demo exam results
        $students = Student::all();
        $exams = Exam::all();
        $subjects = Subject::all();

        foreach ($students as $student) {
            foreach ($exams as $exam) {
                // Get a random subject for this exam
                $subject = $subjects->random();
                
                ExamResult::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'exam_id' => $exam->id,
                        'subject_id' => $subject->id,
                    ],
                    [
                        'school_id' => $school->id,
                        'class_id' => $student->class_id,
                        'marks_obtained' => rand(40, 95),
                        'total_marks' => $exam->total_marks,
                        'percentage' => rand(40, 95),
                        'remarks' => ['Excellent', 'Good', 'Average', 'Needs improvement'][rand(0, 3)],
                        'submitted_at' => now(),
                        'is_active' => true,
                    ]
                );
            }
        }

        $this->command->info('Demo data created successfully!');
        $this->command->info('Students: ' . Student::count());
        $this->command->info('Fees: ' . Fee::count());
        $this->command->info('Exams: ' . Exam::count());
        $this->command->info('Exam Results: ' . ExamResult::count());
        $this->command->info('');
        $this->command->info('Demo Student Login Credentials:');
        $this->command->info('- john.doe.demo@student.com / password');
        $this->command->info('- jane.smith.demo@student.com / password');
        $this->command->info('- mike.johnson.demo@student.com / password');
        $this->command->info('- sarah.wilson.demo@student.com / password');
    }
}
