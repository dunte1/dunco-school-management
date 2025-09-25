<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('finance_role_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('finance_role_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_role_user');
    }
}; 