<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_number')->unique();
            $table->enum('vehicle_type', ['bus', 'van', 'car', 'minibus']);
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->integer('capacity');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->enum('status', ['active', 'maintenance', 'inactive'])->default('active');
            $table->string('registration_number')->unique();
            $table->date('insurance_expiry');
            $table->date('fitness_expiry');
            $table->date('permit_expiry');
            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid']);
            $table->integer('mileage')->default(0);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('driver_id')->references('id')->on('drivers')->onDelete('set null');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}; 