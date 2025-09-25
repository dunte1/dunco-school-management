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
        Schema::create('chatbot_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('conversation_id')->nullable();
            $table->string('filename');
            $table->string('original_filename');
            $table->string('file_path');
            $table->bigInteger('file_size');
            $table->string('file_type');
            $table->string('mime_type');
            $table->text('description')->nullable();
            $table->longText('content_text')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_processed')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('conversation_id')->references('id')->on('chatbot_conversations')->onDelete('cascade');
            $table->index(['user_id', 'is_processed']);
            $table->index('file_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chatbot_documents');
    }
};
