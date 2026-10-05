<?php

namespace Modules\Academic\Services;

use Modules\Academic\Models\OnlineClass;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Modules\Academic\Notifications\OnlineClassScheduled;
use Modules\Academic\Notifications\OnlineClassReminder;
use Modules\Academic\Notifications\OnlineClassStarted;
use Carbon\Carbon;

class OnlineClassNotificationService
{
    /**
     * Send notification when a new online class is scheduled
     */
    public function notifyClassScheduled(OnlineClass $onlineClass)
    {
        // Get all students in the academic class
        $students = $this->getClassStudents($onlineClass->academic_class_id);
        
        // Get the teacher
        $teacher = $onlineClass->teacher;
        
        // Send notifications to students
        foreach ($students as $student) {
            $student->notify(new OnlineClassScheduled($onlineClass));
        }
        
        // Send email notification to students
        $this->sendEmailNotification($students, $onlineClass, 'scheduled');
        
        // Log the notification
        \Log::info("Online class scheduled notification sent", [
            'class_id' => $onlineClass->id,
            'title' => $onlineClass->title,
            'students_count' => $students->count(),
            'teacher' => $teacher->name
        ]);
    }

    /**
     * Send reminder notifications before class starts
     */
    public function sendReminderNotifications()
    {
        // Get classes starting in the next 15 minutes
        $upcomingClasses = OnlineClass::where('status', 'scheduled')
            ->where('start_time', '<=', now()->addMinutes(15))
            ->where('start_time', '>', now())
            ->get();

        foreach ($upcomingClasses as $class) {
            $students = $this->getClassStudents($class->academic_class_id);
            
            foreach ($students as $student) {
                $student->notify(new OnlineClassReminder($class));
            }
            
            // Send email reminder
            $this->sendEmailNotification($students, $class, 'reminder');
        }
    }

    /**
     * Send notification when class starts
     */
    public function notifyClassStarted(OnlineClass $onlineClass)
    {
        $students = $this->getClassStudents($onlineClass->academic_class_id);
        
        foreach ($students as $student) {
            $student->notify(new OnlineClassStarted($onlineClass));
        }
        
        // Send email notification
        $this->sendEmailNotification($students, $onlineClass, 'started');
    }

    /**
     * Send notification to specific class or staff
     */
    public function notifyClassOrStaff($classId = null, $staffIds = [], $message, $type = 'general')
    {
        $recipients = collect();
        
        // Add students from specific class
        if ($classId) {
            $students = $this->getClassStudents($classId);
            $recipients = $recipients->merge($students);
        }
        
        // Add specific staff members
        if (!empty($staffIds)) {
            $staff = User::whereIn('id', $staffIds)->get();
            $recipients = $recipients->merge($staff);
        }
        
        // Send notifications
        foreach ($recipients as $recipient) {
            $recipient->notify(new \Modules\Academic\Notifications\GeneralNotification($message, $type));
        }
        
        // Send email notification
        $this->sendEmailNotification($recipients, null, 'general', $message);
    }

    /**
     * Get all students in a specific academic class
     */
    private function getClassStudents($academicClassId)
    {
        return User::whereHas('academicClasses', function($query) use ($academicClassId) {
            $query->where('academic_class_id', $academicClassId);
        })->get();
    }

    /**
     * Send email notification
     */
    private function sendEmailNotification($recipients, $onlineClass = null, $type, $message = null)
    {
        foreach ($recipients as $recipient) {
            try {
                $data = [
                    'recipient' => $recipient,
                    'onlineClass' => $onlineClass,
                    'type' => $type,
                    'message' => $message
                ];

                Mail::send("academic::emails.online-class-{$type}", $data, function($mail) use ($recipient, $onlineClass, $type) {
                    $mail->to($recipient->email, $recipient->name)
                         ->subject($this->getEmailSubject($type, $onlineClass));
                });
            } catch (\Exception $e) {
                \Log::error("Failed to send email notification", [
                    'recipient' => $recipient->email,
                    'type' => $type,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Get email subject based on notification type
     */
    private function getEmailSubject($type, $onlineClass = null)
    {
        switch ($type) {
            case 'scheduled':
                return "New Online Class Scheduled: {$onlineClass->title}";
            case 'reminder':
                return "Reminder: Online Class Starting Soon - {$onlineClass->title}";
            case 'started':
                return "Online Class Started: {$onlineClass->title}";
            case 'general':
                return "School Notification";
            default:
                return "Online Class Notification";
        }
    }

    /**
     * Schedule reminder notifications
     */
    public function scheduleReminders()
    {
        // This would typically be called by a scheduled task
        $this->sendReminderNotifications();
    }
}
