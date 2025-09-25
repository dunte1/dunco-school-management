<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->enum('type', ['mcq', 'fill_blank', 'essay', 'coding', 'matching', 'true_false', 'short_answer']);
            $table->foreignId('category_id')->constrained('question_categories');
            $table->json('options')->nullable(); // For MCQ, matching options
            $table->json('correct_answers')->nullable(); // Correct answers
            $table->text('explanation')->nullable(); // Explanation for correct answer
            $table->decimal('marks', 8, 2)->default(1);
            $table->integer('time_limit_seconds')->nullable();
            $table->json('metadata')->nullable(); // Additional data like images, code snippets
            $table->boolean('is_active')->default(true);
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->json('tags')->nullable();
            $table->text('feedback')->nullable();
            $table->boolean('file_upload')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}; 