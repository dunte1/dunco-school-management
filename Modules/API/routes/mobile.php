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
| All mobile API routes require authentication via Sanctum tokens.
| Consistent JSON response format: { success: bool, message: string, data: mixed }
|
*/

Route::prefix('v1/mobile')->group(function () {

    // ─── Auth (public) ────────────────────────────────────────────────
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('refresh-token', [AuthController::class, 'refreshToken']);

    // ─── Authenticated routes ─────────────────────────────────────────
    Route::middleware(['auth:sanctum'])->group(function () {

        // Auth / Profile
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'getProfile']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::put('change-password', [AuthController::class, 'changePassword']);
        Route::get('dashboard', [AuthController::class, 'getDashboardData']);

        // Academic
        Route::prefix('academic')->group(function () {
            Route::get('classes', [AcademicController::class, 'getClasses']);
            Route::get('classes/{id}', [AcademicController::class, 'getClass']);
            Route::get('sections', [AcademicController::class, 'getSections']);
            Route::get('subjects', [AcademicController::class, 'getSubjects']);
            Route::get('subjects/{id}', [AcademicController::class, 'getSubject']);
            Route::get('students', [AcademicController::class, 'getStudents']);
            Route::get('students/{id}', [AcademicController::class, 'getStudent']);
            Route::get('attendance', [AcademicController::class, 'getAttendance']);
            Route::get('attendance/{studentId}', [AcademicController::class, 'getAttendance']);
            Route::get('timetable', [AcademicController::class, 'getTimetable']);
            Route::get('timetable/{id}', [AcademicController::class, 'getTimetableById']);
            Route::get('exams', [AcademicController::class, 'getExams']);
            Route::get('exam-results', [AcademicController::class, 'getExamResults']);
            Route::get('grades', [AcademicController::class, 'getGrades']);
            Route::get('grades/{id}', [AcademicController::class, 'getGrade']);
            Route::post('grades', [AcademicController::class, 'createGrade']);
            Route::put('grades/{id}', [AcademicController::class, 'updateGrade']);
            Route::delete('grades/{id}', [AcademicController::class, 'deleteGrade']);
            Route::get('academic-records', [AcademicController::class, 'getAcademicRecords']);
            Route::get('student-count', [AcademicController::class, 'getStudentCount']);
        });

        // Finance
        Route::prefix('finance')->group(function () {
            Route::get('fees', [FinanceController::class, 'getFees']);
            Route::get('fees/{id}', [FinanceController::class, 'getFee']);
            Route::get('fee-categories', [FinanceController::class, 'getFeeCategories']);
            Route::get('fee-structures', [FinanceController::class, 'getFeeStructures']);
            Route::get('fee-structures/{id}', [FinanceController::class, 'getFeeStructure']);
            Route::get('student-fees', [FinanceController::class, 'getStudentFees']);
            Route::get('student-fees/{id}', [FinanceController::class, 'getStudentFee']);
            Route::get('payments', [FinanceController::class, 'getPayments']);
            Route::get('payments/{id}', [FinanceController::class, 'getPayment']);
            Route::post('payments', [FinanceController::class, 'createPayment']);
            Route::put('payments/{id}', [FinanceController::class, 'updatePayment']);
            Route::delete('payments/{id}', [FinanceController::class, 'deletePayment']);
            Route::get('invoices', [FinanceController::class, 'getInvoices']);
            Route::get('invoices/{id}', [FinanceController::class, 'getInvoice']);
            Route::get('payment-summary', [FinanceController::class, 'getPaymentSummary']);
            Route::get('reports/summary', [FinanceController::class, 'getReportsSummary']);
            Route::get('reports/monthly', [FinanceController::class, 'getMonthlyReport']);
            Route::get('reports/student/{studentId}', [FinanceController::class, 'getStudentReport']);
        });

        // Notifications
        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'getNotifications']);
            Route::get('unread-count', [NotificationController::class, 'getUnreadCount']);
            Route::put('read-all', [NotificationController::class, 'markAllAsRead']);
            Route::put('{id}/read', [NotificationController::class, 'markAsRead']);
            Route::delete('{id}', [NotificationController::class, 'deleteNotification']);
            Route::delete('read/all', [NotificationController::class, 'deleteAllRead']);
            Route::put('device-token', [NotificationController::class, 'updateDeviceToken']);
        });

        // Library
        Route::prefix('library')->group(function () {
            Route::get('books', [LibraryController::class, 'getBooks']);
            Route::get('books/search', [LibraryController::class, 'searchBooks']);
            Route::get('books/{id}', [LibraryController::class, 'getBook']);
            Route::get('borrowings', [LibraryController::class, 'getBorrowings']);
            Route::get('categories', [LibraryController::class, 'getCategories']);
            Route::get('authors', [LibraryController::class, 'getAuthors']);
        });

        // Hostel
        Route::prefix('hostel')->group(function () {
            Route::get('hostels', [HostelController::class, 'getHostels']);
            Route::get('hostels/{id}', [HostelController::class, 'getHostel']);
            Route::get('rooms', [HostelController::class, 'getRooms']);
            Route::get('rooms/{id}', [HostelController::class, 'getRoom']);
            Route::get('my-allocation', [HostelController::class, 'getMyAllocation']);
            Route::get('announcements', [HostelController::class, 'getAnnouncements']);
            Route::get('issues', [HostelController::class, 'getIssues']);
            Route::post('issues', [HostelController::class, 'reportIssue']);
            Route::get('fees', [HostelController::class, 'getHostelFees']);
        });

        // Transport
        Route::prefix('transport')->group(function () {
            Route::get('routes', [TransportController::class, 'getRoutes']);
            Route::get('routes/{id}', [TransportController::class, 'getRoute']);
            Route::get('routes/{routeId}/stops', [TransportController::class, 'getRouteStops']);
            Route::get('vehicles', [TransportController::class, 'getVehicles']);
            Route::get('vehicles/{id}', [TransportController::class, 'getVehicle']);
            Route::get('drivers', [TransportController::class, 'getDrivers']);
            Route::get('trips', [TransportController::class, 'getTrips']);
            Route::get('trips/{id}', [TransportController::class, 'getTrip']);
            Route::get('my-route', [TransportController::class, 'getMyRoute']);
        });

        // Documents
        Route::prefix('documents')->group(function () {
            Route::get('/', [DocumentController::class, 'getDocuments']);
            Route::get('/types', [DocumentController::class, 'getDocumentTypes']);
            Route::get('/{id}', [DocumentController::class, 'getDocument']);
            Route::post('/', [DocumentController::class, 'uploadDocument']);
            Route::get('/{id}/download', [DocumentController::class, 'downloadDocument']);
            Route::delete('/{id}', [DocumentController::class, 'deleteDocument']);
        });

        // Global Search
        Route::get('search', [\App\Http\Controllers\Api\GlobalSearchController::class, 'search']);
    });
});
