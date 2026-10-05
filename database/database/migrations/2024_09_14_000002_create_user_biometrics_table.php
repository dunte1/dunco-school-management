<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_biometrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_id');
            $table->enum('biometric_type', ['fingerprint', 'face', 'voice']);
            $table->text('biometric_data'); // Encrypted biometric template
            $table->text('public_key'); // For encryption/decryption
            $table->boolean('is_enabled')->default(true);
            $table->timestamp('last_used')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'device_id', 'biometric_type']);
            $table->index(['device_id', 'biometric_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_biometrics');
    }
};