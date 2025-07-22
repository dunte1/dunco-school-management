<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('attempt_code')->unique();
            $table->datetime('started_at');
            $table->datetime('submitted_at')->nullable();
            $table->datetime('expires_at');
            $table->enum('status', ['started', 'in_progress', 'submitted', 'timeout', 'disqualified'])->default('started');
            $table->decimal('total_marks', 8, 2)->default(0);
            $table->decimal('obtained_marks', 8, 2)->default(0);
            $table->integer('time_taken_minutes')->nullable();
            $table->json('proctoring_data')->nullable(); // Webcam, screen recording, alerts
            $table->json('device_info')->nullable(); // Browser, IP, device details
            $table->text('notes')->nullable(); // Manual notes from invigilator
            $table->boolean('is_graded')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_attempts');
    }
}; 