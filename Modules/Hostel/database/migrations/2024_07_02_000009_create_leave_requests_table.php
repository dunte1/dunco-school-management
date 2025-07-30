<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('warden_id')->nullable();
            $table->string('reason');
            $table->date('from_date');
            $table->date('to_date');
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->string('emergency_contact')->nullable();
            $table->boolean('guardian_notified')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            // student_id and warden_id should reference users table in main app
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
}; 