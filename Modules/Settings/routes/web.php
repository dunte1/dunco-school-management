<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('settings', SettingsController::class)->names('settings');
    Route::get('settings-global', [SettingsController::class, 'global'])->name('settings.global');
    Route::post('settings-global', [SettingsController::class, 'updateGlobal'])->name('settings.global.update');
    Route::get('settings-per-school', [SettingsController::class, 'perSchool'])->name('settings.per_school');
    Route::post('settings-per-school', [SettingsController::class, 'updatePerSchool'])->name('settings.per_school.update');
});
