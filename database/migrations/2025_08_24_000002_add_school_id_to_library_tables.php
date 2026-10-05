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
        // Add school_id to books table
        if (Schema::hasTable('books')) {
            Schema::table('books', function (Blueprint $table) {
                if (!Schema::hasColumn('books', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->onDelete('cascade');
                    $table->index(['school_id']);
                }
            });
        }

        // Add school_id to borrow_records table
        if (Schema::hasTable('borrow_records')) {
            Schema::table('borrow_records', function (Blueprint $table) {
                if (!Schema::hasColumn('borrow_records', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->onDelete('cascade');
                    $table->index(['school_id']);
                }
            });
        }

        // Add school_id to members table if it exists
        if (Schema::hasTable('members')) {
            Schema::table('members', function (Blueprint $table) {
                if (!Schema::hasColumn('members', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->onDelete('cascade');
                    $table->index(['school_id']);
                }
            });
        }

        // Add school_id to categories table if it exists (library categories)
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (!Schema::hasColumn('categories', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->onDelete('cascade');
                    $table->index(['school_id']);
                }
            });
        }

        // Add school_id to authors table if it exists
        if (Schema::hasTable('authors')) {
            Schema::table('authors', function (Blueprint $table) {
                if (!Schema::hasColumn('authors', 'school_id')) {
                    $table->foreignId('school_id')->nullable()->after('id')->constrained('schools')->onDelete('cascade');
                    $table->index(['school_id']);
                }
            });
        }

        // Add school_id to publishers table if it exists
        if (Schema::hasTable('publishers')) {
            Schema::table('publishers', function (Blueprint $table) {
                if (!Schema::hasColumn('publishers', 'school_id')) {
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
        // Remove school_id from books table
        if (Schema::hasTable('books') && Schema::hasColumn('books', 'school_id')) {
            Schema::table('books', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }

        // Remove school_id from borrow_records table
        if (Schema::hasTable('borrow_records') && Schema::hasColumn('borrow_records', 'school_id')) {
            Schema::table('borrow_records', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }

        // Remove school_id from members table
        if (Schema::hasTable('members') && Schema::hasColumn('members', 'school_id')) {
            Schema::table('members', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }

        // Remove school_id from categories table
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'school_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }

        // Remove school_id from authors table
        if (Schema::hasTable('authors') && Schema::hasColumn('authors', 'school_id')) {
            Schema::table('authors', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }

        // Remove school_id from publishers table
        if (Schema::hasTable('publishers') && Schema::hasColumn('publishers', 'school_id')) {
            Schema::table('publishers', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }
    }
};
