<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('licenses')) {
            Schema::create('licenses', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->nullable()->index();
                $table->string('plan')->default('basic');
                $table->string('status')->default('active'); // active, suspended, expired
                $table->integer('seats')->default(0);
                $table->json('features')->nullable();
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('expires_at')->nullable()->index();
                $table->timestamps();

                // Foreign key optional to avoid sqlite constraint issues
                // $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
