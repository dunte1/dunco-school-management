<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('academic_students')) {
            return;
        }

        Schema::table('academic_students', function (Blueprint $table) {
            if (! Schema::hasColumn('academic_students', 'name')) {
                $table->string('name')->nullable();
            }
            if (! Schema::hasColumn('academic_students', 'admission_number')) {
                $table->string('admission_number')->nullable();
            }
            if (! Schema::hasColumn('academic_students', 'class_id')) {
                $table->unsignedBigInteger('class_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('academic_students')) {
            return;
        }

        Schema::table('academic_students', function (Blueprint $table) {
            $drops = [];
            foreach (['name','admission_number','class_id'] as $col) {
                if (Schema::hasColumn('academic_students', $col)) { $drops[] = $col; }
            }
            if (!empty($drops)) {
                $table->dropColumn($drops);
            }
        });
    }
};
