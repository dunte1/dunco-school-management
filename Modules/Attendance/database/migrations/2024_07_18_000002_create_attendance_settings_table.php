<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendance_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->time('default_marking_start')->nullable();
            $table->time('default_marking_end')->nullable();
            $table->time('late_threshold')->nullable();
            $table->boolean('allow_backdated_entries')->default(false);
            $table->boolean('teacher_can_backdate')->default(false);
            $table->integer('min_attendance_percent')->nullable(); // for exam eligibility
            $table->boolean('notify_absent')->default(true);
            $table->boolean('notify_late')->default(true);
            $table->string('notify_channel')->default('email'); // email, sms, both
            $table->integer('chronic_absent_threshold')->nullable();
            $table->text('custom_message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_settings');
    }
}; 