<?php

use Illuminate\Support\Facades\Route;
use Modules\Transport\Http\Controllers\TransportController;
use Modules\Transport\Http\Controllers\VehicleController;
use Modules\Transport\Http\Controllers\DriverController;
use Modules\Transport\Http\Controllers\RouteController;
use Modules\Transport\Http\Controllers\TripController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Main transport dashboard
    Route::get('/transport', [TransportController::class, 'index'])->name('transport.index');
    
    // Reports
    Route::get('/transport/reports', [TransportController::class, 'reports'])->name('transport.reports');
    
    // Vehicles
    Route::resource('/transport/vehicles', VehicleController::class)->names('transport.vehicles');
    
    // Drivers
    Route::resource('/transport/drivers', DriverController::class)->names('transport.drivers');
    
    // Routes
    Route::resource('/transport/routes', RouteController::class)->names('transport.routes');
    
    // Trips
    Route::resource('/transport/trips', TripController::class)->names('transport.trips');
});
