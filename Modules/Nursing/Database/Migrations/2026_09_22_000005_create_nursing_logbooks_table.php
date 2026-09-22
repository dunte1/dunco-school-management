<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursing_logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('academic_students')->onDelete('cascade');
            $table->foreignId('placement_id')->constrained('nursing_placements')->onDelete('cascade');
            $table->date('date');
            $table->string('shift')->nullable();
            $table->decimal('hours', 4, 1)->default(0);
            $table->text('activity')->nullable();
            $table->text('procedure')->nullable();
            $table->text('learning_objective')->nullable();
            $table->text('reflection')->nullable();
            $table->text('challenges')->nullable();
            $table->text('evidence')->nullable();
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'returned', 'rejected'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('review_comments')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'student_id', 'status']);
            $table->index(['placement_id', 'status']);
            $table->index(['reviewed_by', 'status']);
            $table->index(['date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_logbooks');
    }
};
