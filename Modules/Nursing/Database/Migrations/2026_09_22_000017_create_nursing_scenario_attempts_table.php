<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursing_scenario_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scenario_id')->constrained('nursing_scenarios')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('academic_students')->onDelete('cascade');
            $table->json('answers')->nullable();
            $table->integer('score')->default(0);
            $table->integer('total_questions')->default(0);
            $table->decimal('percentage', 5, 2)->default(0);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['scenario_id', 'student_id']);
            $table->index(['student_id', 'is_completed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_scenario_attempts');
    }
};
