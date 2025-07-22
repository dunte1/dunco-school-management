<?php

use Illuminate\Support\Facades\Route;
use Modules\Marketplace\Http\Controllers\MarketplaceController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('marketplaces', MarketplaceController::class)->names('marketplace');
});
