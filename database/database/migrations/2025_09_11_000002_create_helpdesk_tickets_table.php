<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('helpdesk_tickets')) {
            Schema::create('helpdesk_tickets', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('school_id')->nullable()->index();
                $table->string('subject');
                $table->text('description')->nullable();
                $table->string('status')->default('open'); // open, pending, in_progress, resolved, closed
                $table->string('priority')->default('medium'); // low, medium, high, critical
                $table->unsignedBigInteger('assigned_to')->nullable()->index(); // users.id
                $table->unsignedBigInteger('created_by')->nullable()->index(); // users.id
                $table->timestamp('closed_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('helpdesk_tickets');
    }
};
