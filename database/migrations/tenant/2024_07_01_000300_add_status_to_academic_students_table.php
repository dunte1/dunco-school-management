<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('academic_students', function (Blueprint $table) {
            $table->string('status')->default('active')->after('is_active');
            $table->string('status_reason')->nullable()->after('status');
            $table->timestamp('status_changed_at')->nullable()->after('status_reason');
        });
    }

    public function down()
    {
        Schema::table('academic_students', function (Blueprint $table) {
            $table->dropColumn(['status', 'status_reason', 'status_changed_at']);
        });
    }
}; 