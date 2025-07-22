<?php

use Illuminate\Support\Facades\Route;
use Modules\Marketplace\Http\Controllers\MarketplaceController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('marketplaces', MarketplaceController::class)->names('marketplace.api');
});
