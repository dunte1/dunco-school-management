<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add school_id to settings (idempotent).
     *
     * Renamed from 2024_06_28_100000 so it runs after create_schools_table
     * (same timestamp previously caused an FK to a not-yet-created table on MySQL).
     */
    public function up(): void
    {
        if (! Schema::hasTable('settings') || ! Schema::hasTable('schools')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'school_id')) {
                $table->unsignedBigInteger('school_id')->nullable();
                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('settings') && Schema::hasColumn('settings', 'school_id')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }
    }
};
