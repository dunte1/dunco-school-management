<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hostel_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hostel_id');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('bed_id')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['paid', 'unpaid', 'overdue'])->default('unpaid');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->decimal('fine', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // student_id should reference users table in main app
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_fees');
    }
}; 
