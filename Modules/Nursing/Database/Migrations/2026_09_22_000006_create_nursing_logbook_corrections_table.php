<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nursing_logbook_corrections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logbook_id')->constrained('nursing_logbooks')->onDelete('cascade');
            $table->foreignId('corrected_by')->constrained('users')->onDelete('cascade');
            $table->json('previous_values');
            $table->json('new_values');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['logbook_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nursing_logbook_corrections');
    }
};
