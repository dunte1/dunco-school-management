<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Examination\app\Http\Controllers\OnlineExamController;

/*
|--------------------------------------------------------------------------
| Examination API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('examination')->name('api.examination.')->middleware(['auth:sanctum'])->group(function () {
    
    // Real-time proctoring endpoints
    Route::prefix('proctoring')->name('proctoring.')->group(function () {
        Route::post('/heartbeat', [OnlineExamController::class, 'heartbeat'])->name('heartbeat');
        Route::post('/screenshot', [OnlineExamController::class, 'screenshot'])->name('screenshot');
        Route::post('/video-frame', [OnlineExamController::class, 'videoFrame'])->name('video-frame');
        Route::post('/audio-sample', [OnlineExamController::class, 'audioSample'])->name('audio-sample');
    });
    
    // Auto-save functionality
    Route::post('/auto-save/{attempt}', [OnlineExamController::class, 'autoSave'])->name('auto-save');
    
    // Exam status and monitoring
    Route::get('/exam-status/{attempt}', [OnlineExamController::class, 'examStatus'])->name('exam-status');
    Route::get('/exam-progress/{attempt}', [OnlineExamController::class, 'examProgress'])->name('exam-progress');
    
    // Real-time notifications
    Route::post('/notifications/subscribe', [OnlineExamController::class, 'subscribeNotifications'])->name('notifications.subscribe');
    Route::post('/notifications/unsubscribe', [OnlineExamController::class, 'unsubscribeNotifications'])->name('notifications.unsubscribe');
    
    // File upload for exam attachments
    Route::post('/upload-attachment', [OnlineExamController::class, 'uploadAttachment'])->name('upload-attachment');
    
    // Exam analytics (for teachers/admins)
    Route::prefix('analytics')->name('analytics.')->middleware(['role:teacher,admin'])->group(function () {
        Route::get('/exam/{exam}/live', [OnlineExamController::class, 'liveAnalytics'])->name('live');
        Route::get('/exam/{exam}/summary', [OnlineExamController::class, 'examSummary'])->name('summary');
        Route::get('/proctoring/{exam}/alerts', [OnlineExamController::class, 'proctoringAlerts'])->name('alerts');
    });
    
    // Student-specific endpoints
    Route::prefix('student')->name('student.')->middleware(['role:student'])->group(function () {
        Route::get('/my-exams', [OnlineExamController::class, 'studentExams'])->name('exams');
        Route::get('/exam-history', [OnlineExamController::class, 'examHistory'])->name('history');
        Route::get('/results', [OnlineExamController::class, 'studentResults'])->name('results');
    });
    
    // Teacher-specific endpoints
    Route::prefix('teacher')->name('teacher.')->middleware(['role:teacher'])->group(function () {
        Route::get('/grade-exams', [OnlineExamController::class, 'gradeExams'])->name('grade-exams');
        Route::post('/grade-answer/{answer}', [OnlineExamController::class, 'gradeAnswer'])->name('grade-answer');
        Route::get('/exam-analytics', [OnlineExamController::class, 'examAnalytics'])->name('analytics');
    });
});

// Public endpoints (no authentication required)
Route::prefix('examination/public')->name('api.examination.public.')->group(function () {
    Route::get('/exam-info/{exam}', [OnlineExamController::class, 'publicExamInfo'])->name('exam-info');
    Route::post('/contact-support', [OnlineExamController::class, 'contactSupport'])->name('contact-support');
});
