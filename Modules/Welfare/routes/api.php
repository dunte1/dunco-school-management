<?php

use Illuminate\Support\Facades\Route;
use Modules\Welfare\Http\Controllers\WelfareController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('welfares', WelfareController::class)->names('welfare.api');
});
