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
        Schema::create('hostel_students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('student_id')->unique();
            $table->string('email')->unique();
            $table->string('phone');
            $table->unsignedBigInteger('hostel_id');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->date('check_in_date');
            $table->date('check_out_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'graduated', 'transferred'])->default('active');
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('hostel_id')->references('id')->on('hostels')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

            // Indexes
            $table->index(['status']);
            $table->index(['hostel_id']);
            $table->index(['room_id']);
            $table->index(['created_by']);
            $table->index(['school_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostel_students');
    }
};