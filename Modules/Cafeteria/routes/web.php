<?php

use Illuminate\Support\Facades\Route;
use Modules\Cafeteria\Http\Controllers\CafeteriaController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('cafeterias', CafeteriaController::class)->names('cafeteria');
});
