<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedule_snapshots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->unsignedBigInteger('timetable_id')->nullable();
            $table->json('data');
            $table->string('action'); // create, update, delete
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('schedule_id')->references('id')->on('class_schedules')->nullOnDelete();
            $table->foreign('timetable_id')->references('id')->on('timetables')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedule_snapshots');
    }
}; 