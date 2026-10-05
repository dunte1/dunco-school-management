<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AIAssistantController extends Controller
{
    use ApiResponse;

    /**
     * Get AI assistant response
     */
    public function getResponse(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000',
                'context' => 'nullable|string',
                'user_type' => 'nullable|string|in:student,parent,teacher,admin',
            ]);

            $user = $request->user();
            $message = $request->message;
            $context = $request->context ?? '';
            $userType = $request->user_type ?? $this->getUserType($user);

            // Process the message and get AI response
            $response = $this->processMessage($message, $context, $userType, $user);

            // Store conversation history
            $this->storeConversation($user->id, $message, $response, $userType);

            return $this->successResponse([
                'response' => $response['text'],
                'suggestions' => $response['suggestions'],
                'actions' => $response['actions'],
                'context' => $response['context'],
                'timestamp' => now()->toISOString(),
            ], 'AI response generated successfully');

        } catch (\Exception $e) {
            Log::error('AI Assistant error: ' . $e->getMessage());
            return $this->errorResponse('Failed to get AI response: ' . $e->getMessage());
        }
    }

    /**
     * Get quick actions
     */
    public function getQuickActions(Request $request)
    {
        try {
            $user = $request->user();
            $userType = $this->getUserType($user);

            $actions = $this->getUserSpecificActions($userType, $user);

            return $this->successResponse($actions, 'Quick actions retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get quick actions: ' . $e->getMessage());
        }
    }

    /**
     * Get conversation history
     */
    public function getConversationHistory(Request $request)
    {
        try {
            $user = $request->user();
            $limit = $request->get('limit', 20);
            $offset = $request->get('offset', 0);

            $conversations = DB::table('ai_conversations')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->offset($offset)
                ->get()
                ->map(function ($conv) {
                    return [
                        'id' => $conv->id,
                        'user_message' => $conv->user_message,
                        'ai_response' => $conv->ai_response,
                        'user_type' => $conv->user_type,
                        'context' => json_decode($conv->context, true),
                        'created_at' => $conv->created_at,
                    ];
                });

            return $this->successResponse([
                'conversations' => $conversations,
                'has_more' => $conversations->count() === $limit,
            ], 'Conversation history retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get conversation history: ' . $e->getMessage());
        }
    }

    /**
     * Get smart suggestions
     */
    public function getSmartSuggestions(Request $request)
    {
        try {
            $user = $request->user();
            $userType = $this->getUserType($user);
            $context = $request->get('context', '');

            $suggestions = $this->generateSmartSuggestions($user, $userType, $context);

            return $this->successResponse($suggestions, 'Smart suggestions generated successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get smart suggestions: ' . $e->getMessage());
        }
    }

    /**
     * Get AI insights
     */
    public function getInsights(Request $request)
    {
        try {
            $user = $request->user();
            $userType = $this->getUserType($user);
            $schoolId = $user->school_id ?? 1;

            $insights = $this->generateInsights($user, $userType, $schoolId);

            return $this->successResponse($insights, 'AI insights generated successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get AI insights: ' . $e->getMessage());
        }
    }

    /**
     * Process message and generate response
     */
    private function processMessage($message, $context, $userType, $user)
    {
        $message = strtolower(trim($message));
        
        // Check for greetings
        if ($this->isGreeting($message)) {
            return $this->handleGreeting($userType, $user);
        }

        // Check for attendance queries
        if ($this->isAttendanceQuery($message)) {
            return $this->handleAttendanceQuery($user, $userType);
        }

        // Check for fee queries
        if ($this->isFeeQuery($message)) {
            return $this->handleFeeQuery($user, $userType);
        }

        // Check for exam queries
        if ($this->isExamQuery($message)) {
            return $this->handleExamQuery($user, $userType);
        }

        // Check for assignment queries
        if ($this->isAssignmentQuery($message)) {
            return $this->handleAssignmentQuery($user, $userType);
        }

        // Check for general help
        if ($this->isHelpQuery($message)) {
            return $this->handleHelpQuery($userType);
        }

        // Default response
        return $this->handleDefaultQuery($message, $userType);
    }

    /**
     * Check if message is a greeting
     */
    private function isGreeting($message)
    {
        $greetings = ['hello', 'hi', 'hey', 'good morning', 'good afternoon', 'good evening', 'greetings'];
        return in_array($message, $greetings) || str_contains($message, 'hello') || str_contains($message, 'hi');
    }

    /**
     * Check if message is about attendance
     */
    private function isAttendanceQuery($message)
    {
        $attendanceKeywords = ['attendance', 'present', 'absent', 'late', 'attendance record', 'attendance status'];
        return $this->containsKeywords($message, $attendanceKeywords);
    }

    /**
     * Check if message is about fees
     */
    private function isFeeQuery($message)
    {
        $feeKeywords = ['fee', 'fees', 'payment', 'pay', 'outstanding', 'due', 'balance'];
        return $this->containsKeywords($message, $feeKeywords);
    }

    /**
     * Check if message is about exams
     */
    private function isExamQuery($message)
    {
        $examKeywords = ['exam', 'exams', 'test', 'tests', 'result', 'results', 'grade', 'grades'];
        return $this->containsKeywords($message, $examKeywords);
    }

    /**
     * Check if message is about assignments
     */
    private function isAssignmentQuery($message)
    {
        $assignmentKeywords = ['assignment', 'assignments', 'homework', 'project', 'due', 'submit'];
        return $this->containsKeywords($message, $assignmentKeywords);
    }

    /**
     * Check if message is a help request
     */
    private function isHelpQuery($message)
    {
        $helpKeywords = ['help', 'support', 'how to', 'what is', 'how do i', 'can you help'];
        return $this->containsKeywords($message, $helpKeywords);
    }

    /**
     * Check if message contains any of the keywords
     */
    private function containsKeywords($message, $keywords)
    {
        foreach ($keywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Handle greeting
     */
    private function handleGreeting($userType, $user)
    {
        $greetings = [
            'student' => "Hello! I'm your AI assistant. How can I help you with your studies today?",
            'parent' => "Hello! I'm here to help you stay updated about your child's progress. What would you like to know?",
            'teacher' => "Hello! I'm your AI teaching assistant. How can I help you with your classes today?",
            'admin' => "Hello! I'm your AI administrative assistant. How can I help you manage the school today?",
        ];

        $suggestions = $this->getUserSpecificSuggestions($userType);

        return [
            'text' => $greetings[$userType] ?? "Hello! How can I help you today?",
            'suggestions' => $suggestions,
            'actions' => $this->getUserSpecificActions($userType, $user),
            'context' => 'greeting',
        ];
    }

    /**
     * Handle attendance query
     */
    private function handleAttendanceQuery($user, $userType)
    {
        try {
            $attendanceData = $this->getAttendanceData($user, $userType);
            
            $response = "Here's your attendance information:\n\n";
            $response .= "• This week: {$attendanceData['week_percentage']}%\n";
            $response .= "• This month: {$attendanceData['month_percentage']}%\n";
            $response .= "• Total days: {$attendanceData['total_days']}\n";
            $response .= "• Present days: {$attendanceData['present_days']}\n";

            if ($attendanceData['week_percentage'] < 75) {
                $response .= "\n⚠️ Your attendance is below 75%. Please try to attend more classes.";
            }

            return [
                'text' => $response,
                'suggestions' => ['Check monthly attendance', 'View attendance history', 'Contact teacher'],
                'actions' => [
                    ['type' => 'view_attendance', 'label' => 'View Detailed Attendance'],
                    ['type' => 'contact_teacher', 'label' => 'Contact Teacher'],
                ],
                'context' => 'attendance',
            ];
        } catch (\Exception $e) {
            return [
                'text' => "I'm having trouble retrieving your attendance data. Please try again later.",
                'suggestions' => ['Try again', 'Contact support'],
                'actions' => [],
                'context' => 'error',
            ];
        }
    }

    /**
     * Handle fee query
     */
    private function handleFeeQuery($user, $userType)
    {
        try {
            $feeData = $this->getFeeData($user, $userType);
            
            $response = "Here's your fee information:\n\n";
            $response .= "• Outstanding: KSh {$feeData['outstanding']}\n";
            $response .= "• Paid: KSh {$feeData['paid']}\n";
            $response .= "• Total: KSh {$feeData['total']}\n";
            $response .= "• Payment status: {$feeData['status']}\n";

            if ($feeData['outstanding'] > 0) {
                $response .= "\n💳 You have outstanding fees. Would you like to make a payment?";
            }

            return [
                'text' => $response,
                'suggestions' => ['Make payment', 'View fee history', 'Download receipt'],
                'actions' => [
                    ['type' => 'make_payment', 'label' => 'Make Payment'],
                    ['type' => 'view_fees', 'label' => 'View Fee Details'],
                ],
                'context' => 'fees',
            ];
        } catch (\Exception $e) {
            return [
                'text' => "I'm having trouble retrieving your fee information. Please try again later.",
                'suggestions' => ['Try again', 'Contact support'],
                'actions' => [],
                'context' => 'error',
            ];
        }
    }

    /**
     * Handle exam query
     */
    private function handleExamQuery($user, $userType)
    {
        try {
            $examData = $this->getExamData($user, $userType);
            
            $response = "Here's your exam information:\n\n";
            $response .= "• Upcoming exams: {$examData['upcoming_count']}\n";
            $response .= "• Recent results: {$examData['recent_results_count']}\n";

            if ($examData['upcoming_count'] > 0) {
                $response .= "\n📚 You have upcoming exams. Make sure to prepare well!";
            }

            return [
                'text' => $response,
                'suggestions' => ['View exam schedule', 'Check results', 'Study materials'],
                'actions' => [
                    ['type' => 'view_exams', 'label' => 'View Exam Schedule'],
                    ['type' => 'view_results', 'label' => 'View Results'],
                ],
                'context' => 'exams',
            ];
        } catch (\Exception $e) {
            return [
                'text' => "I'm having trouble retrieving your exam information. Please try again later.",
                'suggestions' => ['Try again', 'Contact support'],
                'actions' => [],
                'context' => 'error',
            ];
        }
    }

    /**
     * Handle assignment query
     */
    private function handleAssignmentQuery($user, $userType)
    {
        try {
            $assignmentData = $this->getAssignmentData($user, $userType);
            
            $response = "Here's your assignment information:\n\n";
            $response .= "• Pending assignments: {$assignmentData['pending_count']}\n";
            $response .= "• Completed assignments: {$assignmentData['completed_count']}\n";

            if ($assignmentData['pending_count'] > 0) {
                $response .= "\n📝 You have pending assignments. Don't forget to submit them on time!";
            }

            return [
                'text' => $response,
                'suggestions' => ['View assignments', 'Submit assignment', 'Ask for help'],
                'actions' => [
                    ['type' => 'view_assignments', 'label' => 'View Assignments'],
                    ['type' => 'submit_assignment', 'label' => 'Submit Assignment'],
                ],
                'context' => 'assignments',
            ];
        } catch (\Exception $e) {
            return [
                'text' => "I'm having trouble retrieving your assignment information. Please try again later.",
                'suggestions' => ['Try again', 'Contact support'],
                'actions' => [],
                'context' => 'error',
            ];
        }
    }

    /**
     * Handle help query
     */
    private function handleHelpQuery($userType)
    {
        $helpText = "I can help you with:\n\n";
        
        switch ($userType) {
            case 'student':
                $helpText .= "• Check attendance records\n";
                $helpText .= "• View fee information\n";
                $helpText .= "• Check exam schedules and results\n";
                $helpText .= "• View assignments and homework\n";
                $helpText .= "• Get study tips and reminders\n";
                break;
            case 'parent':
                $helpText .= "• Monitor child's attendance\n";
                $helpText .= "• Check fee payments\n";
                $helpText .= "• View academic progress\n";
                $helpText .= "• Communicate with teachers\n";
                break;
            case 'teacher':
                $helpText .= "• Manage class attendance\n";
                $helpText .= "• Grade assignments and exams\n";
                $helpText .= "• Communicate with students and parents\n";
                $helpText .= "• View class schedules\n";
                break;
            case 'admin':
                $helpText .= "• Monitor school statistics\n";
                $helpText .= "• Manage user accounts\n";
                $helpText .= "• Generate reports\n";
                $helpText .= "• System administration\n";
                break;
        }

        return [
            'text' => $helpText,
            'suggestions' => ['Ask specific question', 'View quick actions', 'Contact support'],
            'actions' => $this->getUserSpecificActions($userType, null),
            'context' => 'help',
        ];
    }

    /**
     * Handle default query
     */
    private function handleDefaultQuery($message, $userType)
    {
        return [
            'text' => "I understand you're asking about '{$message}'. Could you be more specific? I can help you with attendance, fees, exams, assignments, and more.",
            'suggestions' => ['Ask about attendance', 'Check fees', 'View exams', 'See assignments'],
            'actions' => [],
            'context' => 'general',
        ];
    }

    /**
     * Get user type
     */
    private function getUserType($user)
    {
        $roles = $user->roles->pluck('name')->toArray();
        
        if (in_array('student', $roles)) return 'student';
        if (in_array('parent', $roles) || in_array('guardian', $roles)) return 'parent';
        if (in_array('teacher', $roles)) return 'teacher';
        if (in_array('admin', $roles) || in_array('super_admin', $roles)) return 'admin';
        
        return 'user';
    }

    /**
     * Get user-specific suggestions
     */
    private function getUserSpecificSuggestions($userType)
    {
        $suggestions = [
            'student' => ['Check my attendance', 'View my fees', 'See upcoming exams', 'Check assignments'],
            'parent' => ['Check child attendance', 'View fee payments', 'See academic progress', 'Contact teacher'],
            'teacher' => ['Mark attendance', 'Grade assignments', 'View class schedule', 'Send notifications'],
            'admin' => ['View school stats', 'Manage users', 'Generate reports', 'System status'],
        ];

        return $suggestions[$userType] ?? ['How can I help?', 'What would you like to know?'];
    }

    /**
     * Get user-specific actions
     */
    private function getUserSpecificActions($userType, $user)
    {
        $actions = [
            'student' => [
                ['type' => 'view_attendance', 'label' => 'View Attendance', 'icon' => 'attendance'],
                ['type' => 'view_fees', 'label' => 'View Fees', 'icon' => 'fees'],
                ['type' => 'view_exams', 'label' => 'View Exams', 'icon' => 'exams'],
                ['type' => 'view_assignments', 'label' => 'View Assignments', 'icon' => 'assignments'],
            ],
            'parent' => [
                ['type' => 'view_child_progress', 'label' => 'View Child Progress', 'icon' => 'progress'],
                ['type' => 'view_fee_payments', 'label' => 'View Fee Payments', 'icon' => 'payments'],
                ['type' => 'contact_teacher', 'label' => 'Contact Teacher', 'icon' => 'message'],
                ['type' => 'view_notifications', 'label' => 'View Notifications', 'icon' => 'notifications'],
            ],
            'teacher' => [
                ['type' => 'mark_attendance', 'label' => 'Mark Attendance', 'icon' => 'attendance'],
                ['type' => 'grade_assignments', 'label' => 'Grade Assignments', 'icon' => 'grading'],
                ['type' => 'view_schedule', 'label' => 'View Schedule', 'icon' => 'schedule'],
                ['type' => 'send_notification', 'label' => 'Send Notification', 'icon' => 'notification'],
            ],
            'admin' => [
                ['type' => 'view_dashboard', 'label' => 'View Dashboard', 'icon' => 'dashboard'],
                ['type' => 'manage_users', 'label' => 'Manage Users', 'icon' => 'users'],
                ['type' => 'generate_reports', 'label' => 'Generate Reports', 'icon' => 'reports'],
                ['type' => 'system_settings', 'label' => 'System Settings', 'icon' => 'settings'],
            ],
        ];

        return $actions[$userType] ?? [];
    }

    /**
     * Get attendance data
     */
    private function getAttendanceData($user, $userType)
    {
        try {
            // This would integrate with actual attendance data
            return [
                'week_percentage' => 85,
                'month_percentage' => 78,
                'total_days' => 20,
                'present_days' => 17,
            ];
        } catch (\Exception $e) {
            return [
                'week_percentage' => 0,
                'month_percentage' => 0,
                'total_days' => 0,
                'present_days' => 0,
            ];
        }
    }

    /**
     * Get fee data
     */
    private function getFeeData($user, $userType)
    {
        try {
            // This would integrate with actual fee data
            return [
                'outstanding' => 15000,
                'paid' => 35000,
                'total' => 50000,
                'status' => 'Partially Paid',
            ];
        } catch (\Exception $e) {
            return [
                'outstanding' => 0,
                'paid' => 0,
                'total' => 0,
                'status' => 'Unknown',
            ];
        }
    }

    /**
     * Get exam data
     */
    private function getExamData($user, $userType)
    {
        try {
            // This would integrate with actual exam data
            return [
                'upcoming_count' => 3,
                'recent_results_count' => 5,
            ];
        } catch (\Exception $e) {
            return [
                'upcoming_count' => 0,
                'recent_results_count' => 0,
            ];
        }
    }

    /**
     * Get assignment data
     */
    private function getAssignmentData($user, $userType)
    {
        try {
            // This would integrate with actual assignment data
            return [
                'pending_count' => 2,
                'completed_count' => 8,
            ];
        } catch (\Exception $e) {
            return [
                'pending_count' => 0,
                'completed_count' => 0,
            ];
        }
    }

    /**
     * Generate smart suggestions
     */
    private function generateSmartSuggestions($user, $userType, $context)
    {
        $suggestions = [];

        // Add context-based suggestions
        if (str_contains($context, 'attendance')) {
            $suggestions[] = 'Check your attendance for this week';
            $suggestions[] = 'View attendance history';
        }

        if (str_contains($context, 'fees')) {
            $suggestions[] = 'Make a payment';
            $suggestions[] = 'Download fee receipt';
        }

        if (str_contains($context, 'exam')) {
            $suggestions[] = 'View exam schedule';
            $suggestions[] = 'Check exam results';
        }

        // Add user-specific suggestions
        $suggestions = array_merge($suggestions, $this->getUserSpecificSuggestions($userType));

        return array_unique($suggestions);
    }

    /**
     * Generate insights
     */
    private function generateInsights($user, $userType, $schoolId)
    {
        $insights = [];

        switch ($userType) {
            case 'student':
                $insights = [
                    'Your attendance is improving this month',
                    'You have 2 assignments due this week',
                    'Your next exam is in 3 days',
                    'Consider reviewing your study schedule',
                ];
                break;
            case 'parent':
                $insights = [
                    'Your child\'s attendance is good this month',
                    'Fee payment is due in 5 days',
                    'Parent-teacher meeting scheduled next week',
                    'Your child has improved in Mathematics',
                ];
                break;
            case 'teacher':
                $insights = [
                    'Class attendance is 85% today',
                    '5 assignments need grading',
                    'Parent meeting scheduled for tomorrow',
                    'Student performance is improving',
                ];
                break;
            case 'admin':
                $insights = [
                    'School attendance is 78% today',
                    'Fee collection is 65% this month',
                    '3 new students registered this week',
                    'System performance is optimal',
                ];
                break;
        }

        return $insights;
    }

    /**
     * Store conversation
     */
    private function storeConversation($userId, $userMessage, $aiResponse, $userType)
    {
        try {
            DB::table('ai_conversations')->insert([
                'id' => Str::uuid(),
                'user_id' => $userId,
                'user_message' => $userMessage,
                'ai_response' => $aiResponse['text'],
                'user_type' => $userType,
                'context' => json_encode($aiResponse['context']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store conversation: ' . $e->getMessage());
        }
    }
}