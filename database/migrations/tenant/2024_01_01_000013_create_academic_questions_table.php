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
        Schema::create('academic_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('academic_subjects')->onDelete('cascade');
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice', 'true_false', 'short_answer', 'essay'])->default('multiple_choice');
            $table->enum('difficulty_level', ['easy', 'medium', 'hard'])->default('medium');
            $table->decimal('marks', 5, 2)->default(1);
            $table->json('options')->nullable(); // For multiple choice questions
            $table->text('correct_answer');
            $table->text('explanation')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['school_id', 'subject_id']);
            $table->index(['school_id', 'question_type']);
            $table->index(['school_id', 'difficulty_level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_questions');
    }
}; 