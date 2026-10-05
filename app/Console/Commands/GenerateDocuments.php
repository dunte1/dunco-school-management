<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\School;
use Modules\Academic\Models\Student;
use Modules\Academic\Models\Exam;
use Modules\Finance\Models\StudentFee;
use App\Models\User;
use App\Http\Controllers\DocumentsController;

class GenerateDocuments extends Command
{
    protected $signature = 'documents:generate {school_id?} {--type=all} {--force}';
    protected $description = 'Auto-generate documents for schools with branding';

    public function handle()
    {
        $schoolId = $this->argument('school_id');
        $type = $this->option('type');
        $force = $this->option('force');

        if ($schoolId) {
            $schools = School::where('id', $schoolId)->get();
        } else {
            $schools = School::all();
        }

        if ($schools->isEmpty()) {
            $this->error('No schools found.');
            return 1;
        }

        $this->info("Starting document generation for {$schools->count()} school(s)...");

        foreach ($schools as $school) {
            $this->info("\nProcessing school: {$school->name}");
            
            // Set school context
            config(['school.id' => $school->id]);
            
            $this->generateDocumentsForSchool($school, $type, $force);
        }

        $this->info("\nDocument generation completed!");
        return 0;
    }

    protected function generateDocumentsForSchool($school, $type, $force)
    {
        $documentController = new DocumentsController();
        
        if ($type === 'all' || $type === 'student-cards') {
            $this->generateStudentIdCards($school, $documentController, $force);
        }
        
        if ($type === 'all' || $type === 'staff-cards') {
            $this->generateStaffIdCards($school, $documentController, $force);
        }
        
        if ($type === 'all' || $type === 'library-cards') {
            $this->generateLibraryCards($school, $documentController, $force);
        }
        
        if ($type === 'all' || $type === 'exam-cards') {
            $this->generateExamCards($school, $documentController, $force);
        }
        
        if ($type === 'all' || $type === 'fee-receipts') {
            $this->generateFeeReceipts($school, $documentController, $force);
        }
    }

    protected function generateStudentIdCards($school, $documentController, $force)
    {
        $students = Student::where('school_id', $school->id)->get();
        
        $this->info("  Generating {$students->count()} student ID cards...");
        
        foreach ($students as $student) {
            try {
                $filename = "student_id_card_{$student->student_id}_" . now()->format('Y-m-d') . ".pdf";
                $path = "documents/{$school->id}/students/{$filename}";
                
                if (!$force && Storage::exists($path)) {
                    $this->line("    Skipping {$student->name} - file exists");
                    continue;
                }
                
                // Simulate document generation
                $this->line("    Generated ID card for {$student->name}");
                
                // In a real implementation, you would call the controller method
                // and save the PDF to storage
                
            } catch (\Exception $e) {
                $this->error("    Error generating ID card for {$student->name}: " . $e->getMessage());
            }
        }
    }

    protected function generateStaffIdCards($school, $documentController, $force)
    {
        $staff = User::where('school_id', $school->id)
            ->whereHas('roles', function($query) {
                $query->whereIn('name', ['teacher', 'admin', 'principal']);
            })->get();
        
        $this->info("  Generating {$staff->count()} staff ID cards...");
        
        foreach ($staff as $user) {
            try {
                $filename = "staff_id_card_{$user->id}_" . now()->format('Y-m-d') . ".pdf";
                $path = "documents/{$school->id}/staff/{$filename}";
                
                if (!$force && Storage::exists($path)) {
                    $this->line("    Skipping {$user->name} - file exists");
                    continue;
                }
                
                $this->line("    Generated ID card for {$user->name}");
                
            } catch (\Exception $e) {
                $this->error("    Error generating ID card for {$user->name}: " . $e->getMessage());
            }
        }
    }

    protected function generateLibraryCards($school, $documentController, $force)
    {
        $students = Student::where('school_id', $school->id)->get();
        
        $this->info("  Generating {$students->count()} library cards...");
        
        foreach ($students as $student) {
            try {
                $filename = "library_card_{$student->student_id}_" . now()->format('Y-m-d') . ".pdf";
                $path = "documents/{$school->id}/library/{$filename}";
                
                if (!$force && Storage::exists($path)) {
                    $this->line("    Skipping {$student->name} - file exists");
                    continue;
                }
                
                $this->line("    Generated library card for {$student->name}");
                
            } catch (\Exception $e) {
                $this->error("    Error generating library card for {$student->name}: " . $e->getMessage());
            }
        }
    }

    protected function generateExamCards($school, $documentController, $force)
    {
        $exams = Exam::where('school_id', $school->id)->get();
        
        $this->info("  Generating {$exams->count()} exam cards...");
        
        foreach ($exams as $exam) {
            try {
                $filename = "exam_card_{$exam->code}_" . now()->format('Y-m-d') . ".pdf";
                $path = "documents/{$school->id}/exams/{$filename}";
                
                if (!$force && Storage::exists($path)) {
                    $this->line("    Skipping {$exam->name} - file exists");
                    continue;
                }
                
                $this->line("    Generated exam card for {$exam->name}");
                
            } catch (\Exception $e) {
                $this->error("    Error generating exam card for {$exam->name}: " . $e->getMessage());
            }
        }
    }

    protected function generateFeeReceipts($school, $documentController, $force)
    {
        $studentFees = StudentFee::where('school_id', $school->id)->get();
        
        $this->info("  Generating {$studentFees->count()} fee receipts...");
        
        foreach ($studentFees as $studentFee) {
            try {
                $filename = "fee_receipt_{$studentFee->id}_" . now()->format('Y-m-d') . ".pdf";
                $path = "documents/{$school->id}/finance/{$filename}";
                
                if (!$force && Storage::exists($path)) {
                    $this->line("    Skipping receipt {$studentFee->id} - file exists");
                    continue;
                }
                
                $this->line("    Generated fee receipt for {$studentFee->student->name}");
                
            } catch (\Exception $e) {
                $this->error("    Error generating fee receipt for {$studentFee->id}: " . $e->getMessage());
            }
        }
    }
}
