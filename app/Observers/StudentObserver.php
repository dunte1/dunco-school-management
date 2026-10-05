<?php

namespace App\Observers;

use Modules\Academic\Models\Student;
use Illuminate\Support\Facades\Log;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        // Auto-generate student documents
        $this->autoGenerateStudentDocuments($student);
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        // Check if important fields were changed and regenerate documents if needed
        if ($student->wasChanged(['student_id', 'class_id', 'status'])) {
            $this->autoGenerateStudentDocuments($student);
        }
    }

    /**
     * Auto-generate documents for the student
     */
    private function autoGenerateStudentDocuments(Student $student): void
    {
        try {
            // Generate Student ID Card
            $this->generateStudentIdCard($student);
            
            // Generate Library Card
            $this->generateLibraryCard($student);

            $userName = $student->user ? $student->user->name : 'Unknown';
            Log::info("Auto-generated documents for student: {$student->student_id} ({$userName})");
        } catch (\Exception $e) {
            Log::error("Failed to auto-generate documents for student {$student->id}: " . $e->getMessage());
        }
    }

    /**
     * Generate Student ID Card
     */
    private function generateStudentIdCard(Student $student): void
    {
        try {
            Log::info("Auto-generate Student ID Card for: {$student->student_id}");
            
            // You can trigger an Artisan command here
            // \Artisan::call('documents:generate-student-id', ['student_id' => $student->id]);
            
            // Or store in a queue for background processing
            // dispatch(new GenerateStudentIdCardJob($student->id));
        } catch (\Exception $e) {
            Log::error("Failed to generate Student ID Card for student {$student->id}: " . $e->getMessage());
        }
    }

    /**
     * Generate Library Card
     */
    private function generateLibraryCard(Student $student): void
    {
        try {
            Log::info("Auto-generate Library Card for: {$student->student_id}");
            
            // You can trigger an Artisan command here
            // \Artisan::call('documents:generate-library-card', ['student_id' => $student->id]);
            
            // Or store in a queue for background processing
            // dispatch(new GenerateLibraryCardJob($student->id));
        } catch (\Exception $e) {
            Log::error("Failed to generate Library Card for student {$student->id}: " . $e->getMessage());
        }
    }
}
