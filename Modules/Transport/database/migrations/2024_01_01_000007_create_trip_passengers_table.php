<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('trip_passengers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id');
            $table->unsignedBigInteger('student_id');
            $table->enum('status', ['scheduled', 'boarded', 'dropped', 'absent'])->default('scheduled');
            $table->datetime('pickup_time')->nullable();
            $table->datetime('drop_time')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            
            $table->unique(['trip_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('trip_passengers');
    }
}; 
