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
        Schema::create('academic_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->enum('exam_type', ['midterm', 'final', 'quiz', 'assignment', 'project'])->default('quiz');
            $table->string('academic_year');
            $table->enum('term', ['first', 'second', 'third', 'fourth', 'final'])->default('first');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->integer('duration_minutes')->default(60);
            $table->decimal('total_marks', 5, 2)->default(100);
            $table->decimal('passing_marks', 5, 2)->default(40);
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'archived'])->default('draft');
            $table->timestamps();
            
            $table->index(['school_id', 'academic_year']);
            $table->index(['school_id', 'status']);
            $table->index(['school_id', 'exam_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_exams');
    }
}; 