<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('salary_range')->nullable();
            $table->enum('level', ['entry', 'mid', 'senior', 'management', 'executive'])->default('entry');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('school_id')->nullable();
            $table->timestamps();
            
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('positions');
    }
};
