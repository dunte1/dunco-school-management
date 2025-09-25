<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('fee_program', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fee_id');
            $table->unsignedBigInteger('program_id');
            $table->timestamps();

            $table->foreign('fee_id')->references('id')->on('fees')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('fee_program');
    }
}; 