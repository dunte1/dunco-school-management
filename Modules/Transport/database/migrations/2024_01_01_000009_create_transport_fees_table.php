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
        Schema::create('transport_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('transport_students')->onDelete('cascade');
            $table->foreignId('route_id')->constrained('routes')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->enum('fee_type', ['monthly', 'quarterly', 'annual', 'special'])->default('monthly');
            $table->text('description')->nullable();
            $table->decimal('late_fee', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'paid', 'overdue', 'waived'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('cascade');
            $table->timestamps();

            $table->index(['status', 'due_date']);
            $table->index(['student_id', 'status']);
            $table->index(['route_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_fees');
    }
};
