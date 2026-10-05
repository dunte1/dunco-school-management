<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_alert_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->nullable()->index();
            $table->string('name');
            // Types: absent_consecutive, late_count
            $table->string('type');
            $table->unsignedInteger('threshold');
            $table->unsignedInteger('window_days')->default(7);
            $table->enum('channel', ['email','sms','both'])->default('sms');
            $table->string('template_name')->default('attendance_status_alert');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_alert_rules');
    }
};





