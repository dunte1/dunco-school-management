<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Messages
        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sender_id');
                $table->string('subject')->nullable();
                $table->text('body');
                $table->boolean('is_group')->default(false);
                $table->unsignedBigInteger('group_id')->nullable();
                $table->timestamp('scheduled_at')->nullable();
                $table->timestamps();
            });
        }
        
        // Message Recipients
        if (!Schema::hasTable('message_recipients')) {
            Schema::create('message_recipients', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('message_id');
                $table->unsignedBigInteger('recipient_id');
                $table->boolean('is_read')->default(false);
                $table->timestamp('read_at')->nullable();
                $table->boolean('is_starred')->default(false);
                $table->boolean('is_deleted')->default(false);
                $table->timestamps();
            });
        }
        
        // Communication Templates
        if (!Schema::hasTable('communication_templates')) {
            Schema::create('communication_templates', function (Blueprint $table) {
                $table->id();
                $table->string('type'); // email, sms, etc.
                $table->string('name');
                $table->text('body');
                $table->timestamps();
            });
        }
        
        // Attachments
        if (!Schema::hasTable('attachments')) {
            Schema::create('attachments', function (Blueprint $table) {
                $table->id();
                $table->string('file_path');
                $table->string('file_name');
                $table->string('attachable_type');
                $table->unsignedBigInteger('attachable_id');
                $table->timestamps();
            });
        }
        
        // Notification Logs
        if (!Schema::hasTable('notification_logs')) {
            Schema::create('notification_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('notification_id');
                $table->string('status');
                $table->text('response')->nullable();
                $table->timestamps();
            });
        }
        
        // Message Delivery Logs
        if (!Schema::hasTable('message_delivery_logs')) {
            Schema::create('message_delivery_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('message_id');
                $table->unsignedBigInteger('recipient_id');
                $table->string('channel'); // email, sms, push
                $table->string('status'); // sent, failed, pending
                $table->text('response')->nullable();
                $table->timestamps();
            });
        }
        
        // Groups
        if (!Schema::hasTable('groups')) {
            Schema::create('groups', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->unsignedBigInteger('creator_id');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
        
        // Group Members
        if (!Schema::hasTable('group_members')) {
            Schema::create('group_members', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('group_id');
                $table->unsignedBigInteger('user_id');
                $table->enum('role', ['admin', 'member'])->default('member');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
        
        // Broadcasts/Announcements
        if (!Schema::hasTable('broadcasts')) {
            Schema::create('broadcasts', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content');
                $table->enum('type', ['announcement', 'emergency', 'reminder', 'notice']);
                $table->enum('target_type', ['all', 'role', 'class', 'group', 'individual']);
                $table->json('target_data')->nullable(); // Store target IDs
                $table->unsignedBigInteger('created_by');
                $table->timestamp('scheduled_at')->nullable();
                $table->timestamp('sent_at')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
        
        // Broadcast Recipients (for tracking reads)
        if (!Schema::hasTable('broadcast_recipients')) {
            Schema::create('broadcast_recipients', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('broadcast_id');
                $table->unsignedBigInteger('user_id');
                $table->boolean('is_read')->default(false);
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
        
        // Notifications
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->string('type'); // academic, finance, events, system, communication
                $table->string('title');
                $table->text('data'); // JSON data
                $table->unsignedBigInteger('notifiable_id');
                $table->string('notifiable_type');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }
        
        // User Notification Preferences
        if (!Schema::hasTable('user_notification_preferences')) {
            Schema::create('user_notification_preferences', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->string('channel'); // email, sms, push, in_app
                $table->string('category'); // academic, finance, events, system, communication
                $table->boolean('enabled')->default(true);
                $table->boolean('sound')->default(true);
                $table->boolean('vibration')->default(true);
                $table->timestamps();
            });
        }
        
        // Notification Templates
        if (!Schema::hasTable('notification_templates')) {
            Schema::create('notification_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type'); // academic, finance, events, system
                $table->string('title_template');
                $table->text('body_template');
                $table->json('variables')->nullable(); // Template variables
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('group_members');
        Schema::dropIfExists('groups');
        Schema::dropIfExists('message_delivery_logs');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('attachments');
        Schema::dropIfExists('communication_templates');
        Schema::dropIfExists('message_recipients');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('broadcasts');
        Schema::dropIfExists('broadcast_recipients');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('user_notification_preferences');
        Schema::dropIfExists('notification_templates');
    }
}; 