<?php

namespace Modules\Examination\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Examination\app\Models\ExamType;
use Modules\Examination\app\Models\QuestionCategory;
use Modules\Examination\app\Models\Question;
use Modules\Examination\app\Models\Exam;

class ExaminationSeeder extends Seeder
{
    public function run()
    {
        // Create Exam Types
        $examTypes = [
            ['name' => 'Midterm Examination', 'code' => 'MID', 'description' => 'Mid-semester examination', 'is_online' => false],
            ['name' => 'Final Examination', 'code' => 'FINAL', 'description' => 'End of semester examination', 'is_online' => false],
            ['name' => 'Continuous Assessment Test', 'code' => 'CAT', 'description' => 'Regular assessment tests', 'is_online' => true],
            ['name' => 'Online Quiz', 'code' => 'QUIZ', 'description' => 'Online interactive quizzes', 'is_online' => true],
            ['name' => 'Practical Examination', 'code' => 'PRAC', 'description' => 'Hands-on practical tests', 'is_online' => false],
            ['name' => 'Project Assessment', 'code' => 'PROJ', 'description' => 'Project-based evaluation', 'is_online' => false],
        ];

        foreach ($examTypes as $type) {
            ExamType::create($type);
        }

        // Create Question Categories
        $categories = [
            // Mathematics
            ['name' => 'Mathematics', 'code' => 'MATH', 'subject' => 'Mathematics', 'difficulty' => 'medium'],
            ['name' => 'Algebra', 'code' => 'MATH_ALG', 'subject' => 'Mathematics', 'topic' => 'Algebra', 'difficulty' => 'medium'],
            ['name' => 'Calculus', 'code' => 'MATH_CALC', 'subject' => 'Mathematics', 'topic' => 'Calculus', 'difficulty' => 'hard'],
            ['name' => 'Geometry', 'code' => 'MATH_GEO', 'subject' => 'Mathematics', 'topic' => 'Geometry', 'difficulty' => 'medium'],
            
            // Science
            ['name' => 'Physics', 'code' => 'PHYS', 'subject' => 'Physics', 'difficulty' => 'medium'],
            ['name' => 'Chemistry', 'code' => 'CHEM', 'subject' => 'Chemistry', 'difficulty' => 'medium'],
            ['name' => 'Biology', 'code' => 'BIO', 'subject' => 'Biology', 'difficulty' => 'medium'],
            
            // Computer Science
            ['name' => 'Computer Science', 'code' => 'CS', 'subject' => 'Computer Science', 'difficulty' => 'medium'],
            ['name' => 'Programming', 'code' => 'CS_PROG', 'subject' => 'Computer Science', 'topic' => 'Programming', 'difficulty' => 'medium'],
            ['name' => 'Data Structures', 'code' => 'CS_DS', 'subject' => 'Computer Science', 'topic' => 'Data Structures', 'difficulty' => 'hard'],
            
            // Languages
            ['name' => 'English', 'code' => 'ENG', 'subject' => 'English', 'difficulty' => 'medium'],
            ['name' => 'Literature', 'code' => 'ENG_LIT', 'subject' => 'English', 'topic' => 'Literature', 'difficulty' => 'medium'],
            ['name' => 'Grammar', 'code' => 'ENG_GRAM', 'subject' => 'English', 'topic' => 'Grammar', 'difficulty' => 'easy'],
        ];

        foreach ($categories as $category) {
            QuestionCategory::create($category);
        }

        // Create Sample Questions
        $questions = [
            // Mathematics Questions
            [
                'question_text' => 'What is the value of x in the equation 2x + 5 = 13?',
                'type' => 'mcq',
                'category_id' => QuestionCategory::where('code', 'MATH_ALG')->first()->id,
                'options' => ['3', '4', '5', '6'],
                'correct_answers' => ['4'],
                'marks' => 2,
                'explanation' => '2x + 5 = 13, 2x = 8, x = 4'
            ],
            [
                'question_text' => 'Find the derivative of f(x) = x² + 3x + 1',
                'type' => 'fill_blank',
                'category_id' => QuestionCategory::where('code', 'MATH_CALC')->first()->id,
                'correct_answers' => ['2x + 3', '2x+3'],
                'marks' => 3,
                'explanation' => 'The derivative of x² is 2x, derivative of 3x is 3, derivative of constant 1 is 0'
            ],
            [
                'question_text' => 'What is the area of a circle with radius 5 units?',
                'type' => 'mcq',
                'category_id' => QuestionCategory::where('code', 'MATH_GEO')->first()->id,
                'options' => ['25π', '50π', '75π', '100π'],
                'correct_answers' => ['25π'],
                'marks' => 2,
                'explanation' => 'Area = πr² = π(5)² = 25π'
            ],
            
            // Physics Questions
            [
                'question_text' => 'What is the SI unit of force?',
                'type' => 'mcq',
                'category_id' => QuestionCategory::where('code', 'PHYS')->first()->id,
                'options' => ['Joule', 'Newton', 'Watt', 'Pascal'],
                'correct_answers' => ['Newton'],
                'marks' => 1,
                'explanation' => 'Force is measured in Newtons (N)'
            ],
            [
                'question_text' => 'Explain the concept of Newton\'s First Law of Motion and provide examples.',
                'type' => 'essay',
                'category_id' => QuestionCategory::where('code', 'PHYS')->first()->id,
                'marks' => 5,
                'explanation' => 'Newton\'s First Law states that an object will remain at rest or in uniform motion unless acted upon by an external force.'
            ],
            
            // Computer Science Questions
            [
                'question_text' => 'Write a function to calculate the factorial of a number.',
                'type' => 'coding',
                'category_id' => QuestionCategory::where('code', 'CS_PROG')->first()->id,
                'marks' => 4,
                'explanation' => 'Use recursion or iteration to calculate factorial'
            ],
            [
                'question_text' => 'What is the time complexity of binary search?',
                'type' => 'mcq',
                'category_id' => QuestionCategory::where('code', 'CS_DS')->first()->id,
                'options' => ['O(1)', 'O(log n)', 'O(n)', 'O(n²)'],
                'correct_answers' => ['O(log n)'],
                'marks' => 2,
                'explanation' => 'Binary search divides the search space in half each time, resulting in logarithmic time complexity'
            ],
            
            // English Questions
            [
                'question_text' => 'Identify the part of speech for the word "quickly" in the sentence: "She quickly ran to the store."',
                'type' => 'mcq',
                'category_id' => QuestionCategory::where('code', 'ENG_GRAM')->first()->id,
                'options' => ['Noun', 'Verb', 'Adjective', 'Adverb'],
                'correct_answers' => ['Adverb'],
                'marks' => 1,
                'explanation' => '"Quickly" modifies the verb "ran", making it an adverb'
            ],
            [
                'question_text' => 'Analyze the theme of love in Shakespeare\'s Romeo and Juliet.',
                'type' => 'essay',
                'category_id' => QuestionCategory::where('code', 'ENG_LIT')->first()->id,
                'marks' => 5,
                'explanation' => 'Discuss how love is portrayed, its consequences, and its role in the tragedy'
            ],
            
            // Chemistry Questions
            [
                'question_text' => 'What is the chemical formula for water?',
                'type' => 'fill_blank',
                'category_id' => QuestionCategory::where('code', 'CHEM')->first()->id,
                'correct_answers' => ['H2O', 'H₂O'],
                'marks' => 1,
                'explanation' => 'Water consists of two hydrogen atoms and one oxygen atom'
            ],
            [
                'question_text' => 'Which of the following is a noble gas?',
                'type' => 'mcq',
                'category_id' => QuestionCategory::where('code', 'CHEM')->first()->id,
                'options' => ['Helium', 'Oxygen', 'Nitrogen', 'Carbon'],
                'correct_answers' => ['Helium'],
                'marks' => 1,
                'explanation' => 'Helium is a noble gas in Group 18 of the periodic table'
            ],
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }

        // Create Sample Exams
        $exams = [
            [
                'name' => 'Mathematics Midterm 2024',
                'code' => 'MATH_MID_2024',
                'description' => 'Comprehensive midterm examination covering algebra, calculus, and geometry',
                'exam_type_id' => ExamType::where('code', 'MID')->first()->id,
                'academic_year' => '2023-2024',
                'term' => 'Spring',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(7),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
                'duration_minutes' => 120,
                'total_marks' => 50,
                'passing_marks' => 25,
                'is_online' => true,
                'enable_proctoring' => true,
                'shuffle_questions' => true,
                'shuffle_options' => true,
                'show_results_immediately' => false,
                'allow_review' => true,
                'status' => 'published'
            ],
            [
                'name' => 'Computer Science Final 2024',
                'code' => 'CS_FINAL_2024',
                'description' => 'Final examination covering programming, data structures, and algorithms',
                'exam_type_id' => ExamType::where('code', 'FINAL')->first()->id,
                'academic_year' => '2023-2024',
                'term' => 'Spring',
                'start_date' => now()->addDays(14),
                'end_date' => now()->addDays(14),
                'start_time' => '14:00:00',
                'end_time' => '17:00:00',
                'duration_minutes' => 180,
                'total_marks' => 100,
                'passing_marks' => 50,
                'is_online' => true,
                'enable_proctoring' => true,
                'shuffle_questions' => false,
                'shuffle_options' => true,
                'show_results_immediately' => false,
                'allow_review' => true,
                'status' => 'draft'
            ],
            [
                'name' => 'English Literature Quiz',
                'code' => 'ENG_QUIZ_2024',
                'description' => 'Online quiz on Shakespeare and classic literature',
                'exam_type_id' => ExamType::where('code', 'QUIZ')->first()->id,
                'academic_year' => '2023-2024',
                'term' => 'Spring',
                'start_date' => now()->addDays(3),
                'end_date' => now()->addDays(3),
                'start_time' => '10:00:00',
                'end_time' => '11:00:00',
                'duration_minutes' => 60,
                'total_marks' => 20,
                'passing_marks' => 12,
                'is_online' => true,
                'enable_proctoring' => false,
                'shuffle_questions' => true,
                'shuffle_options' => true,
                'show_results_immediately' => true,
                'allow_review' => true,
                'status' => 'published'
            ],
        ];

        foreach ($exams as $exam) {
            Exam::create($exam);
        }

        // Attach questions to exams
        $mathExam = Exam::where('code', 'MATH_MID_2024')->first();
        $csExam = Exam::where('code', 'CS_FINAL_2024')->first();
        $engQuiz = Exam::where('code', 'ENG_QUIZ_2024')->first();

        // Math exam questions
        $mathQuestions = Question::whereHas('category', function($q) {
            $q->where('subject', 'Mathematics');
        })->take(5)->get();
        
        foreach ($mathQuestions as $index => $question) {
            $mathExam->questions()->attach($question->id, ['order' => $index + 1]);
        }

        // CS exam questions
        $csQuestions = Question::whereHas('category', function($q) {
            $q->where('subject', 'Computer Science');
        })->take(3)->get();
        
        foreach ($csQuestions as $index => $question) {
            $csExam->questions()->attach($question->id, ['order' => $index + 1]);
        }

        // English quiz questions
        $engQuestions = Question::whereHas('category', function($q) {
            $q->where('subject', 'English');
        })->take(2)->get();
        
        foreach ($engQuestions as $index => $question) {
            $engQuiz->questions()->attach($question->id, ['order' => $index + 1]);
        }

        $this->command->info('Examination module seeded successfully!');
    }
} 