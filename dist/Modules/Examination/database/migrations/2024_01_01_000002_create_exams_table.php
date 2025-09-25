<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->foreignId('exam_type_id')->constrained('exam_types');
            $table->string('academic_year');
            $table->string('term');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->decimal('total_marks', 8, 2)->default(0);
            $table->decimal('passing_marks', 8, 2)->default(0);
            $table->boolean('is_online')->default(false);
            $table->boolean('enable_proctoring')->default(false);
            $table->boolean('shuffle_questions')->default(false);
            $table->boolean('shuffle_options')->default(false);
            $table->boolean('show_results_immediately')->default(false);
            $table->boolean('allow_review')->default(true);
            $table->boolean('is_active')->default(true);
            $table->enum('status', ['draft', 'published', 'ongoing', 'completed', 'archived'])->default('draft');
            $table->json('settings')->nullable(); // Additional settings
            $table->decimal('negative_marking', 5, 2)->nullable();
            $table->boolean('proctor_webcam')->default(false);
            $table->boolean('proctor_tab_switch')->default(false);
            $table->boolean('proctor_face_detection')->default(false);
            $table->integer('proctor_idle_timeout')->nullable();
            $table->boolean('allow_retake')->default(false);
            $table->integer('max_attempts')->nullable();
            $table->string('retake_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exams');
    }
}; 