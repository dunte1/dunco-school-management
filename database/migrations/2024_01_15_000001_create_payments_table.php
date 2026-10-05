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
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_ref')->unique();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('student_id')->nullable();
                $table->unsignedBigInteger('fee_id')->nullable();
                $table->decimal('amount', 10, 2);
                $table->decimal('fees', 10, 2)->default(0);
                $table->decimal('total_amount', 10, 2);
                $table->string('payment_method');
                $table->string('status')->default('pending');
                $table->string('payment_reference')->nullable();
                $table->decimal('amount_received', 10, 2)->nullable();
                $table->text('description')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('fee_id')->references('id')->on('fees')->onDelete('set null');
                
                $table->index(['user_id', 'status']);
                $table->index(['student_id', 'status']);
                $table->index('transaction_ref');
                $table->index('created_at');
            });
        } else {
            // Table exists, add missing columns
            $this->addMissingColumns();
        }
    }

    /**
     * Add missing columns to existing payments table
     */
    private function addMissingColumns(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Check and add columns that might be missing
            if (!Schema::hasColumn('payments', 'fees')) {
                $table->decimal('fees', 10, 2)->default(0)->after('amount');
            }
            if (!Schema::hasColumn('payments', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('status');
            }
            if (!Schema::hasColumn('payments', 'amount_received')) {
                $table->decimal('amount_received', 10, 2)->nullable()->after('payment_reference');
            }
            if (!Schema::hasColumn('payments', 'description')) {
                $table->text('description')->nullable()->after('amount_received');
            }
            if (!Schema::hasColumn('payments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
