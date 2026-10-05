<?php

namespace Modules\Examination\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Examination\Models\Exam;
use Modules\Examination\Models\ExamType;
use Modules\Examination\Models\QuestionCategory;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create exam type if it doesn't exist
        $examType = ExamType::firstOrCreate([
            'name' => 'Online Exam',
        ], [
            'description' => 'Online examination with proctoring',
            'code' => 'ONLINE',
        ]);

        // Create question category if it doesn't exist
        $category = QuestionCategory::firstOrCreate([
            'name' => 'General',
        ], [
            'description' => 'General questions',
            'code' => 'GEN',
            'is_active' => true,
        ]);

        // Create sample exams
        $exams = [
            [
                'name' => 'Mathematics Midterm Exam',
                'code' => 'MATH-MID-2024',
                'description' => 'Midterm examination for Mathematics course',
                'exam_type_id' => $examType->id,
                'academic_year' => '2024',
                'term' => 'Midterm',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(7),
                'start_time' => now()->addDays(7)->setTime(9, 0),
                'end_time' => now()->addDays(7)->setTime(11, 0),
                'duration_minutes' => 120,
                'total_marks' => 100,
                'passing_marks' => 50,
                'is_online' => true,
                'enable_proctoring' => true,
                'shuffle_questions' => true,
                'shuffle_options' => true,
                'show_results_immediately' => false,
                'allow_review' => true,
                'is_active' => true,
                'status' => 'published',
                'negative_marking' => false,
                'proctor_webcam' => true,
                'proctor_tab_switch' => true,
                'proctor_face_detection' => true,
                'proctor_idle_timeout' => 300,
                'allow_retake' => false,
                'max_attempts' => 1,
            ],
            [
                'name' => 'Physics Final Exam',
                'code' => 'PHYS-FINAL-2024',
                'description' => 'Final examination for Physics course',
                'exam_type_id' => $examType->id,
                'academic_year' => '2024',
                'term' => 'Final',
                'start_date' => now()->addDays(14),
                'end_date' => now()->addDays(14),
                'start_time' => now()->addDays(14)->setTime(14, 0),
                'end_time' => now()->addDays(14)->setTime(16, 0),
                'duration_minutes' => 120,
                'total_marks' => 100,
                'passing_marks' => 50,
                'is_online' => true,
                'enable_proctoring' => true,
                'shuffle_questions' => true,
                'shuffle_options' => true,
                'show_results_immediately' => false,
                'allow_review' => true,
                'is_active' => true,
                'status' => 'published',
                'negative_marking' => false,
                'proctor_webcam' => true,
                'proctor_tab_switch' => true,
                'proctor_face_detection' => true,
                'proctor_idle_timeout' => 300,
                'allow_retake' => false,
                'max_attempts' => 1,
            ],
            [
                'name' => 'Chemistry Quiz',
                'code' => 'CHEM-QUIZ-2024',
                'description' => 'Weekly quiz for Chemistry course',
                'exam_type_id' => $examType->id,
                'academic_year' => '2024',
                'term' => 'Quiz',
                'start_date' => now()->addDays(3),
                'end_date' => now()->addDays(3),
                'start_time' => now()->addDays(3)->setTime(10, 0),
                'end_time' => now()->addDays(3)->setTime(10, 30),
                'duration_minutes' => 30,
                'total_marks' => 50,
                'passing_marks' => 25,
                'is_online' => true,
                'enable_proctoring' => false,
                'shuffle_questions' => false,
                'shuffle_options' => true,
                'show_results_immediately' => true,
                'allow_review' => true,
                'is_active' => true,
                'status' => 'published',
                'negative_marking' => false,
                'proctor_webcam' => false,
                'proctor_tab_switch' => false,
                'proctor_face_detection' => false,
                'proctor_idle_timeout' => 300,
                'allow_retake' => true,
                'max_attempts' => 3,
            ],
        ];

        foreach ($exams as $examData) {
            Exam::create($examData);
        }
    }
}
