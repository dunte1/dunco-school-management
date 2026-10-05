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
        Schema::table('groups', function (Blueprint $table) {
            // Add type column if it doesn't exist
            if (!Schema::hasColumn('groups', 'type')) {
                $table->enum('type', ['general', 'academic', 'staff', 'students', 'parents'])
                      ->default('general')
                      ->after('description');
            }
            
            // Add created_by and updated_by columns if they don't exist
            if (!Schema::hasColumn('groups', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('type');
            }
            
            if (!Schema::hasColumn('groups', 'updated_by')) {
                $table->unsignedBigInteger('updated_by')->nullable()->after('created_by');
            }
            
            // Add soft deletes if it doesn't exist
            if (!Schema::hasColumn('groups', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Add foreign key constraints if they don't exist
        if (Schema::hasColumn('groups', 'created_by')) {
            try {
                Schema::table('groups', function (Blueprint $table) {
                    $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
        }

        if (Schema::hasColumn('groups', 'updated_by')) {
            try {
                Schema::table('groups', function (Blueprint $table) {
                    $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
        }

        // Add indexes if they don't exist
        try {
            Schema::table('groups', function (Blueprint $table) {
                $table->index(['type', 'is_active']);
                $table->index('created_by');
            });
        } catch (\Exception $e) {
            // Indexes might already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['type', 'created_by', 'updated_by', 'deleted_at']);
        });
    }
};
