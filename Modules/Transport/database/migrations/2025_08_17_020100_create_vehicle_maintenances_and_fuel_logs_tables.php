<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('vehicle_maintenances')) {
        Schema::create('vehicle_maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id')->index();
            $table->string('type'); // service, repair, tires, etc
            $table->date('date');
            $table->decimal('cost', 12, 2)->default(0);
            $table->unsignedInteger('odometer')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        }

        if (!Schema::hasTable('fuel_logs')) {
        Schema::create('fuel_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id')->index();
            $table->date('date');
            $table->decimal('liters', 10, 2);
            $table->decimal('cost', 12, 2);
            $table->unsignedInteger('odometer')->nullable();
            $table->timestamps();
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_logs');
        Schema::dropIfExists('vehicle_maintenances');
    }
};


