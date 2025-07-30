<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class SystemPermissionsSeeder extends Seeder
{
    public function run()
    {
        $allPermissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'module' => 'Dashboard'],
            
            // Core Module Permissions
            ['name' => 'schools.view', 'display_name' => 'View Schools', 'module' => 'Core'],
            ['name' => 'schools.create', 'display_name' => 'Create Schools', 'module' => 'Core'],
            ['name' => 'schools.edit', 'display_name' => 'Edit Schools', 'module' => 'Core'],
            ['name' => 'schools.delete', 'display_name' => 'Delete Schools', 'module' => 'Core'],
            
            ['name' => 'users.view', 'display_name' => 'View Users', 'module' => 'Core'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'module' => 'Core'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'module' => 'Core'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'module' => 'Core'],
            
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'module' => 'Core'],
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'module' => 'Core'],
            ['name' => 'roles.edit', 'display_name' => 'Edit Roles', 'module' => 'Core'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'module' => 'Core'],
            
            ['name' => 'permissions.view', 'display_name' => 'View Permissions', 'module' => 'Core'],
            ['name' => 'permissions.create', 'display_name' => 'Create Permissions', 'module' => 'Core'],
            ['name' => 'permissions.edit', 'display_name' => 'Edit Permissions', 'module' => 'Core'],
            ['name' => 'permissions.delete', 'display_name' => 'Delete Permissions', 'module' => 'Core'],
            
            ['name' => 'audit.view', 'display_name' => 'View Audit Logs', 'module' => 'Core'],
            ['name' => 'audit.create', 'display_name' => 'Create Audit Logs', 'module' => 'Core'],
            
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'module' => 'Core'],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'module' => 'Core'],
            ['name' => 'settings.global.view', 'display_name' => 'View Global Settings', 'module' => 'Core'],
            ['name' => 'settings.global.edit', 'display_name' => 'Edit Global Settings', 'module' => 'Core'],
            ['name' => 'settings.per_school.view', 'display_name' => 'View Per-School Settings', 'module' => 'Core'],
            ['name' => 'settings.per_school.edit', 'display_name' => 'Edit Per-School Settings', 'module' => 'Core'],
            
            // Academic Module Permissions
            ['name' => 'academic.view', 'display_name' => 'View Academic', 'module' => 'Academic'],
            ['name' => 'academic.students.view', 'display_name' => 'View Students', 'module' => 'Academic'],
            ['name' => 'academic.students.create', 'display_name' => 'Create Students', 'module' => 'Academic'],
            ['name' => 'academic.students.edit', 'display_name' => 'Edit Students', 'module' => 'Academic'],
            ['name' => 'academic.students.delete', 'display_name' => 'Delete Students', 'module' => 'Academic'],
            
            ['name' => 'academic.classes.view', 'display_name' => 'View Classes', 'module' => 'Academic'],
            ['name' => 'academic.classes.create', 'display_name' => 'Create Classes', 'module' => 'Academic'],
            ['name' => 'academic.classes.edit', 'display_name' => 'Edit Classes', 'module' => 'Academic'],
            ['name' => 'academic.classes.delete', 'display_name' => 'Delete Classes', 'module' => 'Academic'],
            
            ['name' => 'academic.subjects.view', 'display_name' => 'View Subjects', 'module' => 'Academic'],
            ['name' => 'academic.subjects.create', 'display_name' => 'Create Subjects', 'module' => 'Academic'],
            ['name' => 'academic.subjects.edit', 'display_name' => 'Edit Subjects', 'module' => 'Academic'],
            ['name' => 'academic.subjects.delete', 'display_name' => 'Delete Subjects', 'module' => 'Academic'],
            
            ['name' => 'academic.grading.view', 'display_name' => 'View Grading', 'module' => 'Academic'],
            ['name' => 'academic.grading.create', 'display_name' => 'Create Grading', 'module' => 'Academic'],
            ['name' => 'academic.grading.edit', 'display_name' => 'Edit Grading', 'module' => 'Academic'],
            ['name' => 'academic.grading.delete', 'display_name' => 'Delete Grading', 'module' => 'Academic'],
            
            ['name' => 'academic.reports.view', 'display_name' => 'View Academic Reports', 'module' => 'Academic'],
            ['name' => 'academic.reports.create', 'display_name' => 'Create Academic Reports', 'module' => 'Academic'],
            ['name' => 'academic.reports.export', 'display_name' => 'Export Academic Reports', 'module' => 'Academic'],
            
            // Examination Module Permissions
            ['name' => 'examination.view', 'display_name' => 'View Examination', 'module' => 'Examination'],
            ['name' => 'examination.exams.view', 'display_name' => 'View Exams', 'module' => 'Examination'],
            ['name' => 'examination.exams.create', 'display_name' => 'Create Exams', 'module' => 'Examination'],
            ['name' => 'examination.exams.edit', 'display_name' => 'Edit Exams', 'module' => 'Examination'],
            ['name' => 'examination.exams.delete', 'display_name' => 'Delete Exams', 'module' => 'Examination'],
            
            ['name' => 'examination.questions.view', 'display_name' => 'View Questions', 'module' => 'Examination'],
            ['name' => 'examination.questions.create', 'display_name' => 'Create Questions', 'module' => 'Examination'],
            ['name' => 'examination.questions.edit', 'display_name' => 'Edit Questions', 'module' => 'Examination'],
            ['name' => 'examination.questions.delete', 'display_name' => 'Delete Questions', 'module' => 'Examination'],
            
            ['name' => 'examination.categories.view', 'display_name' => 'View Question Categories', 'module' => 'Examination'],
            ['name' => 'examination.categories.create', 'display_name' => 'Create Question Categories', 'module' => 'Examination'],
            ['name' => 'examination.categories.edit', 'display_name' => 'Edit Question Categories', 'module' => 'Examination'],
            ['name' => 'examination.categories.delete', 'display_name' => 'Delete Question Categories', 'module' => 'Examination'],
            
            ['name' => 'examination.schedules.view', 'display_name' => 'View Exam Schedules', 'module' => 'Examination'],
            ['name' => 'examination.schedules.create', 'display_name' => 'Create Exam Schedules', 'module' => 'Examination'],
            ['name' => 'examination.schedules.edit', 'display_name' => 'Edit Exam Schedules', 'module' => 'Examination'],
            ['name' => 'examination.schedules.delete', 'display_name' => 'Delete Exam Schedules', 'module' => 'Examination'],
            
            ['name' => 'examination.results.view', 'display_name' => 'View Exam Results', 'module' => 'Examination'],
            ['name' => 'examination.results.create', 'display_name' => 'Create Exam Results', 'module' => 'Examination'],
            ['name' => 'examination.results.edit', 'display_name' => 'Edit Exam Results', 'module' => 'Examination'],
            ['name' => 'examination.results.delete', 'display_name' => 'Delete Exam Results', 'module' => 'Examination'],
            
            ['name' => 'examination.proctoring.view', 'display_name' => 'View Proctoring', 'module' => 'Examination'],
            ['name' => 'examination.proctoring.create', 'display_name' => 'Create Proctoring', 'module' => 'Examination'],
            ['name' => 'examination.proctoring.edit', 'display_name' => 'Edit Proctoring', 'module' => 'Examination'],
            
            ['name' => 'examination.online.view', 'display_name' => 'View Online Exams', 'module' => 'Examination'],
            ['name' => 'examination.online.create', 'display_name' => 'Create Online Exams', 'module' => 'Examination'],
            ['name' => 'examination.online.edit', 'display_name' => 'Edit Online Exams', 'module' => 'Examination'],
            
            // Teacher-specific examination permissions
            ['name' => 'examination.teacher.exams', 'display_name' => 'Teacher - My Exams', 'module' => 'Examination'],
            ['name' => 'examination.teacher.grade', 'display_name' => 'Teacher - Grade Exams', 'module' => 'Examination'],
            ['name' => 'examination.teacher.analytics', 'display_name' => 'Teacher - Exam Analytics', 'module' => 'Examination'],
            
            // Student-specific examination permissions
            ['name' => 'examination.student.exams', 'display_name' => 'Student - My Exams', 'module' => 'Examination'],
            ['name' => 'examination.student.results', 'display_name' => 'Student - My Results', 'module' => 'Examination'],
            ['name' => 'examination.student.history', 'display_name' => 'Student - Exam History', 'module' => 'Examination'],
            
            // Finance Module Permissions
            ['name' => 'finance.view', 'display_name' => 'View Finance', 'module' => 'Finance'],
            ['name' => 'finance.fees.view', 'display_name' => 'View Fee Structures', 'module' => 'Finance'],
            ['name' => 'finance.fees.create', 'display_name' => 'Create Fee Structures', 'module' => 'Finance'],
            ['name' => 'finance.fees.edit', 'display_name' => 'Edit Fee Structures', 'module' => 'Finance'],
            ['name' => 'finance.fees.delete', 'display_name' => 'Delete Fee Structures', 'module' => 'Finance'],
            
            ['name' => 'finance.billing.view', 'display_name' => 'View Billing & Invoices', 'module' => 'Finance'],
            ['name' => 'finance.billing.create', 'display_name' => 'Create Billing & Invoices', 'module' => 'Finance'],
            ['name' => 'finance.billing.edit', 'display_name' => 'Edit Billing & Invoices', 'module' => 'Finance'],
            ['name' => 'finance.billing.delete', 'display_name' => 'Delete Billing & Invoices', 'module' => 'Finance'],
            
            ['name' => 'finance.payments.view', 'display_name' => 'View Payments', 'module' => 'Finance'],
            ['name' => 'finance.payments.create', 'display_name' => 'Create Payments', 'module' => 'Finance'],
            ['name' => 'finance.payments.edit', 'display_name' => 'Edit Payments', 'module' => 'Finance'],
            ['name' => 'finance.payments.delete', 'display_name' => 'Delete Payments', 'module' => 'Finance'],
            
            ['name' => 'finance.receipts.view', 'display_name' => 'View Receipts', 'module' => 'Finance'],
            ['name' => 'finance.receipts.create', 'display_name' => 'Create Receipts', 'module' => 'Finance'],
            ['name' => 'finance.receipts.edit', 'display_name' => 'Edit Receipts', 'module' => 'Finance'],
            ['name' => 'finance.receipts.delete', 'display_name' => 'Delete Receipts', 'module' => 'Finance'],
            
            ['name' => 'finance.reports.view', 'display_name' => 'View Financial Reports', 'module' => 'Finance'],
            ['name' => 'finance.reports.create', 'display_name' => 'Create Financial Reports', 'module' => 'Finance'],
            ['name' => 'finance.reports.export', 'display_name' => 'Export Financial Reports', 'module' => 'Finance'],
            
            ['name' => 'finance.settings.view', 'display_name' => 'View Finance Settings', 'module' => 'Finance'],
            ['name' => 'finance.settings.edit', 'display_name' => 'Edit Finance Settings', 'module' => 'Finance'],
            
            ['name' => 'finance.bank-reconciliation.view', 'display_name' => 'View Bank Reconciliation', 'module' => 'Finance'],
            ['name' => 'finance.bank-reconciliation.create', 'display_name' => 'Create Bank Reconciliation', 'module' => 'Finance'],
            ['name' => 'finance.bank-reconciliation.edit', 'display_name' => 'Edit Bank Reconciliation', 'module' => 'Finance'],
            
            ['name' => 'finance.banks.view', 'display_name' => 'View Multi-bank', 'module' => 'Finance'],
            ['name' => 'finance.banks.create', 'display_name' => 'Create Multi-bank', 'module' => 'Finance'],
            ['name' => 'finance.banks.edit', 'display_name' => 'Edit Multi-bank', 'module' => 'Finance'],
            ['name' => 'finance.banks.delete', 'display_name' => 'Delete Multi-bank', 'module' => 'Finance'],
            
            ['name' => 'finance.ledger.view', 'display_name' => 'View General Ledger', 'module' => 'Finance'],
            ['name' => 'finance.ledger.create', 'display_name' => 'Create General Ledger', 'module' => 'Finance'],
            ['name' => 'finance.ledger.edit', 'display_name' => 'Edit General Ledger', 'module' => 'Finance'],
            
            ['name' => 'finance.online-payments.view', 'display_name' => 'View Online Payments', 'module' => 'Finance'],
            ['name' => 'finance.online-payments.create', 'display_name' => 'Create Online Payments', 'module' => 'Finance'],
            ['name' => 'finance.online-payments.edit', 'display_name' => 'Edit Online Payments', 'module' => 'Finance'],
            
            ['name' => 'finance.forecasting.view', 'display_name' => 'View Forecasting & Budgeting', 'module' => 'Finance'],
            ['name' => 'finance.forecasting.create', 'display_name' => 'Create Forecasting & Budgeting', 'module' => 'Finance'],
            ['name' => 'finance.forecasting.edit', 'display_name' => 'Edit Forecasting & Budgeting', 'module' => 'Finance'],
            
            ['name' => 'finance.taxes.view', 'display_name' => 'View Tax Management', 'module' => 'Finance'],
            ['name' => 'finance.taxes.create', 'display_name' => 'Create Tax Management', 'module' => 'Finance'],
            ['name' => 'finance.taxes.edit', 'display_name' => 'Edit Tax Management', 'module' => 'Finance'],
            
            ['name' => 'finance.roles.view', 'display_name' => 'View Finance Roles & Permissions', 'module' => 'Finance'],
            ['name' => 'finance.roles.create', 'display_name' => 'Create Finance Roles & Permissions', 'module' => 'Finance'],
            ['name' => 'finance.roles.edit', 'display_name' => 'Edit Finance Roles & Permissions', 'module' => 'Finance'],
            ['name' => 'finance.roles.delete', 'display_name' => 'Delete Finance Roles & Permissions', 'module' => 'Finance'],
            
            // HR Module Permissions
            ['name' => 'hr.view', 'display_name' => 'View HR', 'module' => 'HR'],
            ['name' => 'hr.staff.view', 'display_name' => 'View Staff', 'module' => 'HR'],
            ['name' => 'hr.staff.create', 'display_name' => 'Create Staff', 'module' => 'HR'],
            ['name' => 'hr.staff.edit', 'display_name' => 'Edit Staff', 'module' => 'HR'],
            ['name' => 'hr.staff.delete', 'display_name' => 'Delete Staff', 'module' => 'HR'],
            
            ['name' => 'hr.leave.view', 'display_name' => 'View Leave', 'module' => 'HR'],
            ['name' => 'hr.leave.create', 'display_name' => 'Create Leave', 'module' => 'HR'],
            ['name' => 'hr.leave.edit', 'display_name' => 'Edit Leave', 'module' => 'HR'],
            ['name' => 'hr.leave.delete', 'display_name' => 'Delete Leave', 'module' => 'HR'],
            
            ['name' => 'hr.payroll.view', 'display_name' => 'View Payroll', 'module' => 'HR'],
            ['name' => 'hr.payroll.create', 'display_name' => 'Create Payroll', 'module' => 'HR'],
            ['name' => 'hr.payroll.edit', 'display_name' => 'Edit Payroll', 'module' => 'HR'],
            ['name' => 'hr.payroll.delete', 'display_name' => 'Delete Payroll', 'module' => 'HR'],
            
            ['name' => 'hr.contract.view', 'display_name' => 'View Contracts', 'module' => 'HR'],
            ['name' => 'hr.contract.create', 'display_name' => 'Create Contracts', 'module' => 'HR'],
            ['name' => 'hr.contract.edit', 'display_name' => 'Edit Contracts', 'module' => 'HR'],
            ['name' => 'hr.contract.delete', 'display_name' => 'Delete Contracts', 'module' => 'HR'],
            
            ['name' => 'hr.departments.view', 'display_name' => 'View Departments', 'module' => 'HR'],
            ['name' => 'hr.departments.create', 'display_name' => 'Create Departments', 'module' => 'HR'],
            ['name' => 'hr.departments.edit', 'display_name' => 'Edit Departments', 'module' => 'HR'],
            ['name' => 'hr.departments.delete', 'display_name' => 'Delete Departments', 'module' => 'HR'],
            
            // Library Module Permissions
            ['name' => 'library.view', 'display_name' => 'View Library', 'module' => 'Library'],
            ['name' => 'library.books.view', 'display_name' => 'View Books', 'module' => 'Library'],
            ['name' => 'library.books.create', 'display_name' => 'Create Books', 'module' => 'Library'],
            ['name' => 'library.books.edit', 'display_name' => 'Edit Books', 'module' => 'Library'],
            ['name' => 'library.books.delete', 'display_name' => 'Delete Books', 'module' => 'Library'],
            
            ['name' => 'library.categories.view', 'display_name' => 'View Categories', 'module' => 'Library'],
            ['name' => 'library.categories.create', 'display_name' => 'Create Categories', 'module' => 'Library'],
            ['name' => 'library.categories.edit', 'display_name' => 'Edit Categories', 'module' => 'Library'],
            ['name' => 'library.categories.delete', 'display_name' => 'Delete Categories', 'module' => 'Library'],
            
            ['name' => 'library.authors.view', 'display_name' => 'View Authors', 'module' => 'Library'],
            ['name' => 'library.authors.create', 'display_name' => 'Create Authors', 'module' => 'Library'],
            ['name' => 'library.authors.edit', 'display_name' => 'Edit Authors', 'module' => 'Library'],
            ['name' => 'library.authors.delete', 'display_name' => 'Delete Authors', 'module' => 'Library'],
            
            ['name' => 'library.publishers.view', 'display_name' => 'View Publishers', 'module' => 'Library'],
            ['name' => 'library.publishers.create', 'display_name' => 'Create Publishers', 'module' => 'Library'],
            ['name' => 'library.publishers.edit', 'display_name' => 'Edit Publishers', 'module' => 'Library'],
            ['name' => 'library.publishers.delete', 'display_name' => 'Delete Publishers', 'module' => 'Library'],
            
            ['name' => 'library.members.view', 'display_name' => 'View Members', 'module' => 'Library'],
            ['name' => 'library.members.create', 'display_name' => 'Create Members', 'module' => 'Library'],
            ['name' => 'library.members.edit', 'display_name' => 'Edit Members', 'module' => 'Library'],
            ['name' => 'library.members.delete', 'display_name' => 'Delete Members', 'module' => 'Library'],
            
            ['name' => 'library.borrows.view', 'display_name' => 'View Borrows', 'module' => 'Library'],
            ['name' => 'library.borrows.create', 'display_name' => 'Create Borrows', 'module' => 'Library'],
            ['name' => 'library.borrows.edit', 'display_name' => 'Edit Borrows', 'module' => 'Library'],
            ['name' => 'library.borrows.delete', 'display_name' => 'Delete Borrows', 'module' => 'Library'],
            
            ['name' => 'library.reports.view', 'display_name' => 'View Library Reports', 'module' => 'Library'],
            ['name' => 'library.reports.create', 'display_name' => 'Create Library Reports', 'module' => 'Library'],
            ['name' => 'library.reports.export', 'display_name' => 'Export Library Reports', 'module' => 'Library'],
            
            // Timetable Module Permissions
            ['name' => 'timetable.view', 'display_name' => 'View Timetable', 'module' => 'Timetable'],
            ['name' => 'timetable.schedules.view', 'display_name' => 'View Class Schedules', 'module' => 'Timetable'],
            ['name' => 'timetable.schedules.create', 'display_name' => 'Create Class Schedules', 'module' => 'Timetable'],
            ['name' => 'timetable.schedules.edit', 'display_name' => 'Edit Class Schedules', 'module' => 'Timetable'],
            ['name' => 'timetable.schedules.delete', 'display_name' => 'Delete Class Schedules', 'module' => 'Timetable'],
            
            ['name' => 'timetable.teacher_availabilities.view', 'display_name' => 'View Teacher Availabilities', 'module' => 'Timetable'],
            ['name' => 'timetable.teacher_availabilities.create', 'display_name' => 'Create Teacher Availabilities', 'module' => 'Timetable'],
            ['name' => 'timetable.teacher_availabilities.edit', 'display_name' => 'Edit Teacher Availabilities', 'module' => 'Timetable'],
            ['name' => 'timetable.teacher_availabilities.delete', 'display_name' => 'Delete Teacher Availabilities', 'module' => 'Timetable'],
            
            ['name' => 'timetable.rooms.view', 'display_name' => 'View Rooms', 'module' => 'Timetable'],
            ['name' => 'timetable.rooms.create', 'display_name' => 'Create Rooms', 'module' => 'Timetable'],
            ['name' => 'timetable.rooms.edit', 'display_name' => 'Edit Rooms', 'module' => 'Timetable'],
            ['name' => 'timetable.rooms.delete', 'display_name' => 'Delete Rooms', 'module' => 'Timetable'],
            
            ['name' => 'timetable.room_allocations.view', 'display_name' => 'View Room Allocations', 'module' => 'Timetable'],
            ['name' => 'timetable.room_allocations.create', 'display_name' => 'Create Room Allocations', 'module' => 'Timetable'],
            ['name' => 'timetable.room_allocations.edit', 'display_name' => 'Edit Room Allocations', 'module' => 'Timetable'],
            ['name' => 'timetable.room_allocations.delete', 'display_name' => 'Delete Room Allocations', 'module' => 'Timetable'],
            
            // Attendance Module Permissions
            ['name' => 'attendance.view', 'display_name' => 'View Attendance', 'module' => 'Attendance'],
            ['name' => 'attendance.mark.view', 'display_name' => 'View Mark Attendance', 'module' => 'Attendance'],
            ['name' => 'attendance.mark.create', 'display_name' => 'Create Mark Attendance', 'module' => 'Attendance'],
            ['name' => 'attendance.mark.edit', 'display_name' => 'Edit Mark Attendance', 'module' => 'Attendance'],
            
            ['name' => 'attendance.reports.view', 'display_name' => 'View Attendance Reports', 'module' => 'Attendance'],
            ['name' => 'attendance.reports.create', 'display_name' => 'Create Attendance Reports', 'module' => 'Attendance'],
            ['name' => 'attendance.reports.export', 'display_name' => 'Export Attendance Reports', 'module' => 'Attendance'],
            
            ['name' => 'attendance.settings.view', 'display_name' => 'View Attendance Settings', 'module' => 'Attendance'],
            ['name' => 'attendance.settings.edit', 'display_name' => 'Edit Attendance Settings', 'module' => 'Attendance'],
            
            ['name' => 'attendance.biometric_logs.view', 'display_name' => 'View Biometric Logs', 'module' => 'Attendance'],
            ['name' => 'attendance.biometric_logs.create', 'display_name' => 'Create Biometric Logs', 'module' => 'Attendance'],
            ['name' => 'attendance.biometric_logs.edit', 'display_name' => 'Edit Biometric Logs', 'module' => 'Attendance'],
            
            ['name' => 'attendance.qr_logs.view', 'display_name' => 'View QR Logs', 'module' => 'Attendance'],
            ['name' => 'attendance.qr_logs.create', 'display_name' => 'Create QR Logs', 'module' => 'Attendance'],
            ['name' => 'attendance.qr_logs.edit', 'display_name' => 'Edit QR Logs', 'module' => 'Attendance'],
            
            ['name' => 'attendance.face_logs.view', 'display_name' => 'View Face Logs', 'module' => 'Attendance'],
            ['name' => 'attendance.face_logs.create', 'display_name' => 'Create Face Logs', 'module' => 'Attendance'],
            ['name' => 'attendance.face_logs.edit', 'display_name' => 'Edit Face Logs', 'module' => 'Attendance'],
            
            ['name' => 'attendance.acknowledgment_logs.view', 'display_name' => 'View Acknowledgment Logs', 'module' => 'Attendance'],
            ['name' => 'attendance.acknowledgment_logs.create', 'display_name' => 'Create Acknowledgment Logs', 'module' => 'Attendance'],
            ['name' => 'attendance.acknowledgment_logs.edit', 'display_name' => 'Edit Acknowledgment Logs', 'module' => 'Attendance'],
            
            // Communication Module Permissions
            ['name' => 'communication.view', 'display_name' => 'View Communication', 'module' => 'Communication'],
            ['name' => 'communication.inbox.view', 'display_name' => 'View Inbox', 'module' => 'Communication'],
            ['name' => 'communication.inbox.create', 'display_name' => 'Create Inbox', 'module' => 'Communication'],
            ['name' => 'communication.inbox.edit', 'display_name' => 'Edit Inbox', 'module' => 'Communication'],
            
            ['name' => 'communication.compose.view', 'display_name' => 'View Compose', 'module' => 'Communication'],
            ['name' => 'communication.compose.create', 'display_name' => 'Create Compose', 'module' => 'Communication'],
            ['name' => 'communication.compose.edit', 'display_name' => 'Edit Compose', 'module' => 'Communication'],
            
            ['name' => 'communication.announcements.view', 'display_name' => 'View Announcements', 'module' => 'Communication'],
            ['name' => 'communication.announcements.create', 'display_name' => 'Create Announcements', 'module' => 'Communication'],
            ['name' => 'communication.announcements.edit', 'display_name' => 'Edit Announcements', 'module' => 'Communication'],
            ['name' => 'communication.announcements.delete', 'display_name' => 'Delete Announcements', 'module' => 'Communication'],
            
            // Portal Module Permissions
            ['name' => 'portal.view', 'display_name' => 'View Portal', 'module' => 'Portal'],
            ['name' => 'portal.student.view', 'display_name' => 'View Student Portal', 'module' => 'Portal'],
            ['name' => 'portal.parent.view', 'display_name' => 'View Parent Portal', 'module' => 'Portal'],
            
            // LMS Module Permissions
            ['name' => 'lms.view', 'display_name' => 'View LMS', 'module' => 'LMS'],
            ['name' => 'lms.courses.view', 'display_name' => 'View Courses', 'module' => 'LMS'],
            ['name' => 'lms.courses.create', 'display_name' => 'Create Courses', 'module' => 'LMS'],
            ['name' => 'lms.courses.edit', 'display_name' => 'Edit Courses', 'module' => 'LMS'],
            ['name' => 'lms.courses.delete', 'display_name' => 'Delete Courses', 'module' => 'LMS'],
            
            ['name' => 'lms.lessons.view', 'display_name' => 'View Lessons', 'module' => 'LMS'],
            ['name' => 'lms.lessons.create', 'display_name' => 'Create Lessons', 'module' => 'LMS'],
            ['name' => 'lms.lessons.edit', 'display_name' => 'Edit Lessons', 'module' => 'LMS'],
            ['name' => 'lms.lessons.delete', 'display_name' => 'Delete Lessons', 'module' => 'LMS'],
            
            ['name' => 'lms.assignments.view', 'display_name' => 'View Assignments', 'module' => 'LMS'],
            ['name' => 'lms.assignments.create', 'display_name' => 'Create Assignments', 'module' => 'LMS'],
            ['name' => 'lms.assignments.edit', 'display_name' => 'Edit Assignments', 'module' => 'LMS'],
            ['name' => 'lms.assignments.delete', 'display_name' => 'Delete Assignments', 'module' => 'LMS'],
            
            ['name' => 'lms.quizzes.view', 'display_name' => 'View Quizzes', 'module' => 'LMS'],
            ['name' => 'lms.quizzes.create', 'display_name' => 'Create Quizzes', 'module' => 'LMS'],
            ['name' => 'lms.quizzes.edit', 'display_name' => 'Edit Quizzes', 'module' => 'LMS'],
            ['name' => 'lms.quizzes.delete', 'display_name' => 'Delete Quizzes', 'module' => 'LMS'],
            
            // Document Module Permissions
            ['name' => 'document.view', 'display_name' => 'View Document', 'module' => 'Document'],
            ['name' => 'document.upload.view', 'display_name' => 'View Upload', 'module' => 'Document'],
            ['name' => 'document.upload.create', 'display_name' => 'Create Upload', 'module' => 'Document'],
            ['name' => 'document.upload.edit', 'display_name' => 'Edit Upload', 'module' => 'Document'],
            ['name' => 'document.upload.delete', 'display_name' => 'Delete Upload', 'module' => 'Document'],
            
            ['name' => 'document.manage.view', 'display_name' => 'View Manage', 'module' => 'Document'],
            ['name' => 'document.manage.create', 'display_name' => 'Create Manage', 'module' => 'Document'],
            ['name' => 'document.manage.edit', 'display_name' => 'Edit Manage', 'module' => 'Document'],
            ['name' => 'document.manage.delete', 'display_name' => 'Delete Manage', 'module' => 'Document'],
            
            // Notification Module Permissions
            ['name' => 'notification.view', 'display_name' => 'View Notification', 'module' => 'Notification'],
            ['name' => 'notification.manage.view', 'display_name' => 'View Manage', 'module' => 'Notification'],
            ['name' => 'notification.manage.create', 'display_name' => 'Create Manage', 'module' => 'Notification'],
            ['name' => 'notification.manage.edit', 'display_name' => 'Edit Manage', 'module' => 'Notification'],
            ['name' => 'notification.manage.delete', 'display_name' => 'Delete Manage', 'module' => 'Notification'],
            
            // API Module Permissions
            ['name' => 'api.view', 'display_name' => 'View API', 'module' => 'API'],
            ['name' => 'api.manage.view', 'display_name' => 'View Manage', 'module' => 'API'],
            ['name' => 'api.manage.create', 'display_name' => 'Create Manage', 'module' => 'API'],
            ['name' => 'api.manage.edit', 'display_name' => 'Edit Manage', 'module' => 'API'],
            ['name' => 'api.manage.delete', 'display_name' => 'Delete Manage', 'module' => 'API'],
            
            // Analytics Module Permissions
            ['name' => 'analytics.view', 'display_name' => 'View Analytics', 'module' => 'Analytics'],
            ['name' => 'analytics.reports.view', 'display_name' => 'View Reports', 'module' => 'Analytics'],
            ['name' => 'analytics.reports.create', 'display_name' => 'Create Reports', 'module' => 'Analytics'],
            ['name' => 'analytics.reports.edit', 'display_name' => 'Edit Reports', 'module' => 'Analytics'],
            ['name' => 'analytics.reports.export', 'display_name' => 'Export Reports', 'module' => 'Analytics'],
            
            // ChatBot Module Permissions
            ['name' => 'chatbot.view', 'display_name' => 'View ChatBot', 'module' => 'ChatBot'],
            ['name' => 'chatbot.manage.view', 'display_name' => 'View Manage', 'module' => 'ChatBot'],
            ['name' => 'chatbot.manage.create', 'display_name' => 'Create Manage', 'module' => 'ChatBot'],
            ['name' => 'chatbot.manage.edit', 'display_name' => 'Edit Manage', 'module' => 'ChatBot'],
            ['name' => 'chatbot.manage.delete', 'display_name' => 'Delete Manage', 'module' => 'ChatBot'],
            
            // Marketplace Module Permissions
            ['name' => 'marketplace.view', 'display_name' => 'View Marketplace', 'module' => 'Marketplace'],
            ['name' => 'marketplace.manage.view', 'display_name' => 'View Manage', 'module' => 'Marketplace'],
            ['name' => 'marketplace.manage.create', 'display_name' => 'Create Manage', 'module' => 'Marketplace'],
            ['name' => 'marketplace.manage.edit', 'display_name' => 'Edit Manage', 'module' => 'Marketplace'],
            ['name' => 'marketplace.manage.delete', 'display_name' => 'Delete Manage', 'module' => 'Marketplace'],
            
            // PWA Module Permissions
            ['name' => 'pwa.view', 'display_name' => 'View PWA', 'module' => 'PWA'],
            ['name' => 'pwa.manage.view', 'display_name' => 'View Manage', 'module' => 'PWA'],
            ['name' => 'pwa.manage.create', 'display_name' => 'Create Manage', 'module' => 'PWA'],
            ['name' => 'pwa.manage.edit', 'display_name' => 'Edit Manage', 'module' => 'PWA'],
            ['name' => 'pwa.manage.delete', 'display_name' => 'Delete Manage', 'module' => 'PWA'],
            
            // Research Module Permissions
            ['name' => 'research.view', 'display_name' => 'View Research', 'module' => 'Research'],
            ['name' => 'research.manage.view', 'display_name' => 'View Manage', 'module' => 'Research'],
            ['name' => 'research.manage.create', 'display_name' => 'Create Manage', 'module' => 'Research'],
            ['name' => 'research.manage.edit', 'display_name' => 'Edit Manage', 'module' => 'Research'],
            ['name' => 'research.manage.delete', 'display_name' => 'Delete Manage', 'module' => 'Research'],
            
            // Alumni Module Permissions
            ['name' => 'alumni.view', 'display_name' => 'View Alumni', 'module' => 'Alumni'],
            ['name' => 'alumni.manage', 'display_name' => 'Manage Alumni', 'module' => 'Alumni'],
            
            // Localization Module Permissions
            ['name' => 'localization.view', 'display_name' => 'View Localization', 'module' => 'Localization'],
            ['name' => 'localization.manage', 'display_name' => 'Manage Localization', 'module' => 'Localization'],
        ];

        // Create all permissions
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                [
                    'display_name' => $permission['display_name'],
                    'module' => $permission['module'],
                    'is_system' => true,
                ]
            );
        }

        $this->command->info('All system permissions have been created successfully!');
        $this->command->info('Total permissions created: ' . count($allPermissions));
    }
} 