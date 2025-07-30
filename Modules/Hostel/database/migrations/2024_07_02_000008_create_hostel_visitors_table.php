<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hostel_visitors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hostel_id');
            $table->unsignedBigInteger('student_id');
            $table->string('visitor_name');
            $table->string('visitor_contact')->nullable();
            $table->string('purpose')->nullable();
            $table->timestamp('time_in');
            $table->timestamp('time_out')->nullable();
            $table->string('pass_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('hostel_id')->references('id')->on('hostels')->onDelete('cascade');
            // student_id should reference users table in main app
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_visitors');
    }
}; 