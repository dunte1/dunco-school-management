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
        Schema::create('academic_exam_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('academic_exams')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('academic_questions')->onDelete('cascade');
            $table->integer('order')->default(1);
            $table->decimal('marks', 5, 2);
            $table->timestamps();
            
            $table->unique(['exam_id', 'question_id']);
            $table->index(['exam_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_exam_question');
    }
}; 