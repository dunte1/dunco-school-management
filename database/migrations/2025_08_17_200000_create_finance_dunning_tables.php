<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_dunning_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('criteria')->nullable(); // e.g., {"min_days_past_due":30}
            $table->json('cadence')->nullable(); // e.g., [3,7,14] days offsets
            $table->unsignedBigInteger('template_id');
            $table->string('channel'); // email|sms|whatsapp
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active']);
        });

        Schema::create('finance_dunning_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rule_id');
            $table->unsignedBigInteger('invoice_id');
            $table->date('due_on'); // when to trigger next
            $table->string('status')->default('pending'); // pending|queued|sent|skipped
            $table->timestamps();
            $table->index(['rule_id','invoice_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_dunning_events');
        Schema::dropIfExists('finance_dunning_rules');
    }
};


