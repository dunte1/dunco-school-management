<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursing_scenarios', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced'])->default('intermediate');
            $table->enum('category', [
                'medical_surgical', 'maternal_health', 'child_health',
                'community_health', 'emergency', 'mental_health',
                'geriatric', 'perioperative', 'other'
            ])->default('medical_surgical');
            $table->json('patient_information')->nullable();
            $table->json('patient_history')->nullable();
            $table->json('observations')->nullable();
            $table->json('learning_objectives')->nullable();
            $table->json('references')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('version')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category', 'difficulty', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_scenarios');
    }
};
