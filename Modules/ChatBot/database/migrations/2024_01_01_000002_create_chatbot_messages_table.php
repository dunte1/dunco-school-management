<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chatbot_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->text('content');
            $table->enum('role', ['user', 'assistant', 'system'])->default('user');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('conversation_id')->references('id')->on('chatbot_conversations')->onDelete('cascade');
            $table->index(['conversation_id', 'created_at']);
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chatbot_messages');
    }
}; 