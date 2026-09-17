<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wardens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('hostel_id');
            $table->json('assigned_blocks')->nullable();
            $table->string('contact')->nullable();
            $table->enum('role', ['warden', 'security_guard'])->default('warden');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            // user_id should reference users table in main app
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wardens');
    }
};
