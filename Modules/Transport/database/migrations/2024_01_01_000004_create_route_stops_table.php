<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('route_stops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_id');
            $table->string('stop_name');
            $table->string('stop_location');
            $table->integer('stop_order');
            $table->time('pickup_time')->nullable();
            $table->time('drop_time')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::dropIfExists('route_stops');
    }
}; 
