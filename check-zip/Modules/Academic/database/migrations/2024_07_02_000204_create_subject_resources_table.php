<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('academic_subjects')->onDelete('cascade');
            $table->string('type'); // file, link, etc.
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('file_path')->nullable();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('subject_resources');
    }
}; 