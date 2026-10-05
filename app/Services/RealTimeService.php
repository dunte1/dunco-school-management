<?php

namespace App\Services;

use App\Jobs\SendFcmMessage;
use App\Models\User;
use App\Models\UserDeviceToken;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

class RealTimeService
{
    protected $pusher;

    public function __construct()
    {
        $key = config('broadcasting.connections.pusher.key');
        $secret = config('broadcasting.connections.pusher.secret');
        $appId = config('broadcasting.connections.pusher.app_id');
        
        // Only initialize Pusher if all required credentials are available
        if ($key && $secret && $appId) {
            $this->pusher = new Pusher(
                $key,
                $secret,
                $appId,
                config('broadcasting.connections.pusher.options')
            );
        } else {
            // Log warning about missing Pusher configuration
            Log::warning('Pusher credentials not configured. Real-time features will be disabled.');
            $this->pusher = null;
        }
    }

    /**
     * Broadcast event to multiple channels
     */
    public function broadcast($channels, $event, $data)
    {
        try {
            if (!$this->pusher) {
                Log::warning("Cannot broadcast event - Pusher not configured", [
                    'channels' => $channels,
                    'event' => $event
                ]);
                return false;
            }

            if (!is_array($channels)) {
                $channels = [$channels];
            }

            foreach ($channels as $channel) {
                $this->pusher->trigger($channel, $event, $data);
            }

            Log::info("Real-time event broadcasted", [
                'channels' => $channels,
                'event' => $event,
                'data' => $data
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to broadcast real-time event: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send push notification to user
     */
    public function sendPushNotification($userId, $title, $message, $data = [])
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                return false;
            }

            // Send to user's device token
            if ($user->device_token) {
                dispatch(new SendFcmMessage($user->device_token, $title, $message, $data));
            }

            // Send to all user's device tokens
            $deviceTokens = UserDeviceToken::where('user_id', $userId)->pluck('token');
            foreach ($deviceTokens as $token) {
                dispatch(new SendFcmMessage($token, $title, $message, $data));
            }

            Log::info("Push notification sent", [
                'user_id' => $userId,
                'title' => $title,
                'message' => $message
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error("Failed to send push notification: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send push notification to multiple users
     */
    public function sendBulkPushNotification($userIds, $title, $message, $data = [])
    {
        foreach ($userIds as $userId) {
            $this->sendPushNotification($userId, $title, $message, $data);
        }
    }

    /**
     * Send push notification to users by role
     */
    public function sendPushNotificationByRole($role, $title, $message, $data = [])
    {
        try {
            $users = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            })->pluck('id');

            $this->sendBulkPushNotification($users, $title, $message, $data);

        } catch (\Exception $e) {
            Log::error("Failed to send push notification by role: " . $e->getMessage());
        }
    }

    /**
     * Send push notification to class
     */
    public function sendPushNotificationToClass($classId, $title, $message, $data = [])
    {
        try {
            $students = \Modules\Academic\Models\Student::where('class_id', $classId)
                ->with('user')
                ->get();

            $userIds = $students->pluck('user.id')->filter();
            $this->sendBulkPushNotification($userIds, $title, $message, $data);

            // Also send to parents
            foreach ($students as $student) {
                if ($student->parent && $student->parent->user) {
                    $parentMessage = "{$student->user->name}: $message";
                    $this->sendPushNotification($student->parent->user->id, $title, $parentMessage, $data);
                }
            }

        } catch (\Exception $e) {
            Log::error("Failed to send push notification to class: " . $e->getMessage());
        }
    }

    /**
     * Broadcast and send push notification
     */
    public function broadcastAndNotify($channels, $event, $data, $userIds, $title, $message)
    {
        // Broadcast real-time event
        $this->broadcast($channels, $event, $data);

        // Send push notifications
        $this->sendBulkPushNotification($userIds, $title, $message, $data);
    }

    /**
     * Handle attendance update
     */
    public function handleAttendanceUpdate($attendance)
    {
        $student = $attendance->student;
        $user = $student->user;

        $channels = [
            "private-user.{$user->id}",
            "private-class.{$attendance->class_id}",
            "private-teacher.{$attendance->teacher_id}"
        ];

        $data = [
            'type' => 'attendance_update',
            'attendance_id' => $attendance->id,
            'student_name' => $user->name,
            'status' => $attendance->status,
            'date' => $attendance->date,
            'timestamp' => now()->toISOString()
        ];

        $title = 'Attendance Update';
        $message = "Your attendance for {$attendance->date} has been marked as {$attendance->status}";

        $this->broadcastAndNotify($channels, 'attendance.updated', $data, [$user->id], $title, $message);
    }

    /**
     * Handle grade update
     */
    public function handleGradeUpdate($grade)
    {
        $student = $grade->student;
        $user = $student->user;
        $subject = $grade->subject;

        $channels = [
            "private-user.{$user->id}",
            "private-class.{$grade->class_id}",
            "private-teacher.{$grade->teacher_id}"
        ];

        $data = [
            'type' => 'grade_update',
            'grade_id' => $grade->id,
            'student_name' => $user->name,
            'subject_name' => $subject->name,
            'score' => $grade->score,
            'grade' => $grade->grade,
            'timestamp' => now()->toISOString()
        ];

        $title = 'Grade Update';
        $message = "Your grade for {$subject->name} is {$grade->grade} ({$grade->score}%)";

        $this->broadcastAndNotify($channels, 'grade.updated', $data, [$user->id], $title, $message);
    }

    /**
     * Handle payment update
     */
    public function handlePaymentUpdate($payment)
    {
        $student = $payment->student;
        $user = $student->user;
        $feeType = $payment->feeType;

        $channels = [
            "private-user.{$user->id}",
            "private-finance.admin",
            "private-class.{$student->class_id}"
        ];

        $data = [
            'type' => 'payment_update',
            'payment_id' => $payment->id,
            'student_name' => $user->name,
            'amount' => $payment->amount,
            'status' => $payment->status,
            'fee_type' => $feeType->name,
            'timestamp' => now()->toISOString()
        ];

        $title = 'Payment Update';
        $message = "Payment of {$payment->amount} for {$feeType->name} has been {$payment->status}";

        $this->broadcastAndNotify($channels, 'payment.updated', $data, [$user->id], $title, $message);
    }

    /**
     * Handle assignment creation
     */
    public function handleAssignmentCreation($assignment)
    {
        $class = $assignment->class;
        $subject = $assignment->subject;
        $teacher = $assignment->teacher;

        $channels = [
            "private-class.{$class->id}",
            "private-teacher.{$teacher->id}"
        ];

        $data = [
            'type' => 'assignment_created',
            'assignment_id' => $assignment->id,
            'title' => $assignment->title,
            'subject_name' => $subject->name,
            'due_date' => $assignment->due_date,
            'teacher_name' => $teacher->user->name,
            'timestamp' => now()->toISOString()
        ];

        $title = 'New Assignment';
        $message = "New assignment: {$assignment->title} for {$subject->name}. Due: {$assignment->due_date}";

        // Get all students in the class
        $studentIds = $class->students->pluck('user.id')->filter();
        $this->broadcastAndNotify($channels, 'assignment.created', $data, $studentIds, $title, $message);
    }
}
