<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->string('name'); // e.g., "Part A", "Part B", "Section 1"
            $table->text('description')->nullable();
            $table->integer('order')->default(1);
            $table->decimal('total_marks', 8, 2);
            $table->integer('question_count')->default(0);
            $table->boolean('is_optional')->default(false);
            $table->json('settings')->nullable(); // Additional section settings
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_sections');
    }
};
