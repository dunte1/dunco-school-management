<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->string('asset_type');
            $table->string('asset_tag')->nullable();
            $table->string('description')->nullable();
            $table->date('assigned_at')->nullable();
            $table->date('returned_at')->nullable();
            $table->boolean('is_returned')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assets');
    }
}; 