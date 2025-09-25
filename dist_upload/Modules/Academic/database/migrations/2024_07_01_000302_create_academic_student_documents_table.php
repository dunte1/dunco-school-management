<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('academic_student_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('academic_students')->onDelete('cascade');
            $table->string('type');
            $table->string('file_path');
            $table->timestamp('uploaded_at')->nullable();
            $table->string('status')->default('pending');
            $table->string('review_note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('academic_student_documents');
    }
}; 