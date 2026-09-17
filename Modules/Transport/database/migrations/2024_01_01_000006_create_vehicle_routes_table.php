<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicle_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('route_id');
            $table->timestamps();

            
            $table->unique(['vehicle_id', 'route_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicle_routes');
    }
}; 
