<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Deferred foreign keys.
 *
 * Several original migrations declared foreign keys to tables that did not
 * exist yet at that point in the migration order (users.school_id -> schools,
 * role_user.role_id -> roles, users.primary_role_id -> roles). Those columns
 * are now created without constraints, and this final migration adds the
 * constraints once every referenced table exists (MySQL).
 *
 * SQLite cannot ALTER TABLE ADD CONSTRAINT, so failures are ignored there.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->addForeign('users', 'school_id', 'schools', 'cascade');
        $this->addForeign('users', 'primary_role_id', 'roles', 'set null');
        $this->addForeign('role_user', 'role_id', 'roles', 'cascade');
        $this->addForeign('role_user', 'school_id', 'schools', 'cascade');
    }

    protected function addForeign(string $table, string $column, string $refTable, string $onDelete): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasTable($refTable) || ! Schema::hasColumn($table, $column)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($column, $refTable, $onDelete) {
                $blueprint->foreign($column)->references('id')->on($refTable)->onDelete($onDelete);
            });
        } catch (\Throwable $e) {
            // Constraint already exists, or the driver does not support adding
            // foreign keys to existing tables (SQLite). Safe to ignore.
        }
    }

    public function down(): void
    {
        foreach ([
            ['users', 'school_id'],
            ['users', 'primary_role_id'],
            ['role_user', 'role_id'],
            ['role_user', 'school_id'],
        ] as [$table, $column]) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
                continue;
            }

            try {
                Schema::table($table, function (Blueprint $blueprint) use ($column) {
                    $blueprint->dropForeign([$column]);
                });
            } catch (\Throwable $e) {
                // Ignore.
            }
        }
    }
};
