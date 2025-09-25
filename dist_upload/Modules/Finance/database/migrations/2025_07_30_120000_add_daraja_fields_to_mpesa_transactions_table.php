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
        Schema::table('mpesa_transactions', function (Blueprint $table) {
            // Add missing fields for Daraja API
            if (!Schema::hasColumn('mpesa_transactions', 'transaction_type')) {
                $table->string('transaction_type')->default('stk_push'); // stk_push, c2b, b2c, reversal
            }
            
            if (!Schema::hasColumn('mpesa_transactions', 'checkout_request_id')) {
                $table->string('checkout_request_id')->nullable();
            }
            
            if (!Schema::hasColumn('mpesa_transactions', 'mpesa_receipt_number')) {
                $table->string('mpesa_receipt_number')->nullable();
            }
            
            if (!Schema::hasColumn('mpesa_transactions', 'transaction_date')) {
                $table->string('transaction_date')->nullable();
            }
            
            if (!Schema::hasColumn('mpesa_transactions', 'result_code')) {
                $table->integer('result_code')->nullable();
            }
            
            if (!Schema::hasColumn('mpesa_transactions', 'result_desc')) {
                $table->text('result_desc')->nullable();
            }
            
            if (!Schema::hasColumn('mpesa_transactions', 'conversation_id')) {
                $table->string('conversation_id')->nullable();
            }
            
            if (!Schema::hasColumn('mpesa_transactions', 'originator_conversation_id')) {
                $table->string('originator_conversation_id')->nullable();
            }
            
            if (!Schema::hasColumn('mpesa_transactions', 'merchant_request_id')) {
                $table->string('merchant_request_id')->nullable();
            }
            
            if (!Schema::hasColumn('mpesa_transactions', 'completed_at')) {
                $table->timestamp('completed_at')->nullable();
            }
            
            if (!Schema::hasColumn('mpesa_transactions', 'failed_at')) {
                $table->timestamp('failed_at')->nullable();
            }
            
            if (!Schema::hasColumn('mpesa_transactions', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('mpesa_transactions', 'fee_id')) {
                $table->foreignId('fee_id')->nullable()->constrained('fees')->onDelete('cascade');
            }
            
            // Add indexes for better performance
            $table->index(['transaction_type', 'status']);
            $table->index(['checkout_request_id']);
            $table->index(['mpesa_receipt_number']);
            $table->index(['account_reference']);
            $table->index(['phone_number']);
            $table->index(['user_id']);
            $table->index(['fee_id']);
            $table->index(['completed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpesa_transactions', function (Blueprint $table) {
            // Remove indexes
            $table->dropIndex(['transaction_type', 'status']);
            $table->dropIndex(['checkout_request_id']);
            $table->dropIndex(['mpesa_receipt_number']);
            $table->dropIndex(['account_reference']);
            $table->dropIndex(['phone_number']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['fee_id']);
            $table->dropIndex(['completed_at']);
            
            // Remove columns
            $table->dropColumn([
                'transaction_type',
                'checkout_request_id',
                'mpesa_receipt_number',
                'transaction_date',
                'result_code',
                'result_desc',
                'conversation_id',
                'originator_conversation_id',
                'merchant_request_id',
                'completed_at',
                'failed_at',
                'user_id',
                'fee_id'
            ]);
        });
    }
}; 