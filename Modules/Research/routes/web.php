<?php

use Illuminate\Support\Facades\Route;
use Modules\Research\Http\Controllers\ResearchController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('research', ResearchController::class)->names('research');
});
