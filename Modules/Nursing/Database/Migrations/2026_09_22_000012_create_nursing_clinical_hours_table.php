<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursing_clinical_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('academic_students')->onDelete('cascade');
            $table->foreignId('placement_id')->constrained('nursing_placements')->onDelete('cascade');
            $table->date('date');
            $table->decimal('hours', 4, 1);
            $table->string('shift')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('logbook_id')->nullable()->constrained('nursing_logbooks')->onDelete('set null');
            $table->timestamps();

            $table->index(['school_id', 'student_id', 'status']);
            $table->index(['placement_id', 'status']);
            $table->index(['date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_clinical_hours');
    }
};
