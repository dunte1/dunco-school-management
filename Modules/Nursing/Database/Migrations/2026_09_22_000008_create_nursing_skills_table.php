<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursing_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('nursing_skill_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->json('learning_objectives')->nullable();
            $table->json('equipment')->nullable();
            $table->text('procedure_reference')->nullable();
            $table->text('safety_considerations')->nullable();
            $table->text('documentation_requirements')->nullable();
            $table->text('assessment_criteria')->nullable();
            $table->json('references')->nullable();
            $table->integer('version')->default(1);
            $table->date('review_date')->nullable();
            $table->enum('status', ['draft', 'under_review', 'approved', 'published', 'archived'])->default('draft');
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'is_active']);
            $table->index(['status', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_skills');
    }
};
