<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('amount', 12, 2);
            $table->string('description')->nullable();
            $table->string('reference')->nullable();
            $table->string('status')->default('unreconciled'); // unreconciled, reconciled, flagged
            $table->unsignedBigInteger('matched_payment_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bank_transactions');
    }
}; 