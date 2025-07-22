<?php

use Illuminate\Support\Facades\Route;
use Modules\Welfare\Http\Controllers\WelfareController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('welfares', WelfareController::class)->names('welfare');
});
