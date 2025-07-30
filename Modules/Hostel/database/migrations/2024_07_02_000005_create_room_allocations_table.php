<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bed_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('allocated_by')->nullable();
            $table->enum('allocation_type', ['auto', 'manual', 'request'])->default('manual');
            $table->enum('status', ['active', 'checked_out', 'swapped', 'cancelled'])->default('active');
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->text('notes')->nullable();
            $table->string('document')->nullable();
            $table->timestamps();

            $table->foreign('bed_id')->references('id')->on('beds')->onDelete('cascade');
            // student_id and allocated_by should reference users table in main app
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_allocations');
    }
}; 