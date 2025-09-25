<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hostel_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('bed_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('reported_by');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->string('issue_type');
            $table->text('description');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
            $table->timestamp('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
            $table->foreign('bed_id')->references('id')->on('beds')->onDelete('set null');
            // student_id, reported_by, assigned_to should reference users table in main app
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_issues');
    }
}; 