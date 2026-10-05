<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('exam_results')) {
            return;
        }

        Schema::table('exam_results', function (Blueprint $table) {
            if (! Schema::hasColumn('exam_results', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down()
    {
        if (! Schema::hasTable('exam_results')) {
            return;
        }

        Schema::table('exam_results', function (Blueprint $table) {
            if (Schema::hasColumn('exam_results', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
}; 