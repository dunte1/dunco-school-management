<?php

namespace App\Observers;

use App\Events\GradeUpdated;
use App\Jobs\SendFcmMessage;
use Modules\Academic\Models\ExamResult;
use Illuminate\Support\Facades\Log;

class GradeObserver
{
    /**
     * Handle the Grade "created" event.
     */
    public function created(ExamResult $grade): void
    {
        try {
            // Broadcast real-time event
            event(new GradeUpdated($grade, 'created'));

            // Send push notification to student/parent
            $this->sendGradeNotification($grade, 'New grade posted');

            Log::info("Grade created and broadcasted for student: {$grade->student->user->name}");
        } catch (\Exception $e) {
            Log::error("Failed to broadcast grade creation: " . $e->getMessage());
        }
    }

    /**
     * Handle the Grade "updated" event.
     */
    public function updated(ExamResult $grade): void
    {
        try {
            // Broadcast real-time event
            event(new GradeUpdated($grade, 'updated'));

            // Send push notification if score or grade changed
            if ($grade->wasChanged(['score', 'grade'])) {
                $this->sendGradeNotification($grade, 'Grade updated');
            }

            Log::info("Grade updated and broadcasted for student: {$grade->student->user->name}");
        } catch (\Exception $e) {
            Log::error("Failed to broadcast grade update: " . $e->getMessage());
        }
    }

    /**
     * Handle the Grade "deleted" event.
     */
    public function deleted(ExamResult $grade): void
    {
        try {
            // Broadcast real-time event
            event(new GradeUpdated($grade, 'deleted'));

            Log::info("Grade deleted and broadcasted for student: {$grade->student->user->name}");
        } catch (\Exception $e) {
            Log::error("Failed to broadcast grade deletion: " . $e->getMessage());
        }
    }

    /**
     * Send push notification for grade changes
     */
    private function sendGradeNotification(ExamResult $grade, string $title): void
    {
        try {
            $student = $grade->student;
            $user = $student->user;
            $subject = $grade->subject;

            // Send to student
            if ($user->device_token) {
                $message = "Your grade for {$subject->name} is {$grade->grade} ({$grade->score}%)";
                dispatch(new SendFcmMessage($user->device_token, $title, $message));
            }

            // Send to parent if student is a minor
            if ($student->parent && $student->parent->device_token) {
                $message = "{$student->user->name} received {$grade->grade} ({$grade->score}%) in {$subject->name}";
                dispatch(new SendFcmMessage($student->parent->device_token, $title, $message));
            }
        } catch (\Exception $e) {
            Log::error("Failed to send grade notification: " . $e->getMessage());
        }
    }
}
