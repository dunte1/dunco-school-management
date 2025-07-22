<?php

use Illuminate\Support\Facades\Route;
use Modules\HR\Http\Controllers\HRController;
use Modules\HR\Http\Controllers\StaffController;
use Modules\HR\Http\Controllers\RoleController;
use Modules\HR\Http\Controllers\PermissionController;
use Modules\HR\Http\Controllers\AttendanceController;
use Modules\HR\Http\Controllers\LeaveController;
use Modules\HR\Http\Controllers\PayrollController;
use Modules\HR\Http\Controllers\ContractController;
use Modules\HR\Http\Controllers\PerformanceReviewController;
use Modules\HR\Http\Controllers\DepartmentController;

Route::middleware(['web', 'auth'])->prefix('hr')->group(function () {
    Route::get('/', [HRController::class, 'index'])->name('hr.index');
    
    Route::resource('staff', StaffController::class)->names('hr.staff');
    Route::resource('roles', RoleController::class)->names('hr.roles');
    Route::resource('permissions', PermissionController::class)->names('hr.permissions');
    Route::resource('departments', DepartmentController::class)->names('hr.departments');

    Route::resource('attendance', AttendanceController::class)->except(['show']);
    Route::post('attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::get('attendance-report', [AttendanceController::class, 'report'])->name('attendance.report');

    Route::resource('leave', LeaveController::class)->names('hr.leave');
    Route::post('leave/{id}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
    Route::post('leave/{id}/reject', [LeaveController::class, 'reject'])->name('leave.reject');
    Route::get('leave-balances', [LeaveController::class, 'balances'])->name('leave.balances');

    Route::resource('payroll', PayrollController::class)->names('hr.payroll');
    Route::post('payroll/{id}/mark-paid', [PayrollController::class, 'markPaid'])->name('hr.payroll.markPaid');

    Route::resource('contract', ContractController::class)->names('hr.contract');
    Route::resource('performance-reviews', PerformanceReviewController::class)->names('hr.performance_reviews');
}); 
