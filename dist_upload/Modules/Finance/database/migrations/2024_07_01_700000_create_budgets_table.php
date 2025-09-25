<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('period'); // e.g., 2024-07 or 2024
            $table->decimal('amount', 12, 2);
            $table->string('type'); // income or expense
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('budgets');
    }
}; 