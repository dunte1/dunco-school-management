<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('academic_subject_teacher', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();

            $table->foreign('subject_id')->references('id')->on('academic_subjects')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['subject_id', 'teacher_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('academic_subject_teacher');
    }
}; 