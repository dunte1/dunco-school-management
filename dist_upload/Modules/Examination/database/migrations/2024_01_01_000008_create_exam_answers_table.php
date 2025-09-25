<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_attempt_id')->constrained('exam_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->json('student_answer')->nullable(); // Can be array for multiple answers
            $table->text('essay_answer')->nullable(); // For essay questions
            $table->text('code_answer')->nullable(); // For coding questions
            $table->string('file_path')->nullable(); // For uploaded files
            $table->decimal('marks_obtained', 8, 2)->default(0);
            $table->decimal('max_marks', 8, 2)->default(0);
            $table->boolean('is_correct')->nullable(); // For auto-graded questions
            $table->text('feedback')->nullable(); // Teacher feedback
            $table->json('auto_grade_data')->nullable(); // Auto-grading details
            $table->boolean('is_graded')->default(false);
            $table->datetime('answered_at')->nullable();
            $table->integer('time_spent_seconds')->nullable();
            $table->timestamps();
            
            $table->unique(['exam_attempt_id', 'question_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_answers');
    }
}; 