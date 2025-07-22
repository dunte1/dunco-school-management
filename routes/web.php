<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\Config\RequiredDocumentController;
use App\Http\Controllers\Admin\Config\FeeConfigurationController;

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Core module routes
    // Removed duplicate resource routes for core module. Let the module handle its own routes.
    Route::get('/attendance/session-templates', function() {
        return view('modules.attendance.session_templates');
    })->name('attendance.session_templates');
    Route::get('/attendance/past-records', function() {
        return view('modules.attendance.past_records');
    })->name('attendance.past_records');
    Route::view('/attendance/biometric-logs', 'modules.attendance.biometric_logs')->name('attendance.biometric_logs');
    Route::view('/attendance/qr-logs', 'modules.attendance.qr_logs')->name('attendance.qr_logs');
    Route::view('/attendance/face-logs', 'modules.attendance.face_logs')->name('attendance.face_logs');
    Route::view('/attendance/acknowledgment-logs', 'modules.attendance.acknowledgment_logs')->name('attendance.acknowledgment_logs');
});

Route::prefix('admin/config')->middleware(['web', 'auth', 'admin'])->group(function () {
    // Required Documents
    Route::get('documents', [RequiredDocumentController::class, 'index'])->name('admin.config.documents.index');
    Route::post('documents', [RequiredDocumentController::class, 'store'])->name('admin.config.documents.store');
    Route::put('documents/{requiredDocument}', [RequiredDocumentController::class, 'update'])->name('admin.config.documents.update');
    Route::delete('documents/{requiredDocument}', [RequiredDocumentController::class, 'destroy'])->name('admin.config.documents.destroy');

    // Fee Configurations
    Route::get('fees', [FeeConfigurationController::class, 'index'])->name('admin.config.fees.index');
    Route::post('fees', [FeeConfigurationController::class, 'store'])->name('admin.config.fees.store');
    Route::put('fees/{feeConfiguration}', [FeeConfigurationController::class, 'update'])->name('admin.config.fees.update');
    Route::delete('fees/{feeConfiguration}', [FeeConfigurationController::class, 'destroy'])->name('admin.config.fees.destroy');
});

Route::get('/finance', [\App\Http\Controllers\FinanceController::class, 'index']);

Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notification.index');

Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit_logs.index');

Route::get('/test-permission', function () {
    \Log::info('Test route hit');
    return 'Test route works!';
})->middleware('permission:timetable.view');

Route::get('/test-portal', function () {
    \Log::info('Test portal route hit');
    return 'Test portal route hit';
});

require __DIR__.'/auth.php';
