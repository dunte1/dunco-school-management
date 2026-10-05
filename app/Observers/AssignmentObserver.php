<?php

namespace App\Observers;

use App\Events\AssignmentCreated;
use App\Jobs\SendFcmMessage;
use Modules\Academic\Models\SubjectResource;
use Illuminate\Support\Facades\Log;

class AssignmentObserver
{
    /**
     * Handle the Assignment "created" event.
     */
    public function created(SubjectResource $assignment): void
    {
        try {
            // Broadcast real-time event
            event(new AssignmentCreated($assignment, 'created'));

            // Send push notification to all students in the class
            $this->sendAssignmentNotification($assignment, 'New assignment posted');

            Log::info("Assignment created and broadcasted: {$assignment->title}");
        } catch (\Exception $e) {
            Log::error("Failed to broadcast assignment creation: " . $e->getMessage());
        }
    }

    /**
     * Handle the Assignment "updated" event.
     */
    public function updated(SubjectResource $assignment): void
    {
        try {
            // Broadcast real-time event
            event(new AssignmentCreated($assignment, 'updated'));

            // Send push notification if important fields changed
            if ($assignment->wasChanged(['title', 'description', 'due_date'])) {
                $this->sendAssignmentNotification($assignment, 'Assignment updated');
            }

            Log::info("Assignment updated and broadcasted: {$assignment->title}");
        } catch (\Exception $e) {
            Log::error("Failed to broadcast assignment update: " . $e->getMessage());
        }
    }

    /**
     * Handle the Assignment "deleted" event.
     */
    public function deleted(SubjectResource $assignment): void
    {
        try {
            // Broadcast real-time event
            event(new AssignmentCreated($assignment, 'deleted'));

            Log::info("Assignment deleted and broadcasted: {$assignment->title}");
        } catch (\Exception $e) {
            Log::error("Failed to broadcast assignment deletion: " . $e->getMessage());
        }
    }

    /**
     * Send push notification for assignment changes
     */
    private function sendAssignmentNotification(Assignment $assignment, string $title): void
    {
        try {
            $class = $assignment->class;
            $subject = $assignment->subject;
            $teacher = $assignment->teacher;

            // Get all students in the class
            $students = $class->students;

            foreach ($students as $student) {
                $user = $student->user;

                // Send to student
                if ($user->device_token) {
                    $message = "New assignment: {$assignment->title} for {$subject->name}. Due: {$assignment->due_date}";
                    dispatch(new SendFcmMessage($user->device_token, $title, $message));
                }

                // Send to parent if student is a minor
                if ($student->parent && $student->parent->device_token) {
                    $message = "{$student->user->name} has a new assignment: {$assignment->title} for {$subject->name}. Due: {$assignment->due_date}";
                    dispatch(new SendFcmMessage($student->parent->device_token, $title, $message));
                }
            }
        } catch (\Exception $e) {
            Log::error("Failed to send assignment notification: " . $e->getMessage());
        }
    }
}
