<?php

use Illuminate\Support\Facades\Route;
use Modules\Communication\Http\Controllers\CommunicationController;
use Modules\Communication\Http\Controllers\MessageController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('communications', CommunicationController::class)->names('communication.api');
    Route::apiResource('messages', MessageController::class)->names('messages.api');
});
