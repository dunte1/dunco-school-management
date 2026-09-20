<?php

use Illuminate\Support\Facades\Route;
use Modules\HR\Http\Controllers\HRController;
use Modules\HR\Http\Controllers\StaffController;
use Modules\HR\Http\Controllers\LeaveController;
use Modules\HR\Http\Controllers\PayrollController;
use Modules\HR\Http\Controllers\DepartmentController;
use Modules\HR\Http\Controllers\ContractController;
use Modules\HR\Http\Controllers\AttendanceController;
use Modules\HR\Http\Controllers\LeaveTypeController;
use Modules\HR\Http\Controllers\PerformanceReviewController;

Route::prefix('hr')->name('hr.')->middleware(['auth', 'admin'])->group(function () {
    // HR Dashboard
    Route::get('/', [HRController::class, 'index'])->name('index');

    // Staff Management
    Route::resource('staff', StaffController::class)->names('staff');

    // Leave Management
    Route::get('leave', [LeaveController::class, 'index'])->name('leave.index');
    Route::get('leave/create', [LeaveController::class, 'create'])->name('leave.create');
    Route::post('leave', [LeaveController::class, 'store'])->name('leave.store');
    Route::post('leave/{id}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
    Route::post('leave/{id}/reject', [LeaveController::class, 'reject'])->name('leave.reject');
    Route::get('leave/balances', [LeaveController::class, 'balances'])->name('leave.balances');

    // Payroll Management
    Route::get('payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('payroll/create', [PayrollController::class, 'create'])->name('payroll.create');
    Route::post('payroll', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('payroll/{id}', [PayrollController::class, 'show'])->name('payroll.show');
    Route::get('payroll/{id}/edit', [PayrollController::class, 'edit'])->name('payroll.edit');
    Route::put('payroll/{id}', [PayrollController::class, 'update'])->name('payroll.update');
    Route::delete('payroll/{id}', [PayrollController::class, 'destroy'])->name('payroll.destroy');
    Route::post('payroll/{id}/mark-paid', [PayrollController::class, 'markPaid'])->name('payroll.mark-paid');

    // Department Management
    Route::resource('departments', DepartmentController::class)->names('departments');

    // Contract Management
    Route::resource('contracts', ContractController::class)->names('contracts');

    // Attendance Management
    Route::resource('attendance', AttendanceController::class)->names('attendance');

    // Leave Type Management
    Route::resource('leave-types', LeaveTypeController::class)->names('leave_types');

    // Performance Reviews
    Route::resource('performance-reviews', PerformanceReviewController::class)->names('performance_reviews');
});
