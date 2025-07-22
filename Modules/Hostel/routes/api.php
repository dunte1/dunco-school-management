<?php

use Illuminate\Support\Facades\Route;
use Modules\Hostel\Http\Controllers\HostelController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('hostels', HostelController::class)->names('hostel');
});
