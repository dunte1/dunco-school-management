<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;
use Modules\Settings\Http\Controllers\ModuleManagementController;

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::resource('settings', SettingsController::class)->names('settings');
    Route::get('settings-global', [SettingsController::class, 'global'])->name('settings.global');
    Route::post('settings-global', [SettingsController::class, 'updateGlobal'])->name('settings.global.update');
    Route::get('settings-per-school', [SettingsController::class, 'perSchool'])->name('settings.per_school');
    Route::post('settings-per-school', [SettingsController::class, 'updatePerSchool'])->name('settings.per_school.update');

    // AJAX routes for real-time updates
    Route::post('settings-global-ajax', [SettingsController::class, 'updateGlobalAjax'])->name('settings.global.ajax');
    Route::post('settings-per-school-ajax', [SettingsController::class, 'updatePerSchoolAjax'])->name('settings.per_school.ajax');
    Route::get('settings-get-ajax', [SettingsController::class, 'getSettingsAjax'])->name('settings.get.ajax');

    // Module Management
    Route::get('admin/modules', [ModuleManagementController::class, 'index'])->name('admin.modules.index');
    Route::post('admin/modules/toggle', [ModuleManagementController::class, 'toggle'])->name('admin.modules.toggle');
});

