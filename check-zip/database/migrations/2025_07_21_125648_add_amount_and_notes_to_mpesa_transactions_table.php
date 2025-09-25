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
            $table->decimal('amount', 12, 2)->after('checkout_request_id');
            $table->text('notes')->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mpesa_transactions', function (Blueprint $table) {
            $table->dropColumn(['amount', 'notes']);
        });
    }
};
