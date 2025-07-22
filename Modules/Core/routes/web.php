<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\CoreController;
use Modules\Core\Http\Controllers\SchoolController;
use Modules\Core\Http\Controllers\UserController;
use Modules\Core\Http\Controllers\RoleController;
use Modules\Core\Http\Controllers\PermissionController;
use Modules\Core\Http\Controllers\AuditLogController;
use Modules\Core\Http\Controllers\SchoolSettingController;

// All core routes are protected by 'web' (for session) and 'auth' (must be logged in)
Route::middleware(['web', 'auth'])->prefix('core')->group(function () {
    Route::get('/', [CoreController::class, 'index'])->name('core.dashboard');
    Route::resource('schools', SchoolController::class)->names('core.schools');
    Route::resource('users', UserController::class)->names('core.users');
    Route::resource('roles', RoleController::class)->names('core.roles');
    Route::resource('permissions', PermissionController::class)->names('core.permissions');
    Route::resource('audit-logs', AuditLogController::class)->names('core.audit_logs');
    Route::resource('settings', SchoolSettingController::class)->names('core.settings');
    Route::get('audit-logs/fetch', [Modules\Core\Http\Controllers\AuditLogController::class, 'fetch'])->name('core.audit-logs.fetch');
    Route::post('roles/{role}/clone-permissions', [RoleController::class, 'clonePermissions'])->name('core.roles.clone_permissions');
});

// Test route outside of auth
Route::get('/core-test', function () {
    return 'Core Module route is working!';
});
