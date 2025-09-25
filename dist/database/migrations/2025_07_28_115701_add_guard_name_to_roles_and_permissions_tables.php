<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'guard_name')) {
                $table->string('guard_name')->default('web');
            }
        });
        Schema::table('permissions', function (Blueprint $table) {
            if (!Schema::hasColumn('permissions', 'guard_name')) {
                $table->string('guard_name')->default('web');
            }
        });
    }

    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            if (Schema::hasColumn('roles', 'guard_name')) {
                $table->dropColumn('guard_name');
            }
        });
        Schema::table('permissions', function (Blueprint $table) {
            if (Schema::hasColumn('permissions', 'guard_name')) {
                $table->dropColumn('guard_name');
            }
        });
    }
};
