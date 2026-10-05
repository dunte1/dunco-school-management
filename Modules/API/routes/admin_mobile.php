<?php

use Illuminate\Support\Facades\Route;
use Modules\API\Http\Controllers\Mobile\Admin\AdminStudentController;
use Modules\API\Http\Controllers\Mobile\Admin\AdminTeacherController;
use Modules\API\Http\Controllers\Mobile\Admin\AdminClassController;
use Modules\API\Http\Controllers\Mobile\Admin\AdminAttendanceController;
use Modules\API\Http\Controllers\Mobile\Admin\AdminExamController;
use Modules\API\Http\Controllers\Mobile\Admin\AdminFeesController;
use Modules\API\Http\Controllers\Mobile\Admin\AdminReportsController;
use Modules\API\Http\Controllers\Mobile\Admin\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Admin Mobile API Routes
|--------------------------------------------------------------------------
|
| Here are the routes for admin-specific mobile application API endpoints.
| All routes are prefixed with 'mobile/v1/admin' and require admin role.
|
*/

Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin|superadmin'])->group(function () {
    
    // Health check for admin APIs
    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'Admin API is working',
            'timestamp' => now()->toISOString(),
        ]);
    });
    
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'getDashboardData']);
    Route::get('/stats', [AdminDashboardController::class, 'getStats']);
    Route::get('/recent-activities', [AdminDashboardController::class, 'getRecentActivities']);
    
    // Student Management
    Route::prefix('students')->group(function () {
        Route::get('/', [AdminStudentController::class, 'index']);
        Route::get('/{id}', [AdminStudentController::class, 'show']);
        Route::post('/', [AdminStudentController::class, 'store']);
        Route::put('/{id}', [AdminStudentController::class, 'update']);
        Route::delete('/{id}', [AdminStudentController::class, 'destroy']);
        Route::get('/{id}/attendance', [AdminStudentController::class, 'getAttendance']);
        Route::get('/{id}/academic-record', [AdminStudentController::class, 'getAcademicRecord']);
        Route::post('/{id}/promote', [AdminStudentController::class, 'promote']);
        Route::post('/{id}/demote', [AdminStudentController::class, 'demote']);
        Route::post('/bulk-import', [AdminStudentController::class, 'bulkImport']);
        Route::get('/export', [AdminStudentController::class, 'export']);
    });
    
    // Teacher Management
    Route::prefix('teachers')->group(function () {
        Route::get('/', [AdminTeacherController::class, 'index']);
        Route::get('/{id}', [AdminTeacherController::class, 'show']);
        Route::post('/', [AdminTeacherController::class, 'store']);
        Route::put('/{id}', [AdminTeacherController::class, 'update']);
        Route::delete('/{id}', [AdminTeacherController::class, 'destroy']);
        Route::get('/{id}/subjects', [AdminTeacherController::class, 'getSubjects']);
        Route::post('/{id}/assign-subjects', [AdminTeacherController::class, 'assignSubjects']);
        Route::get('/{id}/schedule', [AdminTeacherController::class, 'getSchedule']);
        Route::post('/{id}/schedule', [AdminTeacherController::class, 'updateSchedule']);
        Route::get('/{id}/performance', [AdminTeacherController::class, 'getPerformance']);
    });
    
    // Class Management
    Route::prefix('classes')->group(function () {
        Route::get('/', [AdminClassController::class, 'index']);
        Route::get('/{id}', [AdminClassController::class, 'show']);
        Route::post('/', [AdminClassController::class, 'store']);
        Route::put('/{id}', [AdminClassController::class, 'update']);
        Route::delete('/{id}', [AdminClassController::class, 'destroy']);
        Route::get('/{id}/students', [AdminClassController::class, 'getStudents']);
        Route::post('/{id}/students', [AdminClassController::class, 'addStudents']);
        Route::delete('/{id}/students/{studentId}', [AdminClassController::class, 'removeStudent']);
        Route::get('/{id}/subjects', [AdminClassController::class, 'getSubjects']);
        Route::post('/{id}/subjects', [AdminClassController::class, 'assignSubjects']);
        Route::get('/{id}/timetable', [AdminClassController::class, 'getTimetable']);
        Route::post('/{id}/timetable', [AdminClassController::class, 'updateTimetable']);
    });
    
    // Attendance Management
    Route::prefix('attendance')->group(function () {
        Route::get('/', [AdminAttendanceController::class, 'index']);
        Route::get('/{id}', [AdminAttendanceController::class, 'show']);
        Route::post('/', [AdminAttendanceController::class, 'store']);
        Route::put('/{id}', [AdminAttendanceController::class, 'update']);
        Route::delete('/{id}', [AdminAttendanceController::class, 'destroy']);
        Route::post('/bulk-mark', [AdminAttendanceController::class, 'bulkMark']);
        Route::get('/reports', [AdminAttendanceController::class, 'getReports']);
        Route::get('/export', [AdminAttendanceController::class, 'export']);
        Route::get('/statistics', [AdminAttendanceController::class, 'getStatistics']);
    });
    
    // Exam Management
    Route::prefix('exams')->group(function () {
        Route::get('/', [AdminExamController::class, 'index']);
        Route::get('/{id}', [AdminExamController::class, 'show']);
        Route::post('/', [AdminExamController::class, 'store']);
        Route::put('/{id}', [AdminExamController::class, 'update']);
        Route::delete('/{id}', [AdminExamController::class, 'destroy']);
        Route::post('/{id}/publish', [AdminExamController::class, 'publish']);
        Route::post('/{id}/unpublish', [AdminExamController::class, 'unpublish']);
        Route::get('/{id}/results', [AdminExamController::class, 'getResults']);
        Route::post('/{id}/results', [AdminExamController::class, 'updateResults']);
        Route::get('/{id}/statistics', [AdminExamController::class, 'getStatistics']);
        Route::get('/export', [AdminExamController::class, 'export']);
    });
    
    // Fees Management
    Route::prefix('fees')->group(function () {
        Route::get('/', [AdminFeesController::class, 'index']);
        Route::get('/{id}', [AdminFeesController::class, 'show']);
        Route::post('/', [AdminFeesController::class, 'store']);
        Route::put('/{id}', [AdminFeesController::class, 'update']);
        Route::delete('/{id}', [AdminFeesController::class, 'destroy']);
        Route::get('/types', [AdminFeesController::class, 'getTypes']);
        Route::post('/types', [AdminFeesController::class, 'createType']);
        Route::get('/payments', [AdminFeesController::class, 'getPayments']);
        Route::post('/payments', [AdminFeesController::class, 'recordPayment']);
        Route::get('/reports', [AdminFeesController::class, 'getReports']);
        Route::get('/export', [AdminFeesController::class, 'export']);
        Route::get('/statistics', [AdminFeesController::class, 'getStatistics']);
    });
    
    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/academic', [AdminReportsController::class, 'getAcademicReports']);
        Route::get('/attendance', [AdminReportsController::class, 'getAttendanceReports']);
        Route::get('/financial', [AdminReportsController::class, 'getFinancialReports']);
        Route::get('/student-progress', [AdminReportsController::class, 'getStudentProgress']);
        Route::get('/teacher-performance', [AdminReportsController::class, 'getTeacherPerformance']);
        Route::get('/class-performance', [AdminReportsController::class, 'getClassPerformance']);
        Route::post('/generate', [AdminReportsController::class, 'generateReport']);
        Route::get('/export/{reportId}', [AdminReportsController::class, 'exportReport']);
    });
    
    // System Management
    Route::prefix('system')->group(function () {
        Route::get('/settings', [AdminDashboardController::class, 'getSettings']);
        Route::post('/settings', [AdminDashboardController::class, 'updateSettings']);
        Route::get('/users', [AdminDashboardController::class, 'getUsers']);
        Route::post('/users', [AdminDashboardController::class, 'createUser']);
        Route::put('/users/{id}', [AdminDashboardController::class, 'updateUser']);
        Route::delete('/users/{id}', [AdminDashboardController::class, 'deleteUser']);
        Route::get('/notifications', [AdminDashboardController::class, 'getNotifications']);
        Route::post('/notifications', [AdminDashboardController::class, 'sendNotification']);
    });
});
