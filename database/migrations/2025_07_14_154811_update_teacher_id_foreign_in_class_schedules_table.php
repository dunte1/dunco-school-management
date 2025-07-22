<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            // Drop the old foreign key
            $table->dropForeign(['teacher_id']);
            // Add the new foreign key
            $table->foreign('teacher_id')->references('id')->on('staff')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('class_schedules', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }
};
