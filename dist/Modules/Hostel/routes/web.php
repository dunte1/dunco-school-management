<?php

use Illuminate\Support\Facades\Route;
use Modules\Hostel\Http\Controllers\HostelController;
use Modules\Hostel\Http\Controllers\RoomAllocationController;
use Modules\Hostel\Http\Controllers\RoomController;
use Modules\Hostel\Http\Controllers\FloorController;
use Modules\Hostel\Http\Controllers\BedController;
use Modules\Hostel\Http\Controllers\HostelFeeController;
use Modules\Hostel\Http\Controllers\HostelIssueController;
use Modules\Hostel\Http\Controllers\LeaveRequestController;
use Modules\Hostel\Http\Controllers\HostelVisitorController;
use Modules\Hostel\Http\Controllers\HostelAnnouncementController;
use Modules\Hostel\Http\Controllers\WardenController;
use Modules\Hostel\Http\Controllers\HostelReportController;

Route::middleware(['web', 'auth'])->prefix('hostel')->group(function () {
    Route::get('/', [HostelController::class, 'dashboard'])->name('hostel.dashboard');
    Route::resource('hostels', HostelController::class)->names('hostel.hostels');
    Route::resource('room_allocations', RoomAllocationController::class)->names('hostel.room_allocations');
    Route::resource('rooms', RoomController::class)->names('hostel.rooms');
    Route::resource('floors', FloorController::class)->names('hostel.floors');
    Route::resource('beds', BedController::class)->names('hostel.beds');
    Route::resource('fees', HostelFeeController::class)->names('hostel.fees');
    Route::resource('issues', HostelIssueController::class)->names('hostel.issues');
    Route::resource('leave_requests', LeaveRequestController::class)->names('hostel.leave_requests');
    Route::resource('visitors', HostelVisitorController::class)->names('hostel.visitors');
    Route::resource('announcements', HostelAnnouncementController::class)->names('hostel.announcements');
    Route::resource('wardens', WardenController::class)->names('hostel.wardens');
    Route::get('reports/dashboard', [HostelReportController::class, 'dashboard'])->name('hostel.reports.dashboard');
});

