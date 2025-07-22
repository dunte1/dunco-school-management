<?php

use Illuminate\Support\Facades\Route;
use Modules\Cafeteria\Http\Controllers\CafeteriaController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('cafeterias', CafeteriaController::class)->names('cafeteria');
});
