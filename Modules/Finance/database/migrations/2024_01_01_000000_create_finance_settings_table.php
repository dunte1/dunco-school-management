<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('finance_settings', function (Blueprint $table) {
            $table->id();
            $table->json('settings');
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_settings');
    }
}; 