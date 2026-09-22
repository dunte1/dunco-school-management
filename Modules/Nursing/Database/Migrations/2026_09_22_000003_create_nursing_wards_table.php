<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursing_wards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained('nursing_facilities')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('nursing_facility_departments')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('floor')->nullable();
            $table->integer('capacity')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['facility_id', 'code']);
            $table->index(['facility_id', 'department_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_wards');
    }
};
