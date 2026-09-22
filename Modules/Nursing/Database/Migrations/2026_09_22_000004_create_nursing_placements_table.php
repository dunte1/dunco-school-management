<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursing_placements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('academic_students')->onDelete('cascade');
            $table->foreignId('facility_id')->constrained('nursing_facilities')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('nursing_facility_departments')->onDelete('cascade');
            $table->foreignId('ward_id')->nullable()->constrained('nursing_wards')->onDelete('set null');
            $table->foreignId('instructor_id')->nullable()->constrained('staff')->onDelete('set null');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('required_hours', 6, 1)->default(200);
            $table->enum('status', ['planned', 'active', 'completed', 'cancelled'])->default('planned');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'student_id', 'status']);
            $table->index(['school_id', 'instructor_id', 'status']);
            $table->index(['facility_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_placements');
    }
};
