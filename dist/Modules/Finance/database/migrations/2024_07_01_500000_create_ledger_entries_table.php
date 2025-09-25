<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('account');
            $table->string('type'); // e.g., asset, liability, income, expense
            $table->string('description')->nullable();
            $table->decimal('debit', 12, 2)->default(0);
            $table->decimal('credit', 12, 2)->default(0);
            $table->string('reference')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->string('related_type')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ledger_entries');
    }
}; 