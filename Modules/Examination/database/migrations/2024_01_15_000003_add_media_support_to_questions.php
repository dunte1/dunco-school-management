<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->json('media_files')->nullable()->after('metadata'); // Store media file information
            $table->boolean('has_media')->default(false)->after('file_upload');
            $table->enum('media_type', ['image', 'audio', 'video', 'document', 'none'])->default('none')->after('has_media');
            $table->integer('media_duration_seconds')->nullable()->after('media_type'); // For audio/video
            $table->json('media_settings')->nullable()->after('media_duration_seconds'); // Additional media settings
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn([
                'media_files',
                'has_media',
                'media_type',
                'media_duration_seconds',
                'media_settings'
            ]);
        });
    }
};
