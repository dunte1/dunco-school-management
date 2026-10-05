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
        // Add school_id to fees table
        if (Schema::hasTable('fees')) {
            Schema::table('fees', function (Blueprint $table) {
                if (!Schema::hasColumn('fees', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->onDelete('cascade');
                    $table->index(['school_id']);
                }
            });
        }

        // Add school_id to student_fees table
        if (Schema::hasTable('student_fees')) {
            Schema::table('student_fees', function (Blueprint $table) {
                if (!Schema::hasColumn('student_fees', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->onDelete('cascade');
                    $table->index(['school_id']);
                }
            });
        }

        // Add school_id to fee_categories table if it exists
        if (Schema::hasTable('fee_categories')) {
            Schema::table('fee_categories', function (Blueprint $table) {
                if (!Schema::hasColumn('fee_categories', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->onDelete('cascade');
                    $table->index(['school_id']);
                }
            });
        }

        // Add school_id to fee_types table if it exists
        if (Schema::hasTable('fee_types')) {
            Schema::table('fee_types', function (Blueprint $table) {
                if (!Schema::hasColumn('fee_types', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->onDelete('cascade');
                    $table->index(['school_id']);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove school_id from fees table
        if (Schema::hasTable('fees') && Schema::hasColumn('fees', 'school_id')) {
            Schema::table('fees', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }

        // Remove school_id from student_fees table
        if (Schema::hasTable('student_fees') && Schema::hasColumn('student_fees', 'school_id')) {
            Schema::table('student_fees', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }

        // Remove school_id from fee_categories table
        if (Schema::hasTable('fee_categories') && Schema::hasColumn('fee_categories', 'school_id')) {
            Schema::table('fee_categories', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }

        // Remove school_id from fee_types table
        if (Schema::hasTable('fee_types') && Schema::hasColumn('fee_types', 'school_id')) {
            Schema::table('fee_types', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }
    }
};
