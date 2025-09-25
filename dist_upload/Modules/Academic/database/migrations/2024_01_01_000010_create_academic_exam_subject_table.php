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
        Schema::create('academic_exam_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('academic_exams')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('academic_subjects')->onDelete('cascade');
            $table->date('exam_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('total_marks', 5, 2);
            $table->decimal('passing_marks', 5, 2);
            $table->timestamps();
            
            $table->unique(['exam_id', 'subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_exam_subject');
    }
}; 