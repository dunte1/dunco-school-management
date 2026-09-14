<?php

use Illuminate\Support\Facades\Route;
use Modules\Settings\Http\Controllers\SettingsController;

Route::middleware(['auth:sanctum','throttle:60,1'])->prefix('v1')->group(function () {
    Route::apiResource('settings', SettingsController::class)->names('settings.api');
});

