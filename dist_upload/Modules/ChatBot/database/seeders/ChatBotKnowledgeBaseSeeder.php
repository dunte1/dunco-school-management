<?php

namespace Modules\ChatBot\database\seeders;

use Illuminate\Database\Seeder;
use Modules\ChatBot\app\Models\ChatBotKnowledgeBase;

class ChatBotKnowledgeBaseSeeder extends Seeder
{
    public function run()
    {
        $knowledgeBase = [
            // Academic Category
            [
                'category' => 'academic',
                'intent' => 'check_grades',
                'patterns' => [
                    'check my grades',
                    'view my grades',
                    'my grades',
                    'grade report',
                    'academic performance',
                    'how am i doing in school',
                    'show my marks'
                ],
                'responses' => [
                    "I can help you check your grades, {user_name}! You can view your current grades in the Academic module under 'My Grades'. Would you like me to show you how to access your grade report?",
                    "Your grades are available in the Academic section. I can guide you to the grade portal where you can see all your subjects and performance. Would you like me to help you navigate there?"
                ],
                'user_type' => 'student',
                'is_premium' => false
            ],
            [
                'category' => 'academic',
                'intent' => 'view_schedule',
                'patterns' => [
                    'view my schedule',
                    'my schedule',
                    'class schedule',
                    'timetable',
                    'when are my classes',
                    'what classes do i have today',
                    'show my timetable'
                ],
                'responses' => [
                    "Your class schedule is available in the Timetable module. You can see your daily classes, room numbers, and teacher information. Would you like me to help you access your schedule?",
                    "I can help you find your schedule! Check the Timetable section to see your classes, including times, rooms, and teachers. Need help navigating there?"
                ],
                'user_type' => 'student',
                'is_premium' => false
            ],
            [
                'category' => 'academic',
                'intent' => 'homework_help',
                'patterns' => [
                    'homework help',
                    'help with homework',
                    'study materials',
                    'find study materials',
                    'assignment help',
                    'need help with assignment',
                    'how to study'
                ],
                'responses' => [
                    "I'd be happy to help you with your homework! You can find study materials in the Library module, and many teachers provide additional resources. What subject are you working on?",
                    "For homework help, check the Library section for study materials and resources. You can also contact your teachers directly through the Communication module. What specific help do you need?"
                ],
                'user_type' => 'student',
                'is_premium' => true
            ],
            [
                'category' => 'academic',
                'intent' => 'exam_schedule',
                'patterns' => [
                    'exam schedule',
                    'when are exams',
                    'test dates',
                    'examination dates',
                    'final exam schedule',
                    'midterm dates',
                    'exam timetable'
                ],
                'responses' => [
                    "Exam schedules are posted in the Academic module under 'Examinations'. You'll find dates, times, and room assignments for all your upcoming tests. Would you like me to help you locate this information?",
                    "Your exam schedule is available in the Academic section. I can help you find the dates and times for your upcoming examinations. Need assistance accessing this information?"
                ],
                'user_type' => 'student',
                'is_premium' => false
            ],

            // Financial Category
            [
                'category' => 'financial',
                'intent' => 'check_fee_balance',
                'patterns' => [
                    'check fee balance',
                    'my fee balance',
                    'outstanding fees',
                    'fee payment',
                    'how much do i owe',
                    'payment balance',
                    'fee status'
                ],
                'responses' => [
                    "You can check your fee balance in the Finance module under 'My Fees'. This will show your current balance, due dates, and payment history. Would you like me to guide you there?",
                    "Your fee balance is available in the Finance section. I can help you navigate to see your current balance and payment options. Need help accessing this information?"
                ],
                'user_type' => 'all',
                'is_premium' => false
            ],
            [
                'category' => 'financial',
                'intent' => 'payment_methods',
                'patterns' => [
                    'payment methods',
                    'how to pay fees',
                    'fee payment options',
                    'online payment',
                    'bank transfer',
                    'payment gateway',
                    'how do i pay'
                ],
                'responses' => [
                    "We accept various payment methods including online payments, bank transfers, and cash payments. You can find all payment options in the Finance module under 'Payment Methods'. Would you like me to show you the available options?",
                    "Payment methods are available in the Finance section. We support online payments, bank transfers, and other convenient options. Need help understanding the payment process?"
                ],
                'user_type' => 'all',
                'is_premium' => false
            ],
            [
                'category' => 'financial',
                'intent' => 'scholarship_info',
                'patterns' => [
                    'scholarship info',
                    'scholarship information',
                    'financial aid',
                    'scholarship application',
                    'grants',
                    'financial assistance',
                    'scholarship eligibility'
                ],
                'responses' => [
                    "Scholarship information is available in the Finance module under 'Scholarships & Financial Aid'. You can find eligibility criteria, application forms, and deadlines. Would you like me to help you access this information?",
                    "I can help you find scholarship information! Check the Finance section for available scholarships, eligibility requirements, and application procedures. Need assistance with the application process?"
                ],
                'user_type' => 'student',
                'is_premium' => true
            ],

            // Administrative Category
            [
                'category' => 'administrative',
                'intent' => 'attendance_status',
                'patterns' => [
                    'attendance status',
                    'my attendance',
                    'attendance record',
                    'how many days absent',
                    'attendance percentage',
                    'attendance report'
                ],
                'responses' => [
                    "Your attendance status is available in the Attendance module. You can view your attendance percentage, absences, and attendance history. Would you like me to help you access this information?",
                    "I can help you check your attendance! Visit the Attendance section to see your attendance record and percentage. Need help navigating there?"
                ],
                'user_type' => 'student',
                'is_premium' => false
            ],
            [
                'category' => 'administrative',
                'intent' => 'transport_info',
                'patterns' => [
                    'transport info',
                    'bus schedule',
                    'transportation',
                    'bus route',
                    'transport details',
                    'school bus',
                    'transportation schedule'
                ],
                'responses' => [
                    "Transport information is available in the Transport module. You can find bus schedules, routes, and pickup/drop-off times. Would you like me to help you access this information?",
                    "I can help you with transport information! Check the Transport section for bus schedules, routes, and other transportation details. Need help finding your route?"
                ],
                'user_type' => 'all',
                'is_premium' => false
            ],
            [
                'category' => 'administrative',
                'intent' => 'cafeteria_menu',
                'patterns' => [
                    'cafeteria menu',
                    'lunch menu',
                    'food menu',
                    'what\'s for lunch',
                    'cafeteria food',
                    'meal options',
                    'school lunch'
                ],
                'responses' => [
                    "The cafeteria menu is available in the Administrative section. You can view daily menus, nutritional information, and meal options. Would you like me to help you access this information?",
                    "I can show you the cafeteria menu! Check the Administrative section for daily meal options and nutritional information. Need help finding the menu?"
                ],
                'user_type' => 'all',
                'is_premium' => true
            ],

            // General Category
            [
                'category' => 'general',
                'intent' => 'greeting',
                'patterns' => [
                    'hello',
                    'hi',
                    'hey',
                    'good morning',
                    'good afternoon',
                    'good evening',
                    'how are you'
                ],
                'responses' => [
                    "Hello {user_name}! I'm your AI school assistant. How can I help you today? I can assist with academic, financial, and administrative questions.",
                    "Hi there! I'm here to help you with school-related questions. What would you like to know about today?"
                ],
                'user_type' => 'all',
                'is_premium' => false
            ],
            [
                'category' => 'general',
                'intent' => 'help',
                'patterns' => [
                    'help',
                    'what can you help me with',
                    'what can you do',
                    'how can you help',
                    'assistance',
                    'support'
                ],
                'responses' => [
                    "I can help you with many things! Here are some areas I can assist with:\n\n🎓 Academic: Grades, schedules, homework help, exam dates\n💰 Financial: Fee balance, payments, scholarships\n🏠 Administrative: Attendance, transport, cafeteria, events\n\nWhat would you like to know about?",
                    "I'm your comprehensive school assistant! I can help with:\n\n• Academic information and support\n• Financial matters and payments\n• Administrative tasks and information\n• General school policies and procedures\n\nJust ask me anything!"
                ],
                'user_type' => 'all',
                'is_premium' => false
            ],
            [
                'category' => 'general',
                'intent' => 'contact_support',
                'patterns' => [
                    'contact support',
                    'help desk',
                    'technical support',
                    'contact admin',
                    'get help',
                    'support team',
                    'contact someone'
                ],
                'responses' => [
                    "For technical support or urgent matters, you can contact the school administration through the Communication module. You can also reach out to your teachers directly. Would you like me to help you find the contact information?",
                    "I can help you contact support! Use the Communication module to reach teachers or administrators. For technical issues, there's also a dedicated support team. Need help finding the right contact?"
                ],
                'user_type' => 'all',
                'is_premium' => false
            ],
            [
                'category' => 'general',
                'intent' => 'school_policies',
                'patterns' => [
                    'school policies',
                    'school rules',
                    'policy information',
                    'school regulations',
                    'code of conduct',
                    'school guidelines'
                ],
                'responses' => [
                    "School policies and guidelines are available in the Settings module under 'School Policies'. You can find information about conduct, dress code, academic policies, and more. Would you like me to help you access this information?",
                    "I can help you find school policies! Check the Settings section for comprehensive information about school rules, conduct policies, and guidelines. Need help navigating there?"
                ],
                'user_type' => 'all',
                'is_premium' => false
            ]
        ];

        foreach ($knowledgeBase as $item) {
            ChatBotKnowledgeBase::create($item);
        }
    }
} 