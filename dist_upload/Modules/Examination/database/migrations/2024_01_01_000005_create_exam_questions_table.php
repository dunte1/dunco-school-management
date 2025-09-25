<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->decimal('marks', 8, 2)->nullable(); // Override question marks if needed
            $table->boolean('is_required')->default(true);
            $table->json('settings')->nullable(); // Question-specific settings for this exam
            $table->timestamps();
            
            $table->unique(['exam_id', 'question_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_questions');
    }
}; 