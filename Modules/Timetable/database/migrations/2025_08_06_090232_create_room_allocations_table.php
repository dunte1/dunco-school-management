<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('room_allocations')) {
            Schema::create('room_allocations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('room_id');
                $table->unsignedBigInteger('class_schedule_id');
                $table->date('allocation_date');
                $table->timestamps();

                $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
                $table->foreign('class_schedule_id')->references('id')->on('class_schedules')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_allocations');
    }
};
