<?php

use Illuminate\Support\Facades\Route;
use Modules\Communication\Http\Controllers\CommunicationController;
use Modules\Communication\Http\Controllers\MessageController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('communications', CommunicationController::class)->names('communication');
});

Route::middleware(['web', 'auth'])->prefix('communication')->group(function () {
    Route::get('/', [\Modules\Communication\Http\Controllers\CommunicationController::class, 'index'])->name('communication.dashboard');
    Route::get('inbox', [MessageController::class, 'inbox'])->name('communication.inbox');
    Route::post('inbox/mark-as-read/{id}', [MessageController::class, 'markAsRead'])->name('communication.markAsRead');
    Route::post('inbox/mark-as-unread/{id}', [MessageController::class, 'markAsUnread'])->name('communication.markAsUnread');
    Route::post('inbox/star/{id}', [MessageController::class, 'toggleStar'])->name('communication.toggleStar');
    Route::post('inbox/delete/{id}', [MessageController::class, 'delete'])->name('communication.delete');
    Route::post('inbox/bulk-action', [MessageController::class, 'bulkAction'])->name('communication.bulkAction');
    Route::get('outbox', [MessageController::class, 'outbox'])->name('communication.outbox');
    Route::get('compose', [MessageController::class, 'compose'])->name('communication.compose');
    Route::post('send', [MessageController::class, 'send'])->name('communication.send');
    Route::get('message/{id}', [MessageController::class, 'show'])->name('communication.message.show');
    Route::get('message/{id}/reply', [MessageController::class, 'reply'])->name('communication.message.reply');
    Route::post('message/{id}/reply', [MessageController::class, 'sendReply'])->name('communication.message.sendReply');
    Route::get('groups', [MessageController::class, 'groups'])->name('communication.groups');
    Route::get('groups/create', [MessageController::class, 'createGroup'])->name('communication.groups.create');
    Route::post('groups', [MessageController::class, 'storeGroup'])->name('communication.groups.store');
    Route::get('groups/{id}', [MessageController::class, 'showGroup'])->name('communication.groups.show');
    Route::post('groups/{id}/message', [MessageController::class, 'sendGroupMessage'])->name('communication.groups.message');
    Route::post('groups/{id}/members', [MessageController::class, 'addGroupMember'])->name('communication.groups.members.add');
    Route::delete('groups/{id}/members/{userId}', [MessageController::class, 'removeGroupMember'])->name('communication.groups.members.remove');
    Route::get('broadcasts', [MessageController::class, 'broadcasts'])->name('communication.broadcasts');
    Route::get('broadcasts/create', [MessageController::class, 'createBroadcast'])->name('communication.broadcasts.create');
    Route::post('broadcasts', [MessageController::class, 'storeBroadcast'])->name('communication.broadcasts.store');
    Route::get('broadcasts/{id}', [MessageController::class, 'showBroadcast'])->name('communication.broadcasts.show');
    Route::post('broadcasts/{id}/send', [MessageController::class, 'sendBroadcast'])->name('communication.broadcasts.send');
    Route::get('announcements', [MessageController::class, 'announcements'])->name('communication.announcements');
    Route::get('announcements/{id}', [MessageController::class, 'showAnnouncement'])->name('communication.announcements.show');
    Route::post('announcements/{id}/mark-read', [MessageController::class, 'markAnnouncementRead'])->name('communication.announcements.markRead');
    Route::get('notifications', [MessageController::class, 'notifications'])->name('communication.notifications');
    Route::get('notifications/settings', [MessageController::class, 'notificationSettings'])->name('communication.notifications.settings');
    Route::post('notifications/settings', [MessageController::class, 'updateNotificationSettings'])->name('communication.notifications.settings.update');
    Route::post('notifications/{id}/mark-read', [MessageController::class, 'markNotificationRead'])->name('communication.notifications.markRead');
    Route::post('notifications/mark-all-read', [MessageController::class, 'markAllNotificationsRead'])->name('communication.notifications.markAllRead');
    Route::get('notifications/count', [MessageController::class, 'getNotificationCount'])->name('communication.notifications.count');
});
