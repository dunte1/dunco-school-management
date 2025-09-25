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
        if (!Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->integer('capacity');
                $table->string('location');
                $table->string('type'); // Lecture Room, Lab, Hall, Studio, etc.
                $table->text('equipment')->nullable(); // JSON or comma-separated list: Projector, Computers, etc.
                $table->text('availability_time')->nullable(); // JSON or string, nullable, for maintenance/unavailable times
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
