<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brandings', function (Blueprint $table) {
            if (!Schema::hasColumn('brandings', 'secondary_color')) {
                $table->string('secondary_color', 32)->nullable()->after('primary_color');
            }
            if (!Schema::hasColumn('brandings', 'text_color')) {
                $table->string('text_color', 32)->nullable()->after('secondary_color');
            }
        });
    }

    public function down(): void
    {
        Schema::table('brandings', function (Blueprint $table) {
            if (Schema::hasColumn('brandings', 'text_color')) {
                $table->dropColumn('text_color');
            }
            if (Schema::hasColumn('brandings', 'secondary_color')) {
                $table->dropColumn('secondary_color');
            }
        });
    }
};





