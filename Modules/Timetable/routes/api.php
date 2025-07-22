<?php

use Illuminate\Support\Facades\Route;
use Modules\Timetable\Http\Controllers\TimetableController;
use Modules\Timetable\Http\Controllers\TeacherAvailabilityController;
use Modules\Timetable\Http\Controllers\RoomController;
use Modules\Timetable\Http\Controllers\RoomAllocationController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('timetables', TimetableController::class)->names('timetable');
    Route::apiResource('teacher-availabilities', TeacherAvailabilityController::class)->names('teacher_availabilities');
    Route::apiResource('rooms', RoomController::class)->names('rooms');
    Route::apiResource('room-allocations', RoomAllocationController::class)->names('room_allocations');
    Route::get('teacher-availabilities/export/csv', [TeacherAvailabilityController::class, 'exportCsv'])->name('teacher_availabilities.export.csv');
    Route::get('teacher-availabilities/export/pdf', [TeacherAvailabilityController::class, 'exportPdf'])->name('teacher_availabilities.export.pdf');
    Route::get('rooms/export/csv', [RoomController::class, 'exportCsv'])->name('rooms.export.csv');
    Route::get('rooms/export/pdf', [RoomController::class, 'exportPdf'])->name('rooms.export.pdf');
    Route::get('room-allocations/export/csv', [RoomAllocationController::class, 'exportCsv'])->name('room_allocations.export.csv');
    Route::get('room-allocations/export/pdf', [RoomAllocationController::class, 'exportPdf'])->name('room_allocations.export.pdf');
    // Notifications API
    Route::get('notifications', [\Modules\Timetable\Http\Controllers\Api\NotificationController::class, 'index'])->name('notifications.api.index');
    Route::post('notifications/{id}/read', [\Modules\Timetable\Http\Controllers\Api\NotificationController::class, 'markAsRead'])->name('notifications.api.read');
    Route::post('notifications/read-all', [\Modules\Timetable\Http\Controllers\Api\NotificationController::class, 'markAllAsRead'])->name('notifications.api.readAll');
    // My Schedule API
    Route::get('my-schedule', [\Modules\Timetable\Http\Controllers\Api\MyScheduleController::class, 'index'])->name('my_schedule.api.index');
    // My Calendar API
    Route::get('my-calendar', [\Modules\Timetable\Http\Controllers\Api\MyScheduleController::class, 'calendar'])->name('my_calendar.api.index');
    // Dashboard Summary API
    Route::get('dashboard-summary', [\Modules\Timetable\Http\Controllers\Api\DashboardController::class, 'dashboard'])->name('dashboard.api.summary');
    // Schedule Snapshots (Undo/Rollback) API
    Route::get('schedule-snapshots', [\Modules\Timetable\Http\Controllers\Api\ScheduleSnapshotController::class, 'index'])->name('schedule_snapshots.api.index');
    Route::get('schedule-snapshots/{id}', [\Modules\Timetable\Http\Controllers\Api\ScheduleSnapshotController::class, 'show'])->name('schedule_snapshots.api.show');
    Route::post('schedule-snapshots/{id}/restore', [\Modules\Timetable\Http\Controllers\Api\ScheduleSnapshotController::class, 'restore'])->name('schedule_snapshots.api.restore');
    // Notification Preferences API
    Route::get('notification-preferences', [\Modules\Timetable\Http\Controllers\Api\NotificationPreferenceController::class, 'show'])->name('notification_preferences.api.show');
    Route::post('notification-preferences', [\Modules\Timetable\Http\Controllers\Api\NotificationPreferenceController::class, 'update'])->name('notification_preferences.api.update');
});
