<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->boolean('is_published')->default(false)->after('is_active');
        });

        // Update existing records to set is_published based on published_at
        \DB::statement('UPDATE announcements SET is_published = 1 WHERE published_at IS NOT NULL AND published_at <= NOW()');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('is_published');
        });
    }
};
