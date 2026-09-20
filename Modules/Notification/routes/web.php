<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\NotificationController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('notifications', NotificationController::class)->names('notification');

    Route::get('notifications/manage', [NotificationController::class, 'manage'])->name('notification.manage');
    Route::post('notifications/{id}/send', [NotificationController::class, 'send'])->name('notification.send');
    Route::get('notifications/templates/list', [NotificationController::class, 'templates'])->name('notification.templates');
    Route::post('notifications/templates/store', [NotificationController::class, 'storeTemplate'])->name('notification.templates.store');
    Route::get('notifications/settings', [NotificationController::class, 'settings'])->name('notification.settings');
});
