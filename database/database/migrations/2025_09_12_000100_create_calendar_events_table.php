<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('calendar_events')) {
            Schema::create('calendar_events', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->string('title');
                $table->text('description')->nullable();
                $table->dateTime('starts_at');
                $table->dateTime('ends_at')->nullable();
                $table->string('location')->nullable();
                $table->string('visibility')->default('all'); // all | staff | students
                $table->timestamps();
                $table->index(['starts_at','ends_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('calendar_events');
    }
};
