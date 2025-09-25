<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->decimal('amount', 12, 2);
            $table->date('payment_date');
            $table->string('method')->nullable(); // cash, bank, online, etc.
            $table->string('status')->default('completed'); // completed, pending, failed
            $table->string('reference')->nullable();
            $table->string('mpesa_transaction_code')->nullable();
            $table->string('mpesa_phone')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}; 