<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add indexes to FK columns that were created without them
 * (because they were converted from constrained() to plain unsignedBigInteger
 * during the MySQL FK-ordering fix in Phase 4/5g).
 */
return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'academic_subjects' => ['school_id'],
            'academic_classes' => ['school_id'],
            'academic_students' => ['school_id'],
            'academic_records' => ['school_id'],
            'academic_attendance_records' => ['school_id'],
            'academic_exams' => ['school_id'],
            'academic_exam_results' => ['school_id'],
            'academic_questions' => ['school_id'],
            'online_classes' => ['school_id', 'subject_id'],
            'borrow_records' => ['book_id', 'member_id'],
            'drivers' => ['school_id'],
            'vehicles' => ['school_id'],
            'routes' => ['school_id'],
            'trips' => ['school_id'],
            'teacher_availabilities' => ['teacher_id'],
            'class_schedules' => ['teacher_id', 'room_id', 'timetable_id', 'academic_class_id'],
            'schedule_snapshots' => ['class_id'],
            'beds' => ['room_id'],
            'hostel_fees' => ['room_id'],
            'hostel_issues' => ['room_id'],
            'role_user' => ['role_id', 'school_id'],
            'permission_role' => ['role_id', 'permission_id', 'school_id'],
            'users' => ['school_id', 'primary_role_id'],
        ];

        foreach ($tables as $table => $columns) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            foreach ($columns as $column) {
                if (Schema::hasColumn($table, $column)) {
                    $indexName = "{$table}_{$column}_index";
                    $hasIndex = Schema::hasIndex($table, $column) ||
                                Schema::hasIndex($table, $indexName);
                    if (!$hasIndex) {
                        try {
                            Schema::table($table, function (Blueprint $blueprint) use ($column) {
                                $blueprint->index($column);
                            });
                        } catch (\Throwable $e) {
                            // Index may already exist or column not found; safe to ignore.
                        }
                    }
                }
            }
        }
    }

    public function down(): void
    {
        // Indexes are safe to leave in place; no rollback needed.
    }
};
