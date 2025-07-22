<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('staff_attendance_records', function (Blueprint $table) {
            $table->string('period', 32)->nullable()->after('date');
        });
    }

    public function down()
    {
        Schema::table('staff_attendance_records', function (Blueprint $table) {
            $table->dropColumn('period');
        });
    }
}; 