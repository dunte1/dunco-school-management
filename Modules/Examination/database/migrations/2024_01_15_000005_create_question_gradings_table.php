<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_gradings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_attempt_id')->constrained('exam_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->foreignId('graded_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('grading_rubric_id')->nullable()->constrained('grading_rubrics')->onDelete('set null');
            $table->decimal('marks_obtained', 8, 2);
            $table->decimal('total_marks', 8, 2);
            $table->json('rubric_scores')->nullable(); // Individual criteria scores
            $table->text('feedback')->nullable();
            $table->text('comments')->nullable();
            $table->enum('status', ['pending', 'graded', 'moderated', 'disputed'])->default('pending');
            $table->timestamp('graded_at')->nullable();
            $table->timestamp('moderated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_gradings');
    }
};
