<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * NOTE: This canonical migration merges the columns that were previously
     * split across two colliding migrations (database/ and Modules/Academic/).
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
            if (! Schema::hasColumn('academic_students', 'stream')) {
                $table->string('stream')->nullable();
            }
            if (! Schema::hasColumn('academic_students', 'house')) {
                $table->string('house')->nullable();
            }
            if (! Schema::hasColumn('academic_students', 'group')) {
                $table->string('group')->nullable();
            }
            if (! Schema::hasColumn('academic_students', 'is_transfer')) {
                $table->boolean('is_transfer')->default(false);
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
            foreach (['name', 'admission_number', 'class_id', 'stream', 'house', 'group', 'is_transfer'] as $col) {
                if (Schema::hasColumn('academic_students', $col)) {
                    $drops[] = $col;
                }
            }
            if (! empty($drops)) {
                $table->dropColumn($drops);
            }
        });
    }
};
