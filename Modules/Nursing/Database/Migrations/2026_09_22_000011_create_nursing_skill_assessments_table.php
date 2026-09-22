<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursing_skill_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('academic_students')->onDelete('cascade');
            $table->foreignId('skill_id')->constrained('nursing_skills')->onDelete('cascade');
            $table->foreignId('student_skill_id')->nullable()->constrained('nursing_student_skills')->onDelete('set null');
            $table->foreignId('assessor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rubric_id')->nullable()->constrained('nursing_skill_rubrics')->onDelete('set null');
            $table->json('criteria_scores')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->decimal('maximum_score', 5, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->enum('result', ['pass', 'fail', 'conditional', 'pending'])->default('pending');
            $table->text('feedback')->nullable();
            $table->text('recommendation')->nullable();
            $table->timestamp('assessed_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'skill_id']);
            $table->index(['assessor_id']);
            $table->index(['assessed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_skill_assessments');
    }
};
