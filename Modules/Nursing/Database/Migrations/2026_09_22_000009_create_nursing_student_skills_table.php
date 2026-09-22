<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursing_student_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('academic_students')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('nursing_skills')->onDelete('cascade');
            $table->foreignId('placement_id')->nullable()->constrained('nursing_placements')->onDelete('set null');
            $table->enum('status', [
                'not_started', 'learning', 'observed', 'assisted',
                'performed_supervised', 'competent', 'remediation_required'
            ])->default('not_started');
            $table->foreignId('awarded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('awarded_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'skill_id', 'placement_id']);
            $table->index(['student_id', 'status']);
            $table->index(['skill_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_student_skills');
    }
};
