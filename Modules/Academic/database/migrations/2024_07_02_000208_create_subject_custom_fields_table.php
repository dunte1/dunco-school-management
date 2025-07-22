<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectCustomFieldsTable extends Migration
{
    public function up(): void
    {
        Schema::create('subject_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('academic_subjects')->onDelete('cascade');
            $table->string('field_name');
            $table->string('field_value')->nullable();
            $table->string('field_type')->default('string');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_custom_fields');
    }
} 