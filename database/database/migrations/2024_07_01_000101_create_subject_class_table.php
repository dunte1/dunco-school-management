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
        // Check if the subject_class table already exists
        if (Schema::hasTable('subject_class')) {
            return;
        }

        Schema::create('subject_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('academic_classes')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['subject_id', 'class_id']);
            $table->index('subject_id');
            $table->index('class_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_class');
    }
};
