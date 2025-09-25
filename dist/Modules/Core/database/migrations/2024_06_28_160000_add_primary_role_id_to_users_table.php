<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'primary_role_id')) {
                $table->unsignedBigInteger('primary_role_id')->nullable()->after('school_id');
                $table->foreign('primary_role_id')->references('id')->on('roles')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'primary_role_id')) {
                $table->dropForeign(['primary_role_id']);
                $table->dropColumn('primary_role_id');
            }
        });
    }
}; 