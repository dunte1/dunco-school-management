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
        if (Schema::hasTable('subjects')) {
            return;
        }

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('class')->nullable();
            $table->string('section')->nullable();
            $table->integer('credits')->default(1);
            $table->string('type')->default('theory'); // theory, practical, lab
            $table->integer('total_periods_per_week')->default(5);
            $table->time('period_duration')->default('00:45:00');
            $table->boolean('is_active')->default(true);
            $table->string('subject_group')->nullable(); // science, arts, commerce
            $table->integer('pass_marks')->default(35);
            $table->integer('full_marks')->default(100);
            $table->json('syllabus')->nullable();
            $table->string('textbook')->nullable();
            $table->string('reference_books')->nullable();
            $table->timestamps();

            $table->index(['class', 'section']);
            $table->index('teacher_id');
            $table->index('is_active');
            $table->index('subject_group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
