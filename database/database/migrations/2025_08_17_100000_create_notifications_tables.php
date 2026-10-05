<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('notification_templates')) {
            Schema::create('notification_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('channel'); // email|sms|whatsapp
                $table->string('subject')->nullable(); // for email/whatsapp
                $table->text('body'); // blade-like with {{variable}}
                $table->json('variables')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('notification_jobs')) {
            Schema::create('notification_jobs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('template_id');
                $table->string('channel');
                $table->string('recipient');
                $table->json('payload')->nullable();
                $table->timestamp('scheduled_at')->nullable();
                $table->string('status')->default('queued'); // queued|sent|failed|retrying
                $table->unsignedSmallInteger('attempts')->default(0);
                $table->text('last_error')->nullable();
                $table->timestamp('next_run_at')->nullable();
                $table->string('dedup_key', 191)->nullable();
                $table->timestamps();
                $table->index(['template_id','channel','status']);
                $table->index(['scheduled_at']);
                $table->index(['next_run_at']);
                $table->unique('dedup_key');
            });
        }

        if (!Schema::hasTable('notification_logs')) {
            Schema::create('notification_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('template_id')->nullable();
                $table->string('channel');
                $table->string('recipient');
                $table->json('payload')->nullable();
                $table->text('response')->nullable();
                $table->string('status')->default('sent');
                $table->timestamp('sent_at')->nullable();
                $table->string('dedup_key', 191)->nullable();
                $table->timestamps();
                $table->index(['template_id','channel','recipient']);
                $table->index(['sent_at']);
                $table->index(['dedup_key']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('notification_jobs');
        Schema::dropIfExists('notification_templates');
    }
};


