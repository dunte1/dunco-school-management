<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\Http\Controllers\AttendanceController;
use Modules\Attendance\Http\Controllers\DashboardController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('attendances', AttendanceController::class)->names('attendance_module');
    Route::get('attendance/dashboard', [AttendanceController::class, 'dashboard'])->name('attendance.dashboard');
    Route::get('attendance/mark', [AttendanceController::class, 'mark'])->name('attendance.mark');
    Route::get('attendance/reports', [AttendanceController::class, 'reports'])->name('attendance.reports');
    Route::get('attendance/settings', [AttendanceController::class, 'settings'])->name('attendance.settings');

    // Additional views
    // View routes moved to routes/web.php

    // Dashboard API
    Route::get('attendance/api/stats', [DashboardController::class, 'stats'])->name('attendance.api.stats');
    Route::get('attendance/api/trend', [DashboardController::class, 'trendData'])->name('attendance.api.trend');
    Route::get('attendance/api/staff-summary', [DashboardController::class, 'staffSummary'])->name('attendance.api.staff-summary');
    Route::get('attendance/api/staff-trend', [DashboardController::class, 'staffTrend'])->name('attendance.api.staff-trend');

    // Attendance APIs
    Route::get('attendance/api/classes', [AttendanceController::class, 'getClasses'])->name('attendance.api.classes');
    Route::get('attendance/api/subjects', [AttendanceController::class, 'getSubjects'])->name('attendance.api.subjects');
    Route::get('attendance/api/sessions', [AttendanceController::class, 'getSessions'])->name('attendance.api.sessions');
    Route::get('attendance/api/students', [AttendanceController::class, 'getStudents'])->name('attendance.api.students');
    Route::post('attendance/api/store', [AttendanceController::class, 'storeAttendance'])->name('attendance.api.store');
    Route::get('attendance/api/report', [AttendanceController::class, 'getReport'])->name('attendance.api.report');
    Route::get('attendance/api/export', [AttendanceController::class, 'exportExcel'])->name('attendance.api.export');
    Route::get('attendance/api/settings', [AttendanceController::class, 'getSettings'])->name('attendance.api.settings');
    Route::post('attendance/api/settings', [AttendanceController::class, 'saveSettings'])->name('attendance.api.settings.save');
    Route::post('attendance/api/bulk-notify', [AttendanceController::class, 'sendBulkNotifications'])->name('attendance.api.bulk-notify');
    Route::post('attendance/api/absent-alerts', [AttendanceController::class, 'sendXDaysAbsentAlerts'])->name('attendance.api.absent-alerts');
});
