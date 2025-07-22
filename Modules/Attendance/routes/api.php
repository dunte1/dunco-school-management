<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\Http\Controllers\AttendanceController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('attendances', AttendanceController::class)->names('attendance');
    Route::get('attendance/dashboard/stats', [\Modules\Attendance\Http\Controllers\DashboardController::class, 'stats'])->name('attendance.dashboard.stats');
    Route::get('attendance/dashboard/trend', [\Modules\Attendance\Http\Controllers\DashboardController::class, 'trendData']);
    Route::get('attendance/dashboard/staff-summary', [\Modules\Attendance\Http\Controllers\DashboardController::class, 'staffSummary']);
    Route::get('attendance/dashboard/staff-trend', [\Modules\Attendance\Http\Controllers\DashboardController::class, 'staffTrend']);
    Route::get('attendance/classes', [\Modules\Attendance\Http\Controllers\AttendanceController::class, 'getClasses']);
    Route::get('attendance/subjects', [\Modules\Attendance\Http\Controllers\AttendanceController::class, 'getSubjects']);
    Route::get('attendance/sessions', [\Modules\Attendance\Http\Controllers\AttendanceController::class, 'getSessions']);
    Route::get('attendance/students', [\Modules\Attendance\Http\Controllers\AttendanceController::class, 'getStudents']);
    Route::get('attendance/report', [\Modules\Attendance\Http\Controllers\AttendanceController::class, 'getReport']);
    Route::get('attendance/report/export/excel', [\Modules\Attendance\Http\Controllers\AttendanceController::class, 'exportExcel']);
    Route::post('attendance/save', [\Modules\Attendance\Http\Controllers\AttendanceController::class, 'storeAttendance']);
    Route::get('attendance/settings', [\Modules\Attendance\Http\Controllers\AttendanceController::class, 'getSettings']);
    Route::post('attendance/settings', [\Modules\Attendance\Http\Controllers\AttendanceController::class, 'saveSettings']);
});
