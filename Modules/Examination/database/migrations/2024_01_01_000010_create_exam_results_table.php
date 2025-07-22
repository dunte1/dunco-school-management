<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('exam_attempt_id')->constrained('exam_attempts')->onDelete('cascade');
            $table->decimal('total_marks', 8, 2);
            $table->decimal('obtained_marks', 8, 2);
            $table->decimal('percentage', 5, 2);
            $table->string('grade')->nullable();
            $table->string('grade_point')->nullable();
            $table->enum('result_status', ['pass', 'fail', 'absent', 'disqualified'])->default('pass');
            $table->integer('class_position')->nullable();
            $table->integer('subject_position')->nullable();
            $table->integer('stream_position')->nullable();
            $table->text('remarks')->nullable();
            $table->json('subject_breakdown')->nullable(); // Subject-wise marks
            $table->boolean('is_published')->default(false);
            $table->datetime('published_at')->nullable();
            $table->timestamps();
            
            $table->unique(['exam_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_results');
    }
}; 