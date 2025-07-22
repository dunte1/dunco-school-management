<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('attendance_settings', function (Blueprint $table) {
            $table->boolean('notify_absent')->default(true);
            $table->boolean('notify_late')->default(true);
            $table->string('notify_channel')->default('email'); // email, sms, both
            $table->integer('chronic_absent_threshold')->nullable();
            $table->text('custom_message')->nullable();
        });
    }

    public function down()
    {
        Schema::table('attendance_settings', function (Blueprint $table) {
            $table->dropColumn(['notify_absent', 'notify_late', 'notify_channel', 'chronic_absent_threshold', 'custom_message']);
        });
    }
}; 