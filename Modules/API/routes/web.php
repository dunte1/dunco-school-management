<?php

use Illuminate\Support\Facades\Route;
use Modules\API\Http\Controllers\APIController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Specific API endpoints (must come before resource route)
    Route::get('apis/users', [APIController::class, 'users'])->name('api.users');
    Route::get('apis/hostels', [APIController::class, 'hostels'])->name('api.hostels');
    Route::get('apis/students', [APIController::class, 'students'])->name('api.students');
    Route::get('apis/staff', [APIController::class, 'staff'])->name('api.staff');
    Route::get('apis/classes', [APIController::class, 'classes'])->name('api.classes');
    Route::get('apis/room-allocations', [APIController::class, 'roomAllocations'])->name('api.room-allocations');
    Route::get('apis/stats', [APIController::class, 'stats'])->name('api.stats');
    
    // Resource route (must come after specific routes)
    Route::resource('apis', APIController::class)->names('api');
});
