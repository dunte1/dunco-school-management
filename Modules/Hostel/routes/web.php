<?php

use Illuminate\Support\Facades\Route;
use Modules\Hostel\Http\Controllers\HostelController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('hostels', HostelController::class)->names('hostel');
});
