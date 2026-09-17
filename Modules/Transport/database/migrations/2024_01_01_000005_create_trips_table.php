<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('route_id');
            $table->date('trip_date');
            $table->datetime('start_time');
            $table->datetime('end_time')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->integer('passenger_count')->default(0);
            $table->decimal('fuel_consumed', 8, 2)->nullable();
            $table->decimal('distance_covered', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('trips');
    }
}; 
