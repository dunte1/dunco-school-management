<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('academic_students')) { return; }
        Schema::create('academic_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('student_id')->nullable();
            $table->string('name');
            $table->string('admission_number')->nullable();
            $table->foreignId('class_id')->nullable()->constrained('academic_classes')->nullOnDelete();
            $table->date('admission_date')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('blood_group', 10)->nullable();
            $table->string('religion', 50)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('emergency_contact', 100)->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('disabilities')->nullable();
            $table->text('allergies')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('stream')->nullable();
            $table->string('house')->nullable();
            $table->string('group')->nullable();
            $table->boolean('is_transfer')->default(false);
            $table->string('enrollment_status')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('status')->nullable();
            $table->text('status_reason')->nullable();
            $table->timestamp('status_changed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_students');
    }
};


