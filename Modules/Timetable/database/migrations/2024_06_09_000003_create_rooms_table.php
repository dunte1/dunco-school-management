<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('capacity');
            $table->string('location')->nullable();
            $table->string('type')->nullable(); // Lecture Room, Lab, Hall, Studio, etc.
            $table->text('equipment')->nullable(); // JSON or comma-separated list: Projector, Computers, etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}; 