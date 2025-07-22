<?php

use Illuminate\Support\Facades\Route;
use Modules\Assets\Http\Controllers\AssetsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('assets', AssetsController::class)->names('assets');
});
