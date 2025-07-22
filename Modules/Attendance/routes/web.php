<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\Http\Controllers\AttendanceController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('attendances', AttendanceController::class)->names('attendance_module');
    Route::get('attendance/dashboard', [\Modules\Attendance\Http\Controllers\AttendanceController::class, 'dashboard'])->name('attendance.dashboard');
    Route::get('attendance/mark', [\Modules\Attendance\Http\Controllers\AttendanceController::class, 'mark'])->name('attendance.mark');
    Route::get('attendance/reports', [\Modules\Attendance\Http\Controllers\AttendanceController::class, 'reports'])->name('attendance.reports');
    Route::get('attendance/settings', [\Modules\Attendance\Http\Controllers\AttendanceController::class, 'settings'])->name('attendance.settings');
});
