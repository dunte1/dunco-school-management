<?php

use Illuminate\Support\Facades\Route;
use Modules\Academic\Http\Controllers\AcademicController;
use Modules\Academic\app\Http\Controllers\Api\StudentApiController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('academics', AcademicController::class)->names('academic');
});

Route::get('/students', [StudentApiController::class, 'index']);
