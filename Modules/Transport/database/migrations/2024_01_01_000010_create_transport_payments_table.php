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
        Schema::create('transport_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('transport_students')->onDelete('cascade');
            $table->foreignId('fee_id')->nullable()->constrained('transport_fees')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->date('payment_date');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'cheque', 'card', 'online'])->default('cash');
            $table->string('transaction_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['successful', 'failed', 'pending', 'cancelled'])->default('successful');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('cascade');
            $table->timestamps();

            $table->index(['student_id', 'payment_date']);
            $table->index(['fee_id', 'status']);
            $table->index(['payment_method', 'status']);
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_payments');
    }
};
