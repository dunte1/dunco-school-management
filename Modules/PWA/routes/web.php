<?php

use Illuminate\Support\Facades\Route;
use Modules\PWA\Http\Controllers\PWAController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('pwas', PWAController::class)->names('pwa');
});
