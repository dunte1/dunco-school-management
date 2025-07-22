<?php

use Illuminate\Support\Facades\Route;
use Modules\Portal\Http\Controllers\PortalController;
use Modules\Portal\app\Http\Controllers\ApplicationController;

Route::get('/portal', [PortalController::class, 'dashboard'])->name('portal.dashboard');
Route::get('/portal/academics', [PortalController::class, 'academics'])->name('portal.academics');
Route::get('/portal/schedule', [PortalController::class, 'schedule'])->name('portal.schedule');
Route::get('/portal/materials', [PortalController::class, 'materials'])->name('portal.materials');
Route::get('/portal/assignments', [PortalController::class, 'assignments'])->name('portal.assignments');
Route::get('/portal/finance', [PortalController::class, 'finance'])->name('portal.finance');
Route::get('/portal/communication', [PortalController::class, 'communication'])->name('portal.communication');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('portals', PortalController::class)->except(['index'])->names('portal');
});

Route::get('/apply', [ApplicationController::class, 'showForm'])->name('portal.apply.form');
Route::post('/apply', [ApplicationController::class, 'submit'])->name('portal.apply.submit');
Route::get('/portal/library/search', [PortalController::class, 'librarySearch'])->name('portal.library.search');
Route::post('/portal/communication/send-message', [PortalController::class, 'sendMessage'])->name('portal.communication.send-message');
Route::post('/portal/communication/send-test-notification', [PortalController::class, 'sendTestNotification'])->name('portal.communication.send-test-notification');

// New LMS Route
Route::get('/portal/lms', [PortalController::class, 'lms'])->name('portal.lms');

// New Hostel Route
Route::get('/portal/hostel', [PortalController::class, 'hostel'])->name('portal.hostel');

// New Transport Route
Route::get('/portal/transport', [PortalController::class, 'transport'])->name('portal.transport');

// New Welfare Route
Route::get('/portal/welfare', [PortalController::class, 'welfare'])->name('portal.welfare');

// Profile Route
Route::get('/portal/profile', [PortalController::class, 'profile'])->name('portal.profile');
Route::patch('/portal/profile', [PortalController::class, 'updateProfile'])->name('portal.profile.update');
