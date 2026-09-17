<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teacher_availabilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->string('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_availabilities');
    }
}; 
