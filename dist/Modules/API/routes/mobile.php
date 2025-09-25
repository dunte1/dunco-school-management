<?php

use Illuminate\Support\Facades\Route;
use Modules\API\Http\Controllers\Mobile\AuthController;
use Modules\API\Http\Controllers\Mobile\AcademicController;
use Modules\API\Http\Controllers\Mobile\FinanceController;
use Modules\API\Http\Controllers\Mobile\NotificationController;
use Modules\API\Http\Controllers\Mobile\LibraryController;
use Modules\API\Http\Controllers\Mobile\HostelController;
use Modules\API\Http\Controllers\Mobile\TransportController;
use Modules\API\Http\Controllers\Mobile\DocumentController;

/*
|--------------------------------------------------------------------------
| Mobile API Routes
|--------------------------------------------------------------------------
|
| Here are the routes for the mobile application API endpoints.
| All routes are prefixed with 'mobile/v1' and use Sanctum authentication.
|
*/

Route::prefix('mobile/v1')->group(function () {
    
    // Public routes (no authentication required)
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);
    
    // Protected routes (authentication required)
    Route::middleware(['auth:sanctum'])->group(function () {
        
        // Authentication
        Route::get('auth/profile', [AuthController::class, 'getProfile']);
        Route::put('auth/profile', [AuthController::class, 'updateProfile']);
        Route::post('auth/change-password', [AuthController::class, 'changePassword']);
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::post('auth/refresh', [AuthController::class, 'refreshToken']);
        
        // Academic Management
        Route::prefix('academic')->group(function () {
            // Classes and Sections
            Route::get('classes', [AcademicController::class, 'getClasses']);
            Route::get('classes/{id}', [AcademicController::class, 'getClass']);
            Route::get('sections', [AcademicController::class, 'getSections']);
            
            // Subjects
            Route::get('subjects', [AcademicController::class, 'getSubjects']);
            Route::get('subjects/{id}', [AcademicController::class, 'getSubject']);
            
            // Grades
            Route::get('grades', [AcademicController::class, 'getGrades']);
            Route::get('grades/{id}', [AcademicController::class, 'getGrade']);
            Route::post('grades', [AcademicController::class, 'createGrade']);
            Route::put('grades/{id}', [AcademicController::class, 'updateGrade']);
            Route::delete('grades/{id}', [AcademicController::class, 'deleteGrade']);
            
            // Attendance
            Route::get('attendance', [AcademicController::class, 'getAttendance']);
            Route::get('attendance/{id}', [AcademicController::class, 'getAttendanceRecord']);
            Route::post('attendance', [AcademicController::class, 'createAttendance']);
            Route::put('attendance/{id}', [AcademicController::class, 'updateAttendance']);
            Route::delete('attendance/{id}', [AcademicController::class, 'deleteAttendance']);
            
            // Assignments
            Route::get('assignments', [AcademicController::class, 'getAssignments']);
            Route::get('assignments/{id}', [AcademicController::class, 'getAssignment']);
            Route::post('assignments', [AcademicController::class, 'createAssignment']);
            Route::put('assignments/{id}', [AcademicController::class, 'updateAssignment']);
            Route::delete('assignments/{id}', [AcademicController::class, 'deleteAssignment']);
            
            // Assignment Submissions
            Route::get('assignments/{id}/submissions', [AcademicController::class, 'getAssignmentSubmissions']);
            Route::post('assignments/{id}/submit', [AcademicController::class, 'submitAssignment']);
            Route::put('submissions/{id}/grade', [AcademicController::class, 'gradeSubmission']);
            
            // Timetable
            Route::get('timetable', [AcademicController::class, 'getTimetable']);
            Route::get('timetable/{id}', [AcademicController::class, 'getTimetableById']);
        });
        
        // Finance Management
        Route::prefix('finance')->group(function () {
            // Fees
            Route::get('fees', [FinanceController::class, 'getFees']);
            Route::get('fees/{id}', [FinanceController::class, 'getFee']);
            Route::get('fee-structures', [FinanceController::class, 'getFeeStructures']);
            Route::get('fee-structures/{id}', [FinanceController::class, 'getFeeStructure']);
            
            // Student Fees
            Route::get('student-fees', [FinanceController::class, 'getStudentFees']);
            Route::get('student-fees/{id}', [FinanceController::class, 'getStudentFee']);
            
            // Payments
            Route::get('payments', [FinanceController::class, 'getPayments']);
            Route::get('payments/{id}', [FinanceController::class, 'getPayment']);
            Route::post('payments', [FinanceController::class, 'createPayment']);
            Route::put('payments/{id}', [FinanceController::class, 'updatePayment']);
            Route::delete('payments/{id}', [FinanceController::class, 'deletePayment']);
            
            // M-Pesa Integration
            Route::post('mpesa/stk-push', [FinanceController::class, 'initiateMpesaPayment']);
            Route::get('mpesa/status/{checkoutRequestId}', [FinanceController::class, 'checkMpesaStatus']);
            Route::post('mpesa/callback', [FinanceController::class, 'mpesaCallback']);
            
            // Financial Reports
            Route::get('reports/summary', [FinanceController::class, 'getFinancialSummary']);
            Route::get('reports/monthly', [FinanceController::class, 'getMonthlyReport']);
            Route::get('reports/student/{studentId}', [FinanceController::class, 'getStudentFinancialReport']);
        });
        
        // Notifications
        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'getNotifications']);
            Route::get('/{id}', [NotificationController::class, 'getNotification']);
            Route::put('/{id}/read', [NotificationController::class, 'markAsRead']);
            Route::put('/read-all', [NotificationController::class, 'markAllAsRead']);
            Route::delete('/{id}', [NotificationController::class, 'deleteNotification']);
        });
        
        // Library Management
        Route::prefix('library')->group(function () {
            Route::get('books', [LibraryController::class, 'getBooks']);
            Route::get('books/{id}', [LibraryController::class, 'getBook']);
            Route::get('borrowed-books', [LibraryController::class, 'getBorrowedBooks']);
            Route::post('books/{id}/borrow', [LibraryController::class, 'borrowBook']);
            Route::post('books/{id}/return', [LibraryController::class, 'returnBook']);
        });
        
        // Hostel Management
        Route::prefix('hostel')->group(function () {
            Route::get('rooms', [HostelController::class, 'getRooms']);
            Route::get('rooms/{id}', [HostelController::class, 'getRoom']);
            Route::get('allocations', [HostelController::class, 'getRoomAllocations']);
            Route::get('allocations/{id}', [HostelController::class, 'getRoomAllocation']);
        });
        
        // Transport Management
        Route::prefix('transport')->group(function () {
            Route::get('vehicles', [TransportController::class, 'getVehicles']);
            Route::get('vehicles/{id}', [TransportController::class, 'getVehicle']);
            Route::get('routes', [TransportController::class, 'getRoutes']);
            Route::get('routes/{id}', [TransportController::class, 'getRoute']);
            Route::get('schedules', [TransportController::class, 'getSchedules']);
        });
        
        // Document Management
        Route::prefix('documents')->group(function () {
            Route::get('/', [DocumentController::class, 'getDocuments']);
            Route::get('/{id}', [DocumentController::class, 'getDocument']);
            Route::post('/', [DocumentController::class, 'uploadDocument']);
            Route::delete('/{id}', [DocumentController::class, 'deleteDocument']);
        });
        
        // Dashboard Data
        Route::get('dashboard', [AuthController::class, 'getDashboardData']);
        
        // Real-time Updates (WebSocket)
        Route::get('ws/connect', [NotificationController::class, 'connectWebSocket']);
    });
}); 