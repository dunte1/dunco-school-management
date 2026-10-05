<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_two_factors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('method', ['sms', 'email', 'app'])->default('email');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->json('backup_codes')->nullable();
            $table->string('secret_key')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'is_enabled']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_two_factors');
    }
};