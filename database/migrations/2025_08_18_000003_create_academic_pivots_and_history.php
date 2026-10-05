<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Student-Class pivot
        if (!Schema::hasTable('academic_class_student')) {
        Schema::create('academic_class_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('academic_students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('academic_classes')->onDelete('cascade');
            $table->date('enrollment_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['student_id', 'class_id']);
        }); }

        // Enrollment history
        if (!Schema::hasTable('academic_enrollment_history')) {
        Schema::create('academic_enrollment_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('academic_students')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('academic_classes')->nullOnDelete();
            $table->string('academic_year')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('changed_at')->nullable();
            $table->timestamps();
        }); }
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_enrollment_history');
        Schema::dropIfExists('academic_class_student');
    }
};


