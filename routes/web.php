<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Root route - show welcome page (must be first to avoid conflicts)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Config\RequiredDocumentController;
use App\Http\Controllers\Admin\Config\FeeConfigurationController;

// Authenticated user routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Attendance module views
    Route::view('/attendance/session-templates', 'modules.attendance.session_templates')->name('attendance.session_templates');
    Route::view('/attendance/past-records', 'modules.attendance.past_records')->name('attendance.past_records');
    Route::view('/attendance/biometric-logs', 'modules.attendance.biometric_logs')->name('attendance.biometric_logs');
    Route::view('/attendance/qr-logs', 'modules.attendance.qr_logs')->name('attendance.qr_logs');
    Route::view('/attendance/face-logs', 'modules.attendance.face_logs')->name('attendance.face_logs');
    Route::view('/attendance/acknowledgment-logs', 'modules.attendance.acknowledgment_logs')->name('attendance.acknowledgment_logs');
});

// Admin config routes
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

// Audit logs
Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->middleware(['auth', 'admin'])->name('audit_logs.index');

// Debug route for permission checking
Route::get('/debug/permissions', [App\Http\Controllers\DebugController::class, 'permissions'])
    ->middleware(['auth'])
    ->name('debug.permissions');

// Performance routes
Route::prefix('performance')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\PerformanceController::class, 'dashboard'])->name('performance.dashboard');
    Route::post('/optimize', [App\Http\Controllers\PerformanceController::class, 'optimize'])->name('performance.optimize');
    Route::get('/stats', [App\Http\Controllers\PerformanceController::class, 'stats'])->name('performance.stats');
    Route::post('/clear-caches', [App\Http\Controllers\PerformanceController::class, 'clearCaches'])->name('performance.clear-caches');
});

// Auth routes
require __DIR__.'/auth.php';

// Load module routes
require __DIR__.'/modules.php';
