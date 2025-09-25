<?php

use Illuminate\Support\Facades\Route;
use Modules\Attendance\Http\Controllers\AttendanceController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('attendances', AttendanceController::class)->names('attendance_module');
    Route::get('attendance/dashboard', [AttendanceController::class, 'dashboard'])->name('attendance.dashboard');
    Route::get('attendance/mark', [AttendanceController::class, 'mark'])->name('attendance.mark');
    Route::get('attendance/reports', [AttendanceController::class, 'reports'])->name('attendance.reports');
    Route::get('attendance/settings', [AttendanceController::class, 'settings'])->name('attendance.settings');
});

