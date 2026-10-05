<?php

namespace App\Observers;

use App\Events\AttendanceMarked;
use App\Jobs\SendFcmMessage;
use Modules\Academic\Models\AttendanceRecord;
use Illuminate\Support\Facades\Log;

class AttendanceObserver
{
    /**
     * Handle the AttendanceRecord "created" event.
     */
    public function created(AttendanceRecord $attendance): void
    {
        try {
            // Broadcast real-time event
            event(new AttendanceMarked($attendance, 'created'));

            // Send push notification to student/parent
            $this->sendAttendanceNotification($attendance, 'Attendance marked');

            Log::info("Attendance created and broadcasted for student: {$attendance->student->user->name}");
        } catch (\Exception $e) {
            Log::error("Failed to broadcast attendance creation: " . $e->getMessage());
        }
    }

    /**
     * Handle the AttendanceRecord "updated" event.
     */
    public function updated(AttendanceRecord $attendance): void
    {
        try {
            // Broadcast real-time event
            event(new AttendanceMarked($attendance, 'updated'));

            // Send push notification if status changed
            if ($attendance->wasChanged('status')) {
                $this->sendAttendanceNotification($attendance, 'Attendance updated');
            }

            Log::info("Attendance updated and broadcasted for student: {$attendance->student->user->name}");
        } catch (\Exception $e) {
            Log::error("Failed to broadcast attendance update: " . $e->getMessage());
        }
    }

    /**
     * Handle the AttendanceRecord "deleted" event.
     */
    public function deleted(AttendanceRecord $attendance): void
    {
        try {
            // Broadcast real-time event
            event(new AttendanceMarked($attendance, 'deleted'));

            Log::info("Attendance deleted and broadcasted for student: {$attendance->student->user->name}");
        } catch (\Exception $e) {
            Log::error("Failed to broadcast attendance deletion: " . $e->getMessage());
        }
    }

    /**
     * Send push notification for attendance changes
     */
    private function sendAttendanceNotification(AttendanceRecord $attendance, string $title): void
    {
        try {
            $student = $attendance->student;
            $user = $student->user;

            // Send to student
            if ($user->device_token) {
                $message = "Your attendance for {$attendance->date} has been marked as {$attendance->status}";
                dispatch(new SendFcmMessage($user->device_token, $title, $message));
            }

            // Send to parent if student is a minor
            if ($student->parent && $student->parent->device_token) {
                $message = "{$student->user->name}'s attendance for {$attendance->date} has been marked as {$attendance->status}";
                dispatch(new SendFcmMessage($student->parent->device_token, $title, $message));
            }
        } catch (\Exception $e) {
            Log::error("Failed to send attendance notification: " . $e->getMessage());
        }
    }
}
