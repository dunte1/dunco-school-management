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
        Schema::create('academic_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('academic_students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('academic_classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('academic_subjects')->onDelete('cascade');
            $table->string('academic_year');
            $table->enum('term', ['first', 'second', 'third', 'fourth', 'final'])->default('first');
            $table->enum('exam_type', ['quiz', 'midterm', 'final', 'assignment', 'project'])->default('quiz');
            $table->decimal('marks_obtained', 5, 2);
            $table->decimal('total_marks', 5, 2);
            $table->decimal('percentage', 5, 2);
            $table->string('grade', 3)->nullable();
            $table->text('remarks')->nullable();
            $table->date('exam_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['school_id', 'student_id']);
            $table->index(['school_id', 'class_id']);
            $table->index(['school_id', 'subject_id']);
            $table->index(['school_id', 'academic_year']);
            $table->unique(['student_id', 'class_id', 'subject_id', 'academic_year', 'term', 'exam_type'], 'unique_academic_record');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_records');
    }
}; 