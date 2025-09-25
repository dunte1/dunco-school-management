<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('role_user', function (Blueprint $table) {
            if (!Schema::hasColumn('role_user', 'school_id')) {
                $table->unsignedBigInteger('school_id')->nullable()->after('user_id');
                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('role_user', function (Blueprint $table) {
            if (Schema::hasColumn('role_user', 'school_id')) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            }
        });
    }
}; 