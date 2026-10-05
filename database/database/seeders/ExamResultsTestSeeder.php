<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\Exam;
use Modules\Academic\Models\ExamResult;
use Modules\Academic\Models\Subject;

class ExamResultsTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Dunco school
        $school = School::where('code', 'DIS001')->first();
        if (!$school) {
            $this->command->error('Dunco school not found. Please run FinalDocumentTestSeeder first.');
            return;
        }

        // Get test students
        $students = Student::where('school_id', $school->id)
            ->where('admission_number', 'LIKE', 'STU2024%')
            ->get();

        if ($students->isEmpty()) {
            $this->command->error('No test students found. Please run FinalDocumentTestSeeder first.');
            return;
        }

        // Get or create subjects
        $subjects = [
            'Mathematics' => 'MATH',
            'English' => 'ENG',
            'Science' => 'SCI',
            'History' => 'HIST',
            'Geography' => 'GEO'
        ];

        $subjectIds = [];
        foreach ($subjects as $name => $code) {
            $subject = Subject::firstOrCreate(
                ['code' => $code],
                [
                    'name' => $name,
                    'code' => $code,
                    'school_id' => $school->id,
                    'is_active' => true
                ]
            );
            $subjectIds[$name] = $subject->id;
        }

        // Get or create exams
        $exams = [
            'Mid-Term Exam 2024' => ['start' => '2024-06-15 09:00:00', 'end' => '2024-06-15 12:00:00'],
            'End of Term Exam 2024' => ['start' => '2024-08-20 09:00:00', 'end' => '2024-08-20 12:00:00'],
            'Final Exam 2024' => ['start' => '2024-11-30 09:00:00', 'end' => '2024-11-30 12:00:00']
        ];

        $examIds = [];
        foreach ($exams as $name => $dates) {
            $exam = Exam::firstOrCreate(
                ['name' => $name, 'school_id' => $school->id],
                [
                    'name' => $name,
                    'code' => strtoupper(str_replace(' ', '_', $name)),
                    'school_id' => $school->id,
                    'start_date' => $dates['start'],
                    'end_date' => $dates['end'],
                    'academic_year' => '2024',
                    'exam_type' => 'midterm',
                    'term' => 'first',
                    'is_active' => true,
                    'status' => 'completed'
                ]
            );
            $examIds[$name] = $exam->id;
        }

        // Create exam results for each student
        foreach ($students as $student) {
            foreach ($exams as $examName => $examDate) {
                foreach ($subjects as $subjectName => $subjectCode) {
                    // Generate random marks between 40 and 95
                    $marks = rand(40, 95);
                    $totalMarks = 100;
                    
                    ExamResult::firstOrCreate(
                        [
                            'student_id' => $student->id,
                            'exam_id' => $examIds[$examName],
                            'subject_id' => $subjectIds[$subjectName],
                            'class_id' => $student->class_id,
                            'school_id' => $school->id
                        ],
                        [
                            'student_id' => $student->id,
                            'exam_id' => $examIds[$examName],
                            'subject_id' => $subjectIds[$subjectName],
                            'class_id' => $student->class_id,
                            'school_id' => $school->id,
                            'marks_obtained' => $marks,
                            'total_marks' => $totalMarks,
                            'percentage' => ($marks / $totalMarks) * 100,
                            'grade' => $this->calculateGrade($marks),
                            'remarks' => $this->getRemarks($marks),
                            'submitted_at' => now(),
                            'is_active' => true
                        ]
                    );
                }
            }
        }

        $this->command->info('✅ Exam results test data created successfully!');
        $this->command->info('');
        $this->command->info('📊 Created:');
        $this->command->info('   • ' . count($subjects) . ' subjects');
        $this->command->info('   • ' . count($exams) . ' exams');
        $this->command->info('   • ' . ($students->count() * count($exams) * count($subjects)) . ' exam results');
        $this->command->info('');
        $this->command->info('🎯 You can now test:');
        $this->command->info('   • Result slip generation');
        $this->command->info('   • Result slip template preview');
        $this->command->info('   • Academic documents section');
    }

    private function calculateGrade($marks)
    {
        if ($marks >= 80) return 'A';
        if ($marks >= 70) return 'B';
        if ($marks >= 60) return 'C';
        if ($marks >= 50) return 'D';
        return 'E';
    }

    private function getRemarks($marks)
    {
        if ($marks >= 80) return 'Excellent';
        if ($marks >= 70) return 'Very Good';
        if ($marks >= 60) return 'Good';
        if ($marks >= 50) return 'Average';
        return 'Needs Improvement';
    }
}
