<?php

use Illuminate\Support\Facades\Route;
use Modules\Hostel\Http\Controllers\HostelController;
use Modules\Hostel\Http\Controllers\HostelReportController;
use Modules\Hostel\Http\Controllers\FloorController;
use Modules\Hostel\Http\Controllers\RoomController;
use Modules\Hostel\Http\Controllers\BedController;
use Modules\Hostel\Http\Controllers\RoomAllocationController;
use Modules\Hostel\Http\Controllers\HostelFeeController;
use Modules\Hostel\Http\Controllers\HostelIssueController;
use Modules\Hostel\Http\Controllers\HostelVisitorController;
use Modules\Hostel\Http\Controllers\LeaveRequestController;
use Modules\Hostel\Http\Controllers\WardenController;
use Modules\Hostel\Http\Controllers\HostelAnnouncementController;

// Test route without middleware
Route::get('hostel/test', function() {
    return 'Hostel test route working!';
})->name('hostel.test');

Route::prefix('hostel')->middleware(['web', 'auth'])->group(function () {
    Route::get('dashboard', [HostelController::class, 'dashboard'])->name('hostel.dashboard');
    Route::resource('hostels', HostelController::class)->names('hostel.hostels');
    Route::resource('floors', FloorController::class)->names('hostel.floors');
    Route::resource('rooms', RoomController::class)->names('hostel.rooms');
    Route::resource('beds', BedController::class)->names('hostel.beds');
    Route::resource('room-allocations', RoomAllocationController::class)->names('hostel.room_allocations');
    Route::resource('fees', HostelFeeController::class)->names('hostel.fees');
    Route::resource('issues', HostelIssueController::class)->names('hostel.issues');
    Route::resource('visitors', HostelVisitorController::class)->names('hostel.visitors');
    Route::resource('leave-requests', LeaveRequestController::class)->names('hostel.leave_requests');
    Route::resource('wardens', WardenController::class)->names('hostel.wardens');
    Route::resource('announcements', HostelAnnouncementController::class)->names('hostel.announcements');

    // Reports
    Route::get('reports/dashboard', [HostelReportController::class, 'dashboard'])->name('hostel.reports.dashboard');
    Route::get('reports/occupancy', [HostelReportController::class, 'occupancy'])->name('hostel.reports.occupancy');
    Route::get('reports/allocation', [HostelReportController::class, 'allocation'])->name('hostel.reports.allocation');
    Route::get('reports/maintenance', [HostelReportController::class, 'maintenance'])->name('hostel.reports.maintenance');
    Route::get('reports/movement', [HostelReportController::class, 'movement'])->name('hostel.reports.movement');
    Route::get('reports/defaulters', [HostelReportController::class, 'defaulters'])->name('hostel.reports.defaulters');
    Route::get('reports/availability', [HostelReportController::class, 'availability'])->name('hostel.reports.availability');
    Route::get('reports/damage', [HostelReportController::class, 'damage'])->name('hostel.reports.damage');
});
