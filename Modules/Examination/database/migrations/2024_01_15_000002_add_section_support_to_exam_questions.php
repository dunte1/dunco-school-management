<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('exam_questions', function (Blueprint $table) {
            $table->foreignId('section_id')->nullable()->constrained('exam_sections')->onDelete('cascade');
            $table->integer('section_order')->default(1);
        });
    }

    public function down()
    {
        Schema::table('exam_questions', function (Blueprint $table) {
            $table->dropForeign(['section_id']);
            $table->dropColumn(['section_id', 'section_order']);
        });
    }
};
