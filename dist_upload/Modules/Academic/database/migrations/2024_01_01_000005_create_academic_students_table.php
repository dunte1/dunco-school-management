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
        Schema::create('academic_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('student_id')->unique(); // School-specific student ID
            $table->date('admission_date');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('blood_group', 5)->nullable();
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('allergies')->nullable();
            $table->string('previous_school')->nullable();
            $table->enum('enrollment_status', ['enrolled', 'pending', 'withdrawn', 'graduated'])->default('enrolled');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['school_id', 'enrollment_status']);
            $table->index(['school_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_students');
    }
}; 