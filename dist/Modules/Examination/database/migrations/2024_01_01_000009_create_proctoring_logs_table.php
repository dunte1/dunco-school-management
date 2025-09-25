<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('proctoring_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_attempt_id')->constrained('exam_attempts')->onDelete('cascade');
            $table->enum('event_type', [
                'tab_switch', 'window_focus', 'copy_paste', 'right_click', 
                'keyboard_shortcut', 'multiple_faces', 'no_face', 'voice_detected',
                'screen_share', 'browser_dev_tools', 'suspicious_activity'
            ]);
            $table->text('description');
            $table->json('event_data')->nullable(); // Detailed event information
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('low');
            $table->boolean('is_resolved')->default(false);
            $table->text('resolution_notes')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users');
            $table->datetime('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proctoring_logs');
    }
}; 