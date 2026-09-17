<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('start_location');
            $table->string('end_location');
            $table->decimal('distance', 8, 2); // in kilometers
            $table->integer('estimated_time'); // in minutes
            $table->decimal('fare', 10, 2);
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('school_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('routes');
    }
}; 
