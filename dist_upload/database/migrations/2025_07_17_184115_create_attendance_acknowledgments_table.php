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
        Schema::create('attendance_acknowledgments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_record_id');
            $table->unsignedBigInteger('parent_id');
            $table->timestamp('acknowledged_at')->nullable();
            $table->string('channel')->nullable();
            $table->timestamps();

            $table->foreign('attendance_record_id')->references('id')->on('academic_attendance_records')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_acknowledgments');
    }
};
