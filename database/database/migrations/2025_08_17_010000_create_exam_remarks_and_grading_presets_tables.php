<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_remarks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id')->index();
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->enum('status', ['requested', 'reviewing', 'resolved'])->default('requested');
            $table->text('remark')->nullable();
            $table->text('resolution')->nullable();
            $table->timestamps();
        });

        Schema::create('grading_presets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable()->index();
            $table->string('name');
            $table->json('bands'); // e.g. [{"min":80,"max":100,"grade":"A"}, ...]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('grading_preset_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grading_preset_id')->index();
            $table->unsignedBigInteger('class_id')->nullable()->index();
            $table->unsignedBigInteger('exam_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grading_preset_assignments');
        Schema::dropIfExists('grading_presets');
        Schema::dropIfExists('exam_remarks');
    }
};


