<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->string('level')->nullable()->after('motto');
            $table->string('phone')->nullable()->after('level');
            $table->string('email')->nullable()->after('phone');
            $table->string('address')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['level', 'phone', 'email', 'address']);
        });
    }
}; 