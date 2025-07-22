<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/attendance/analytics/student', [\Modules\Academic\Http\Controllers\AttendanceController::class, 'getStudentAnalytics']);
    Route::get('/attendance/analytics/staff', [\Modules\HR\Http\Controllers\AttendanceController::class, 'getStaffAnalytics']);
    Route::apiResource('attendance/session-templates', \App\Http\Controllers\Modules\Attendance\Http\Controllers\SessionTemplateController::class);
    Route::get('/attendance/past-records', [\Modules\Academic\Http\Controllers\AttendanceController::class, 'getPastRecords']);
    Route::put('/attendance/past-records/{id}', [\Modules\Academic\Http\Controllers\AttendanceController::class, 'updatePastRecord']);
    Route::get('/attendance/past-records/{id}/audit-log', [\Modules\Academic\Http\Controllers\AttendanceController::class, 'getAuditLog']);
    Route::post('/attendance/notifications/bulk', [\Modules\Academic\Http\Controllers\AttendanceController::class, 'sendBulkNotifications']);
    Route::post('/attendance/notifications/x-days-absent', [\Modules\Academic\Http\Controllers\AttendanceController::class, 'sendXDaysAbsentAlerts']);
    Route::post('/attendance/biometric', [\Modules\Attendance\Http\Controllers\AdvancedAttendanceController::class, 'biometricEvent']);
    Route::post('/attendance/qr', [\Modules\Attendance\Http\Controllers\AdvancedAttendanceController::class, 'qrEvent']);
    Route::post('/attendance/face', [\Modules\Attendance\Http\Controllers\AdvancedAttendanceController::class, 'faceEvent']);
    Route::post('/attendance/acknowledge', [\Modules\Attendance\Http\Controllers\AdvancedAttendanceController::class, 'parentAcknowledge']);
    Route::get('/attendance/biometric-logs', [\Modules\Attendance\Http\Controllers\AdvancedAttendanceController::class, 'getBiometricLogs']);
    Route::get('/attendance/qr-logs', [\Modules\Attendance\Http\Controllers\AdvancedAttendanceController::class, 'getQrLogs']);
    Route::get('/attendance/face-logs', [\Modules\Attendance\Http\Controllers\AdvancedAttendanceController::class, 'getFaceLogs']);
    Route::get('/attendance/acknowledgment-logs', [\Modules\Attendance\Http\Controllers\AdvancedAttendanceController::class, 'getAcknowledgmentLogs']);
}); 