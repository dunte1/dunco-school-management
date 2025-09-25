<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hostel_announcements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hostel_id');
            $table->unsignedBigInteger('warden_id');
            $table->string('title');
            $table->text('message');
            $table->string('attachment')->nullable();
            $table->enum('audience', ['all', 'residents', 'staff'])->default('all');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->foreign('hostel_id')->references('id')->on('hostels')->onDelete('cascade');
            // warden_id should reference users table in main app
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_announcements');
    }
};
