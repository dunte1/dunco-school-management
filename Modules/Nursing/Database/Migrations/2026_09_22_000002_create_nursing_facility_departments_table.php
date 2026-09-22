<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursing_facility_departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained('nursing_facilities')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['facility_id', 'code']);
            $table->index(['facility_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_facility_departments');
    }
};
