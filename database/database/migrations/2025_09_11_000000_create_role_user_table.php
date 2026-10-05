<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('role_user')) {
            Schema::create('role_user', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('school_id')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'role_id', 'school_id']);
                $table->index(['user_id']);
                $table->index(['role_id']);
                $table->index(['school_id']);
            });
        } else {
            // Ensure required columns exist
            Schema::table('role_user', function (Blueprint $table) {
                if (!Schema::hasColumn('role_user', 'school_id')) {
                    $table->unsignedBigInteger('school_id')->nullable()->after('role_id');
                }
                if (!Schema::hasColumn('role_user', 'created_at')) {
                    $table->timestamps();
                }
            });
        }

        // Conditionally add foreign keys only if referenced tables exist
        try {
            if (Schema::hasTable('users') && Schema::hasTable('roles')) {
                Schema::table('role_user', function (Blueprint $table) {
                    // Avoid duplicate FK additions
                    $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
                    $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
                });
            }
            if (Schema::hasTable('schools')) {
                Schema::table('role_user', function (Blueprint $table) {
                    $table->foreign('school_id')->references('id')->on('schools')->nullOnDelete();
                });
            }
        } catch (\Throwable $e) {
            // Log-only in migration context; foreign keys are optional
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('role_user')) {
            Schema::dropIfExists('role_user');
        }
    }
};
