<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('academic_exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('exam_id')->constrained('academic_exams')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('academic_students')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('academic_subjects')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('academic_classes')->onDelete('cascade');
            $table->decimal('marks_obtained', 5, 2);
            $table->decimal('total_marks', 5, 2);
            $table->decimal('percentage', 5, 2);
            $table->string('grade', 3)->nullable();
            $table->text('remarks')->nullable();
            $table->datetime('submitted_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['school_id', 'exam_id']);
            $table->index(['school_id', 'student_id']);
            $table->index(['school_id', 'subject_id']);
            $table->unique(['exam_id', 'student_id', 'subject_id'], 'unique_exam_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_exam_results');
    }
}; 