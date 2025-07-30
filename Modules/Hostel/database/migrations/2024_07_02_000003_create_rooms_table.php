<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hostel_id');
            $table->unsignedBigInteger('floor_id');
            $table->string('name');
            $table->enum('type', ['single', 'double', 'triple']);
            $table->boolean('ac')->default(false);
            $table->boolean('ensuite')->default(false);
            $table->unsignedInteger('capacity');
            $table->json('amenities')->nullable();
            $table->string('layout_image')->nullable();
            $table->decimal('price_per_bed', 10, 2)->nullable();
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved'])->default('available');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('hostel_id')->references('id')->on('hostels')->onDelete('cascade');
            $table->foreign('floor_id')->references('id')->on('floors')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
}; 