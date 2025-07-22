<?php

use Illuminate\Support\Facades\Route;
use Modules\Academic\Http\Controllers\AcademicController;
use Modules\Academic\Http\Controllers\ClassController;
use Modules\Academic\Http\Controllers\SubjectController;
use Modules\Academic\Http\Controllers\SubjectResourceController;
use Modules\Academic\Http\Controllers\SubjectCalendarEventController;
use Modules\Academic\Http\Controllers\AttendanceController;
use Modules\Academic\Http\Controllers\OnlineClassController;

Route::middleware(['auth'])->prefix('academic')->name('academic.')->group(function () {
    
    // Dashboard
    Route::get('/', [AcademicController::class, 'index'])->name('dashboard');
    
    // Classes Management
    Route::prefix('classes')->name('classes.')->group(function () {
        Route::get('/', [ClassController::class, 'index'])->name('index');
        Route::get('/create', [ClassController::class, 'create'])->name('create');
        Route::post('/', [ClassController::class, 'store'])->name('store');
        Route::get('/{id}', [ClassController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ClassController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ClassController::class, 'update'])->name('update');
        Route::delete('/{id}', [ClassController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status', [ClassController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{id}/enroll-student', [ClassController::class, 'enrollStudent'])->name('enroll-student');
        Route::delete('/{classId}/students/{studentId}', [ClassController::class, 'removeStudent'])->name('remove-student');
    });
    
    // Online Classes Management
    Route::prefix('online-classes')->name('online-classes.')->group(function () {
        Route::get('/', [OnlineClassController::class, 'index'])->name('index');
        Route::get('/create', [OnlineClassController::class, 'create'])->name('create');
        Route::post('/', [OnlineClassController::class, 'store'])->name('store');
        Route::get('/{onlineClass}', [OnlineClassController::class, 'show'])->name('show');
        Route::get('/{onlineClass}/edit', [OnlineClassController::class, 'edit'])->name('edit');
        Route::put('/{onlineClass}', [OnlineClassController::class, 'update'])->name('update');
        Route::delete('/{onlineClass}', [OnlineClassController::class, 'destroy'])->name('destroy');
        Route::post('/{onlineClass}/join', [OnlineClassController::class, 'join'])->name('join');
        Route::post('/{onlineClass}/start', [OnlineClassController::class, 'start'])->name('start');
        Route::post('/{onlineClass}/end', [OnlineClassController::class, 'end'])->name('end');
        Route::get('/upcoming/list', [OnlineClassController::class, 'upcoming'])->name('upcoming');
    });
    
    // Subjects Management
    Route::prefix('subjects')->name('subjects.')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('index');
        Route::get('/create', [SubjectController::class, 'create'])->name('create');
        Route::post('/', [SubjectController::class, 'store'])->name('store');
        Route::get('/{id}', [SubjectController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SubjectController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SubjectController::class, 'update'])->name('update');
        Route::delete('/{id}', [SubjectController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status', [SubjectController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/{id}/assign-data', [SubjectController::class, 'assignData'])->name('assign-data');
        Route::post('/{id}/assign', [SubjectController::class, 'assign'])->name('assign');
        Route::get('/{id}/performance', [SubjectController::class, 'performance'])->name('performance');
        Route::get('/{id}/prerequisites', [SubjectController::class, 'prerequisites'])->name('academic.subjects.prerequisites');
        Route::post('/{id}/prerequisites', [SubjectController::class, 'addPrerequisite'])->name('academic.subjects.addPrerequisite');
        Route::delete('/{id}/prerequisites/{prereqId}', [SubjectController::class, 'removePrerequisite'])->name('academic.subjects.removePrerequisite');
        Route::get('/{id}/groups', [SubjectController::class, 'groups'])->name('academic.subjects.groups');
        Route::post('/{id}/groups', [SubjectController::class, 'assignGroups'])->name('academic.subjects.assignGroups');
        Route::delete('/{id}/groups/{groupId}', [SubjectController::class, 'removeGroup'])->name('academic.subjects.removeGroup');
        Route::get('/{id}/audit-logs', [SubjectController::class, 'auditLogs'])->name('academic.subjects.auditLogs');
        Route::get('/{id}/approvals', [SubjectController::class, 'approvals'])->name('academic.subjects.approvals');
        Route::post('/{id}/approve', [SubjectController::class, 'approve'])->name('academic.subjects.approve');
        Route::post('/import', [SubjectController::class, 'importSubjects'])->name('academic.subjects.import');
        Route::get('/export', [SubjectController::class, 'exportSubjects'])->name('academic.subjects.export');
        Route::get('/analytics', [SubjectController::class, 'analytics'])->name('academic.subjects.analytics');
    });

    // Academic Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [\Modules\Academic\Http\Controllers\AcademicReportController::class, 'index'])->name('index');
        Route::get('/dashboard', [\Modules\Academic\app\Http\Controllers\ReportController::class, 'dashboard'])->name('dashboard');
    });

    // Grading System
    Route::prefix('grading')->name('grading.')->group(function () {
        Route::get('/', [\Modules\Academic\app\Http\Controllers\GradingController::class, 'index'])->name('index');
        Route::get('/create', [\Modules\Academic\app\Http\Controllers\GradingController::class, 'create'])->name('create');
        Route::post('/', [\Modules\Academic\app\Http\Controllers\GradingController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\Modules\Academic\app\Http\Controllers\GradingController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\Modules\Academic\app\Http\Controllers\GradingController::class, 'update'])->name('update');
        Route::delete('/{id}', [\Modules\Academic\app\Http\Controllers\GradingController::class, 'destroy'])->name('destroy');

        // Grades nested under grading scale
        Route::post('/{scaleId}/grades', [\Modules\Academic\app\Http\Controllers\GradingController::class, 'storeGrade'])->name('grade.store');
        Route::put('/{scaleId}/grades/{gradeId}', [\Modules\Academic\app\Http\Controllers\GradingController::class, 'updateGrade'])->name('grade.update');
        Route::delete('/{scaleId}/grades/{gradeId}', [\Modules\Academic\app\Http\Controllers\GradingController::class, 'destroyGrade'])->name('grade.destroy');
    });

    // Student Enrollment
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'index'])->name('index');
        Route::get('/create', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'create'])->name('create');
        Route::post('/', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'update'])->name('update');
        Route::delete('/{id}', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'destroy'])->name('destroy');
        Route::get('/bulk-import', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'showBulkImport'])->name('bulk_import');
        Route::post('/bulk-import', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'handleBulkImport'])->name('handle_bulk_import');
        Route::post('/{id}/add-parent', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'addParent'])->name('add_parent');
        Route::delete('/{id}/remove-parent/{parentId}', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'removeParent'])->name('remove_parent');
        Route::post('/{id}/upload-document', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'uploadDocument'])->name('upload_document');
        Route::delete('/{id}/delete-document/{docId}', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'deleteDocument'])->name('delete_document');
        Route::post('/{id}/verify-document/{docId}', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'verifyDocument'])->name('students.verify_document');
        Route::post('/{id}/record-payment/{feeId}', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'recordPayment'])->name('students.record_payment');
        Route::get('/{id}/id-card', [\Modules\Academic\app\Http\Controllers\StudentController::class, 'idCard'])->name('students.id_card');
    });

    // Subject Resources
    Route::prefix('subjects/{subjectId}/resources')->group(function () {
        Route::get('/', [SubjectResourceController::class, 'index'])->name('academic.subjects.resources.index');
        Route::post('/', [SubjectResourceController::class, 'store'])->name('academic.subjects.resources.store');
        Route::delete('/{resourceId}', [SubjectResourceController::class, 'destroy'])->name('academic.subjects.resources.destroy');
    });

    // Subject Calendar Events
    Route::prefix('subjects/{subjectId}/calendar-events')->group(function () {
        Route::get('/', [SubjectCalendarEventController::class, 'index']);
        Route::post('/', [SubjectCalendarEventController::class, 'store']);
        Route::delete('/{eventId}', [SubjectCalendarEventController::class, 'destroy']);
    });

    // Attendance Management
    Route::prefix('attendance')->name('academic.attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/take/{classId}', [AttendanceController::class, 'takeAttendance'])->name('take');
        Route::post('/save/{classId}', [AttendanceController::class, 'saveAttendance'])->name('save');
        Route::get('/report/{classId}', [AttendanceController::class, 'report'])->name('report');
        Route::get('/export/{classId}', [AttendanceController::class, 'export'])->name('export');
        Route::post('/mark', [AttendanceController::class, 'markAttendance'])->name('mark');
        Route::post('/mark-staff', [AttendanceController::class, 'markStaffAttendance'])->name('markStaff');
        Route::post('/excuse', [AttendanceController::class, 'submitExcuse'])->name('excuse.submit');
        Route::get('/excuses', [AttendanceController::class, 'adminExcuses'])->name('excuses');
        Route::post('/excuse/{id}/action', [AttendanceController::class, 'excuseAction'])->name('excuse.action');
        Route::get('/student/{studentId}/history', [AttendanceController::class, 'studentHistory'])->name('student-history');
        Route::post('/bulk-import', [AttendanceController::class, 'bulkImport'])->name('bulk-import');
        Route::get('/statistics', [AttendanceController::class, 'statistics'])->name('statistics');
    });

    // Examination Management
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('/', [\Modules\Academic\Http\Controllers\ExamController::class, 'index'])->name('index');
        Route::get('/create', [\Modules\Academic\Http\Controllers\ExamController::class, 'create'])->name('create');
        Route::post('/', [\Modules\Academic\Http\Controllers\ExamController::class, 'store'])->name('store');
        Route::get('/{id}', [\Modules\Academic\Http\Controllers\ExamController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\Modules\Academic\Http\Controllers\ExamController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\Modules\Academic\Http\Controllers\ExamController::class, 'update'])->name('update');
        Route::delete('/{id}', [\Modules\Academic\Http\Controllers\ExamController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/publish', [\Modules\Academic\Http\Controllers\ExamController::class, 'publish'])->name('publish');
        Route::post('/{id}/archive', [\Modules\Academic\Http\Controllers\ExamController::class, 'archive'])->name('archive');
    });
});
