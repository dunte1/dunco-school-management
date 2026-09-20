<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addForeignKeys();
        $this->addIndexes();
    }

    public function down(): void
    {
        // Drop foreign keys in reverse order
        $fks = [
            ['invoice_items', 'invoice_items_fee_id_foreign'],
            ['invoice_items', 'invoice_items_invoice_id_foreign'],
            ['payments', 'payments_invoice_id_foreign'],
            ['fees', 'fees_fee_category_id_foreign'],
            ['fees', 'fees_fee_type_id_foreign'],
            ['mpesa_transactions', 'mpesa_transactions_user_id_foreign'],
            ['mpesa_transactions', 'mpesa_transactions_student_fee_id_foreign'],
            ['mpesa_transactions', 'mpesa_transactions_fee_id_foreign'],
            ['bank_transactions', 'bank_transactions_matched_payment_id_foreign'],
            ['invoices', 'invoices_student_id_foreign'],
            ['hostels', 'hostels_school_id_foreign'],
            ['floors', 'floors_hostel_id_foreign'],
            ['rooms', 'rooms_hostel_id_foreign'],
            ['rooms', 'rooms_floor_id_foreign'],
            ['beds', 'beds_room_id_foreign'],
            ['room_allocations', 'room_allocations_bed_id_foreign'],
            ['room_allocations', 'room_allocations_student_id_foreign'],
            ['hostel_fees', 'hostel_fees_hostel_id_foreign'],
            ['hostel_fees', 'hostel_fees_student_id_foreign'],
            ['staff', 'staff_school_id_foreign'],
            ['staff', 'staff_department_id_foreign'],
            ['staff', 'staff_user_id_foreign'],
            ['leaves', 'leaves_staff_id_foreign'],
            ['payrolls', 'payrolls_staff_id_foreign'],
            ['contracts', 'contracts_staff_id_foreign'],
            ['performance_reviews', 'performance_reviews_staff_id_foreign'],
            ['assets', 'assets_staff_id_foreign'],
            ['drivers', 'drivers_school_id_foreign'],
            ['vehicles', 'vehicles_driver_id_foreign'],
            ['vehicles', 'vehicles_school_id_foreign'],
            ['routes', 'routes_school_id_foreign'],
            ['class_schedules', 'class_schedules_timetable_id_foreign'],
            ['class_schedules', 'class_schedules_academic_class_id_foreign'],
            ['class_schedules', 'class_schedules_teacher_id_foreign'],
            ['class_schedules', 'class_schedules_room_id_foreign'],
            ['teacher_availabilities', 'teacher_availabilities_teacher_id_foreign'],
            ['session_template_rules', 'session_template_rules_session_template_id_foreign'],
            ['attendance_biometric_logs', 'attendance_biometric_logs_student_id_foreign'],
            ['attendance_qr_logs', 'attendance_qr_logs_student_id_foreign'],
            ['attendance_qr_logs', 'attendance_qr_logs_session_id_foreign'],
            ['attendance_face_logs', 'attendance_face_logs_student_id_foreign'],
            ['attendance_acknowledgments', 'attendance_acknowledgments_attendance_record_id_foreign'],
            ['attendance_acknowledgments', 'attendance_acknowledgments_parent_id_foreign'],
        ];

        foreach ($fks as [$table, $fkName]) {
            try {
                if (Schema::hasTable($table)) {
                    Schema::table($table, function (Blueprint $b) use ($fkName) {
                        $b->dropForeign($fkName);
                    });
                }
            } catch (\Exception $e) {
                // Skip if FK doesn't exist
            }
        }
    }

    private function addForeignKeys(): void
    {
        $definitions = [
            // Finance
            ['fees', 'fee_category_id', 'fee_categories', 'id', 'cascadeOnDelete'],
            ['fees', 'fee_type_id', 'fee_types', 'id', 'nullOnDelete'],
            ['invoices', 'student_id', 'academic_students', 'id', 'cascadeOnDelete'],
            ['invoice_items', 'invoice_id', 'invoices', 'id', 'cascadeOnDelete'],
            ['invoice_items', 'fee_id', 'fees', 'id', 'nullOnDelete'],
            ['payments', 'invoice_id', 'invoices', 'id', 'nullOnDelete'],
            ['mpesa_transactions', 'user_id', 'users', 'id', 'cascadeOnDelete'],
            ['mpesa_transactions', 'student_fee_id', 'student_fees', 'id', 'nullOnDelete'],
            ['mpesa_transactions', 'fee_id', 'fees', 'id', 'nullOnDelete'],
            ['bank_transactions', 'matched_payment_id', 'payments', 'id', 'nullOnDelete'],
            // Hostel
            ['hostels', 'school_id', 'schools', 'id', 'cascadeOnDelete'],
            ['floors', 'hostel_id', 'hostels', 'id', 'cascadeOnDelete'],
            ['rooms', 'hostel_id', 'hostels', 'id', 'cascadeOnDelete'],
            ['rooms', 'floor_id', 'floors', 'id', 'nullOnDelete'],
            ['beds', 'room_id', 'rooms', 'id', 'cascadeOnDelete'],
            ['room_allocations', 'bed_id', 'beds', 'id', 'cascadeOnDelete'],
            ['room_allocations', 'student_id', 'users', 'id', 'cascadeOnDelete'],
            ['hostel_fees', 'hostel_id', 'hostels', 'id', 'cascadeOnDelete'],
            ['hostel_fees', 'student_id', 'users', 'id', 'cascadeOnDelete'],
            // HR
            ['staff', 'school_id', 'schools', 'id', 'cascadeOnDelete'],
            ['staff', 'department_id', 'departments', 'id', 'nullOnDelete'],
            ['staff', 'user_id', 'users', 'id', 'cascadeOnDelete'],
            ['leaves', 'staff_id', 'staff', 'id', 'cascadeOnDelete'],
            ['payrolls', 'staff_id', 'staff', 'id', 'cascadeOnDelete'],
            ['contracts', 'staff_id', 'staff', 'id', 'cascadeOnDelete'],
            ['performance_reviews', 'staff_id', 'staff', 'id', 'cascadeOnDelete'],
            ['assets', 'staff_id', 'staff', 'id', 'nullOnDelete'],
            // Transport
            ['drivers', 'school_id', 'schools', 'id', 'cascadeOnDelete'],
            ['vehicles', 'driver_id', 'drivers', 'id', 'nullOnDelete'],
            ['vehicles', 'school_id', 'schools', 'id', 'cascadeOnDelete'],
            ['routes', 'school_id', 'schools', 'id', 'cascadeOnDelete'],
            // Timetable
            ['class_schedules', 'timetable_id', 'timetables', 'id', 'cascadeOnDelete'],
            ['class_schedules', 'academic_class_id', 'academic_classes', 'id', 'cascadeOnDelete'],
            ['class_schedules', 'teacher_id', 'teachers', 'id', 'nullOnDelete'],
            ['class_schedules', 'room_id', 'rooms', 'id', 'nullOnDelete'],
            ['teacher_availabilities', 'teacher_id', 'teachers', 'id', 'cascadeOnDelete'],
            // Attendance
            ['session_template_rules', 'session_template_id', 'session_templates', 'id', 'cascadeOnDelete'],
            ['attendance_biometric_logs', 'student_id', 'academic_students', 'id', 'cascadeOnDelete'],
            ['attendance_qr_logs', 'student_id', 'academic_students', 'id', 'cascadeOnDelete'],
            ['attendance_qr_logs', 'session_id', 'attendance_sessions', 'id', 'cascadeOnDelete'],
            ['attendance_face_logs', 'student_id', 'academic_students', 'id', 'cascadeOnDelete'],
            ['attendance_acknowledgments', 'attendance_record_id', 'academic_attendance_records', 'id', 'cascadeOnDelete'],
            ['attendance_acknowledgments', 'parent_id', 'users', 'id', 'cascadeOnDelete'],
        ];

        foreach ($definitions as [$table, $column, $refTable, $refColumn, $onDelete]) {
            $fkName = "{$table}_{$column}_foreign";
            try {
                if (!Schema::hasTable($table) || !Schema::hasColumn($table, $column)) {
                    continue;
                }
                if (!Schema::hasTable($refTable)) {
                    continue;
                }
                Schema::table($table, function (Blueprint $b) use ($column, $refTable, $refColumn, $onDelete, $fkName) {
                    if (!$b->hasIndex("{$table}_{$column}_index")) {
                        $b->index($column);
                    }
                    try {
                        $b->foreign($column)->references($refColumn)->on($refTable)->$onDelete();
                    } catch (\Exception $e) {
                        // FK already exists or constraint error - skip
                    }
                });
            } catch (\Exception $e) {
                // Table/column doesn't exist - skip
            }
        }
    }

    private function addIndexes(): void
    {
        $indexes = [
            ['invoices', ['status']],
            ['payments', ['status']],
            ['staff', ['status']],
            ['leaves', ['status']],
            ['payrolls', ['status']],
            ['room_allocations', ['status']],
            ['hostel_fees', ['status']],
            ['student_fees', ['student_id']],
            ['student_payments', ['student_id']],
            ['student_payments', ['fee_id']],
        ];

        foreach ($indexes as [$table, $columns]) {
            try {
                if (!Schema::hasTable($table)) {
                    continue;
                }
                Schema::table($table, function (Blueprint $b) use ($columns) {
                    foreach ($columns as $column) {
                        if (Schema::hasColumn($table, $column) && !$b->hasIndex("{$table}_{$column}_index")) {
                            $b->index($column);
                        }
                    }
                });
            } catch (\Exception $e) {
                // Skip
            }
        }
    }
};
