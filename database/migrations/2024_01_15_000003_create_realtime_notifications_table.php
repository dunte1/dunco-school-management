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
        Schema::create('realtime_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('channel');
            $table->string('event');
            $table->text('message');
            $table->json('data')->nullable();
            $table->unsignedBigInteger('sender_id');
            $table->json('target_users')->nullable();
            $table->boolean('sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->index(['channel', 'event']);
            $table->index(['sender_id', 'created_at']);
            $table->index('sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realtime_notifications');
    }
};
