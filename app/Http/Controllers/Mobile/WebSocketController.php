<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Pusher\Pusher;

class WebSocketController extends Controller
{
    use ApiResponse;

    private $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => true
            ]
        );
    }

    /**
     * Get WebSocket connection info
     */
    public function getConnectionInfo(Request $request)
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;

            $channels = $this->getUserChannels($user, $schoolId);
            $authToken = $this->generateAuthToken($user);

            return $this->successResponse([
                'pusher_key' => config('broadcasting.connections.pusher.key'),
                'pusher_cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'channels' => $channels,
                'auth_token' => $authToken,
                'user_id' => $user->id,
                'school_id' => $schoolId,
            ], 'WebSocket connection info retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get WebSocket connection info: ' . $e->getMessage());
        }
    }

    /**
     * Send real-time notification
     */
    public function sendNotification(Request $request)
    {
        try {
            $request->validate([
                'channel' => 'required|string',
                'event' => 'required|string',
                'message' => 'required|string',
                'data' => 'nullable|array',
                'target_users' => 'nullable|array',
            ]);

            $user = $request->user();
            $channel = $request->channel;
            $event = $request->event;
            $message = $request->message;
            $data = $request->data ?? [];
            $targetUsers = $request->target_users ?? [];

            // Prepare notification data
            $notificationData = [
                'id' => Str::uuid(),
                'message' => $message,
                'data' => $data,
                'sender_id' => $user->id,
                'sender_name' => $user->name,
                'timestamp' => now()->toISOString(),
                'target_users' => $targetUsers,
            ];

            // Send via Pusher
            $this->pusher->trigger($channel, $event, $notificationData);

            // Store in database for persistence
            $this->storeNotification($notificationData, $channel, $event);

            return $this->successResponse([
                'notification_id' => $notificationData['id'],
                'channel' => $channel,
                'event' => $event,
                'message' => $message,
            ], 'Notification sent successfully');

        } catch (\Exception $e) {
            Log::error('Failed to send notification: ' . $e->getMessage());
            return $this->errorResponse('Failed to send notification: ' . $e->getMessage());
        }
    }

    /**
     * Get real-time attendance updates
     */
    public function getAttendanceUpdates(Request $request)
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;
            $date = $request->get('date', now()->toDateString());

            $attendanceData = $this->getAttendanceData($schoolId, $date);

            // Send real-time update
            $this->pusher->trigger("school.{$schoolId}.attendance", 'attendance_updated', [
                'date' => $date,
                'data' => $attendanceData,
                'timestamp' => now()->toISOString(),
            ]);

            return $this->successResponse($attendanceData, 'Attendance updates sent successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get attendance updates: ' . $e->getMessage());
        }
    }

    /**
     * Get real-time fee updates
     */
    public function getFeeUpdates(Request $request)
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;

            $feeData = $this->getFeeData($schoolId);

            // Send real-time update
            $this->pusher->trigger("school.{$schoolId}.fees", 'fees_updated', [
                'data' => $feeData,
                'timestamp' => now()->toISOString(),
            ]);

            return $this->successResponse($feeData, 'Fee updates sent successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get fee updates: ' . $e->getMessage());
        }
    }

    /**
     * Get real-time exam updates
     */
    public function getExamUpdates(Request $request)
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;

            $examData = $this->getExamData($schoolId);

            // Send real-time update
            $this->pusher->trigger("school.{$schoolId}.exams", 'exams_updated', [
                'data' => $examData,
                'timestamp' => now()->toISOString(),
            ]);

            return $this->successResponse($examData, 'Exam updates sent successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get exam updates: ' . $e->getMessage());
        }
    }

    /**
     * Get real-time assignment updates
     */
    public function getAssignmentUpdates(Request $request)
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;

            $assignmentData = $this->getAssignmentData($schoolId, $user->id);

            // Send real-time update
            $this->pusher->trigger("school.{$schoolId}.assignments", 'assignments_updated', [
                'data' => $assignmentData,
                'timestamp' => now()->toISOString(),
            ]);

            return $this->successResponse($assignmentData, 'Assignment updates sent successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get assignment updates: ' . $e->getMessage());
        }
    }

    /**
     * Get real-time chat messages
     */
    public function getChatMessages(Request $request)
    {
        try {
            $request->validate([
                'chat_id' => 'required|integer',
                'limit' => 'nullable|integer|min:1|max:100',
            ]);

            $user = $request->user();
            $chatId = $request->chat_id;
            $limit = $request->get('limit', 50);

            $messages = $this->getChatMessagesData($chatId, $user->id, $limit);

            return $this->successResponse($messages, 'Chat messages retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get chat messages: ' . $e->getMessage());
        }
    }

    /**
     * Send chat message
     */
    public function sendChatMessage(Request $request)
    {
        try {
            $request->validate([
                'chat_id' => 'required|integer',
                'message' => 'required|string|max:1000',
                'message_type' => 'nullable|string|in:text,image,file',
            ]);

            $user = $request->user();
            $chatId = $request->chat_id;
            $message = $request->message;
            $messageType = $request->get('message_type', 'text');

            $messageData = [
                'id' => Str::uuid(),
                'chat_id' => $chatId,
                'user_id' => $user->id,
                'user_name' => $user->name,
                'message' => $message,
                'message_type' => $messageType,
                'timestamp' => now()->toISOString(),
            ];

            // Store message in database
            $this->storeChatMessage($messageData);

            // Send via WebSocket
            $this->pusher->trigger("chat.{$chatId}", 'new_message', $messageData);

            return $this->successResponse($messageData, 'Message sent successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to send message: ' . $e->getMessage());
        }
    }

    /**
     * Get system status updates
     */
    public function getSystemStatus(Request $request)
    {
        try {
            $user = $request->user();
            $schoolId = $user->school_id ?? 1;

            $systemStatus = [
                'server_status' => 'online',
                'database_status' => $this->checkDatabaseStatus(),
                'cache_status' => $this->checkCacheStatus(),
                'storage_status' => $this->checkStorageStatus(),
                'active_users' => $this->getActiveUsersCount($schoolId),
                'last_updated' => now()->toISOString(),
            ];

            // Send real-time update
            $this->pusher->trigger("school.{$schoolId}.system", 'status_updated', $systemStatus);

            return $this->successResponse($systemStatus, 'System status retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get system status: ' . $e->getMessage());
        }
    }

    /**
     * Get user channels
     */
    private function getUserChannels($user, $schoolId)
    {
        $channels = [
            "user.{$user->id}",
            "school.{$schoolId}",
        ];

        // Add role-specific channels
        $roles = $user->roles->pluck('name')->toArray();
        
        if (in_array('student', $roles)) {
            $channels[] = "school.{$schoolId}.students";
            $channels[] = "student.{$user->id}";
        }

        if (in_array('parent', $roles) || in_array('guardian', $roles)) {
            $channels[] = "school.{$schoolId}.parents";
            $channels[] = "parent.{$user->id}";
        }

        if (in_array('teacher', $roles)) {
            $channels[] = "school.{$schoolId}.teachers";
            $channels[] = "teacher.{$user->id}";
        }

        if (in_array('admin', $roles) || in_array('super_admin', $roles)) {
            $channels[] = "school.{$schoolId}.admin";
            $channels[] = "admin.{$user->id}";
        }

        return $channels;
    }

    /**
     * Generate auth token
     */
    private function generateAuthToken($user)
    {
        return base64_encode(json_encode([
            'user_id' => $user->id,
            'school_id' => $user->school_id,
            'roles' => $user->roles->pluck('name')->toArray(),
            'expires_at' => now()->addHours(24)->timestamp,
        ]));
    }

    /**
     * Store notification
     */
    private function storeNotification($data, $channel, $event)
    {
        try {
            DB::table('realtime_notifications')->insert([
                'id' => $data['id'],
                'channel' => $channel,
                'event' => $event,
                'message' => $data['message'],
                'data' => json_encode($data['data']),
                'sender_id' => $data['sender_id'],
                'target_users' => json_encode($data['target_users']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store notification: ' . $e->getMessage());
        }
    }

    /**
     * Get attendance data
     */
    private function getAttendanceData($schoolId, $date)
    {
        try {
            if (class_exists('Modules\\Attendance\\Models\\Attendance')) {
                return \Modules\Attendance\Models\Attendance::where('school_id', $schoolId)
                    ->whereDate('date', $date)
                    ->with(['student', 'class'])
                    ->get()
                    ->map(function ($attendance) {
                        return [
                            'id' => $attendance->id,
                            'student_id' => $attendance->student_id,
                            'student_name' => $attendance->student->name ?? 'N/A',
                            'class_id' => $attendance->class_id,
                            'class_name' => $attendance->class->name ?? 'N/A',
                            'status' => $attendance->status,
                            'time' => $attendance->created_at->toISOString(),
                        ];
                    });
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get fee data
     */
    private function getFeeData($schoolId)
    {
        try {
            if (class_exists('Modules\\Finance\\Models\\Fee')) {
                return \Modules\Finance\Models\Fee::where('school_id', $schoolId)
                    ->with(['student', 'class'])
                    ->get()
                    ->map(function ($fee) {
                        return [
                            'id' => $fee->id,
                            'student_id' => $fee->student_id,
                            'student_name' => $fee->student->name ?? 'N/A',
                            'amount' => $fee->amount,
                            'status' => $fee->status,
                            'due_date' => $fee->due_date,
                        ];
                    });
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get exam data
     */
    private function getExamData($schoolId)
    {
        try {
            if (class_exists('Modules\\Academic\\Models\\Exam')) {
                return \Modules\Academic\Models\Exam::where('school_id', $schoolId)
                    ->where('date', '>=', now())
                    ->with(['subject', 'class'])
                    ->get()
                    ->map(function ($exam) {
                        return [
                            'id' => $exam->id,
                            'name' => $exam->name,
                            'subject' => $exam->subject->name ?? 'N/A',
                            'class' => $exam->class->name ?? 'N/A',
                            'date' => $exam->date,
                            'start_time' => $exam->start_time,
                            'end_time' => $exam->end_time,
                        ];
                    });
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get assignment data
     */
    private function getAssignmentData($schoolId, $userId)
    {
        try {
            if (class_exists('Modules\\Academic\\Models\\Assignment')) {
                return \Modules\Academic\Models\Assignment::where('school_id', $schoolId)
                    ->where('student_id', $userId)
                    ->where('due_date', '>=', now())
                    ->with(['subject', 'class'])
                    ->get()
                    ->map(function ($assignment) {
                        return [
                            'id' => $assignment->id,
                            'title' => $assignment->title,
                            'description' => $assignment->description,
                            'subject' => $assignment->subject->name ?? 'N/A',
                            'class' => $assignment->class->name ?? 'N/A',
                            'due_date' => $assignment->due_date,
                            'status' => $assignment->status,
                        ];
                    });
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get chat messages data
     */
    private function getChatMessagesData($chatId, $userId, $limit)
    {
        try {
            return DB::table('chat_messages')
                ->where('chat_id', $chatId)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'user_id' => $message->user_id,
                        'user_name' => $message->user_name,
                        'message' => $message->message,
                        'message_type' => $message->message_type,
                        'timestamp' => $message->created_at,
                    ];
                });
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Store chat message
     */
    private function storeChatMessage($messageData)
    {
        try {
            DB::table('chat_messages')->insert([
                'id' => $messageData['id'],
                'chat_id' => $messageData['chat_id'],
                'user_id' => $messageData['user_id'],
                'user_name' => $messageData['user_name'],
                'message' => $messageData['message'],
                'message_type' => $messageData['message_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store chat message: ' . $e->getMessage());
        }
    }

    /**
     * Check database status
     */
    private function checkDatabaseStatus()
    {
        try {
            DB::connection()->getPdo();
            return 'connected';
        } catch (\Exception $e) {
            return 'disconnected';
        }
    }

    /**
     * Check cache status
     */
    private function checkCacheStatus()
    {
        try {
            Redis::ping();
            return 'active';
        } catch (\Exception $e) {
            return 'inactive';
        }
    }

    /**
     * Check storage status
     */
    private function checkStorageStatus()
    {
        try {
            $freeSpace = disk_free_space(storage_path());
            $totalSpace = disk_total_space(storage_path());
            $percentage = round(($freeSpace / $totalSpace) * 100, 2);
            
            return $percentage > 10 ? 'normal' : 'low';
        } catch (\Exception $e) {
            return 'unknown';
        }
    }

    /**
     * Get active users count
     */
    private function getActiveUsersCount($schoolId)
    {
        try {
            return \App\Models\User::where('school_id', $schoolId)
                ->where('last_activity_at', '>=', now()->subMinutes(5))
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }
}