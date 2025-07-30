<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Admin\Config\RequiredDocumentController;
use App\Http\Controllers\Admin\Config\FeeConfigurationController;
use Modules\Finance\Http\Controllers\MpesaCallbackController;

// Main dashboard
Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('home');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

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

// Finance page
Route::get('/finance', [\App\Http\Controllers\FinanceController::class, 'index'])->middleware(['auth'])->name('finance.index');

// M-Pesa Daraja API Callback Routes
Route::prefix('finance/payment')->name('finance.payment.')->group(function () {
    // STK Push Callback
    Route::post('mpesa-stk-callback', [MpesaCallbackController::class, 'stkCallback'])->name('mpesa-stk-callback');
    
    // C2B Callbacks
    Route::post('c2b-confirmation', [MpesaCallbackController::class, 'c2bConfirmation'])->name('c2b-confirmation');
    Route::post('c2b-validation', [MpesaCallbackController::class, 'c2bValidation'])->name('c2b-validation');
    
    // B2C Result
    Route::post('b2c-result', [MpesaCallbackController::class, 'b2cResult'])->name('b2c-result');
    
    // Transaction Reversal
    Route::post('reversal-result', [MpesaCallbackController::class, 'reversalResult'])->name('reversal-result');
    
    // Timeout URLs
    Route::post('mpesa-timeout', [MpesaCallbackController::class, 'timeout'])->name('mpesa-timeout');
    Route::post('mpesa-reverse-timeout', [MpesaCallbackController::class, 'timeout'])->name('mpesa-reverse-timeout');
    
    // API Test
    Route::get('mpesa-test-connection', [MpesaCallbackController::class, 'testConnection'])->name('mpesa-test-connection');
});

// Notifications
Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->middleware(['auth'])->name('notification.index');

// Audit logs
Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->middleware(['auth', 'admin'])->name('audit_logs.index');



// Optional dev-only permission test (safe to comment/remove before production)
if (Schema::hasTable('permissions')) {
    Route::get('/test-permission', function () {
        \Log::info('Test route hit');
        return 'Test route works!';
    })->middleware('permission:timetable.view');
}

// Debug route for permission checking
Route::get('/debug/permissions', [App\Http\Controllers\DebugController::class, 'permissions'])
    ->middleware(['auth'])
    ->name('debug.permissions');

// Sidebar update test page
Route::get('/test-sidebar-update', function () {
    return view('test-sidebar-update');
})->middleware(['auth'])->name('test.sidebar.update');

// Performance routes
Route::prefix('performance')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\PerformanceController::class, 'dashboard'])->name('performance.dashboard');
    Route::post('/optimize', [App\Http\Controllers\PerformanceController::class, 'optimize'])->name('performance.optimize');
    Route::get('/stats', [App\Http\Controllers\PerformanceController::class, 'stats'])->name('performance.stats');
    Route::post('/clear-caches', [App\Http\Controllers\PerformanceController::class, 'clearCaches'])->name('performance.clear-caches');
});

// Auth routes
require __DIR__.'/auth.php';
