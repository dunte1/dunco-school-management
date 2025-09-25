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
        if (! Schema::hasTable('proctoring_logs')) {
            return;
        }

        Schema::table('proctoring_logs', function (Blueprint $table) {
            if (! Schema::hasColumn('proctoring_logs', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('proctoring_logs')) {
            return;
        }

        Schema::table('proctoring_logs', function (Blueprint $table) {
            if (Schema::hasColumn('proctoring_logs', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
