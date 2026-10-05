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
        Schema::create('academic_exam_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('academic_exams')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('academic_classes')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['exam_id', 'class_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_exam_class');
    }
}; 