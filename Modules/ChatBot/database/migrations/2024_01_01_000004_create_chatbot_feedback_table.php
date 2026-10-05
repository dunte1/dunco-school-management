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
        Schema::create('chatbot_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('conversation_id')->nullable();
            $table->unsignedBigInteger('message_id')->nullable();
            $table->integer('rating')->comment('1-5 star rating');
            $table->text('comment')->nullable();
            $table->string('feedback_type')->default('message')->comment('message, conversation, general');
            $table->string('sentiment')->nullable()->comment('positive, negative, neutral');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('conversation_id')->references('id')->on('chatbot_conversations')->onDelete('cascade');
            $table->foreign('message_id')->references('id')->on('chatbot_messages')->onDelete('cascade');
            
            $table->index(['user_id', 'created_at']);
            $table->index(['conversation_id', 'created_at']);
            $table->index(['rating', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_feedback');
    }
};