<?php

use Illuminate\Support\Facades\Route;
use Modules\Examination\app\Http\Controllers\ExamController;
use Modules\Examination\app\Http\Controllers\OnlineExamController;
use Modules\Examination\app\Http\Controllers\QuestionController;
use Modules\Examination\app\Http\Controllers\QuestionCategoryController;
use Modules\Examination\app\Http\Controllers\ExamScheduleController;
use Modules\Examination\app\Http\Controllers\ProctoringController;
use Modules\Examination\app\Http\Controllers\ResultController;
use Modules\Examination\app\Http\Controllers\ExamTypeController;

/*
|--------------------------------------------------------------------------
| Examination Module Routes
|--------------------------------------------------------------------------
*/

Route::get('dashboard', function () {
    return view('examination::dashboard');
})->name('examination.dashboard');

Route::prefix('examination')->name('examination.')->middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/', [ExamController::class, 'index'])->name('dashboard');
    
    // Exam Management
    Route::prefix('exams')->name('exams.')->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('index');
        Route::get('/create', [ExamController::class, 'create'])->name('create');
        Route::post('/', [ExamController::class, 'store'])->name('store');
        Route::get('/{exam}', [ExamController::class, 'show'])->name('show');
        Route::get('/{exam}/edit', [ExamController::class, 'edit'])->name('edit');
        Route::put('/{exam}', [ExamController::class, 'update'])->name('update');
        Route::delete('/{exam}', [ExamController::class, 'destroy'])->name('destroy');
        
        // Exam Actions
        Route::post('/{exam}/publish', [ExamController::class, 'publish'])->name('publish');
        Route::post('/{exam}/start', [ExamController::class, 'start'])->name('start');
        Route::post('/{exam}/complete', [ExamController::class, 'complete'])->name('complete');
        
        // Question Management
        Route::post('/{exam}/questions', [ExamController::class, 'addQuestions'])->name('add-questions');
        Route::delete('/{exam}/questions/{question}', [ExamController::class, 'removeQuestion'])->name('remove-question');
        Route::post('/{exam}/generate-questions', [ExamController::class, 'generateRandomQuestions'])->name('generate-questions');
        
        // Results
        Route::get('/{exam}/results', [ExamController::class, 'results'])->name('results');
        Route::get('/{exam}/export-results', [ExamController::class, 'exportResults'])->name('export-results');
    });
    
    // Online Exam Routes
    Route::prefix('online')->name('online.')->group(function () {
        Route::get('/exam/{exam}', [OnlineExamController::class, 'startExam'])->name('start');
        Route::get('/exam-data/{attempt}', [OnlineExamController::class, 'getExamData'])->name('exam-data');
        Route::post('/start-attempt/{attempt}', [OnlineExamController::class, 'startAttempt'])->name('start-attempt');
        Route::post('/save-answer/{attempt}', [OnlineExamController::class, 'saveAnswer'])->name('save-answer');
        Route::post('/submit/{attempt}', [OnlineExamController::class, 'submitExam'])->name('submit');
        Route::post('/proctoring-log/{attempt}', [OnlineExamController::class, 'logProctoringEvent'])->name('proctoring-log');
        Route::get('/proctoring-status/{attempt}', [OnlineExamController::class, 'getProctoringStatus'])->name('proctoring-status');
        Route::get('/result/{attempt}', [OnlineExamController::class, 'examResult'])->name('result');
        Route::get('/download-answer-file/{answer}', [OnlineExamController::class, 'downloadAnswerFile'])->name('download.exam.answer.file');
    });
    
    // Question Bank Management
    Route::prefix('questions')->name('questions.')->group(function () {
        Route::get('/', [QuestionController::class, 'index'])->name('index');
        Route::get('/create', [QuestionController::class, 'create'])->name('create');
        Route::post('/', [QuestionController::class, 'store'])->name('store');
        Route::get('/{question}', [QuestionController::class, 'show'])->name('show');
        Route::get('/{question}/edit', [QuestionController::class, 'edit'])->name('edit');
        Route::put('/{question}', [QuestionController::class, 'update'])->name('update');
        Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('destroy');
        Route::post('/import', [QuestionController::class, 'import'])->name('import');
        Route::get('/export', [QuestionController::class, 'export'])->name('export');
    });
    
    // Question Categories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [QuestionCategoryController::class, 'index'])->name('index');
        Route::get('/create', [QuestionCategoryController::class, 'create'])->name('create');
        Route::post('/', [QuestionCategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [QuestionCategoryController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [QuestionCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [QuestionCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [QuestionCategoryController::class, 'destroy'])->name('destroy');
    });
    
    // Exam Schedules
    Route::prefix('schedules')->name('schedules.')->group(function () {
        Route::get('/', [ExamScheduleController::class, 'index'])->name('index');
        Route::get('/create', [ExamScheduleController::class, 'create'])->name('create');
        Route::post('/', [ExamScheduleController::class, 'store'])->name('store');
        Route::get('/{schedule}', [ExamScheduleController::class, 'show'])->name('show');
        Route::get('/{schedule}/edit', [ExamScheduleController::class, 'edit'])->name('edit');
        Route::put('/{schedule}', [ExamScheduleController::class, 'update'])->name('update');
        Route::delete('/{schedule}', [ExamScheduleController::class, 'destroy'])->name('destroy');
        Route::get('/timetable', [ExamScheduleController::class, 'timetable'])->name('timetable');
    });
    
    // Proctoring Management
    Route::prefix('proctoring')->name('proctoring.')->group(function () {
        Route::get('/', [ProctoringController::class, 'index'])->name('index');
        Route::get('/live/{exam}', [ProctoringController::class, 'liveMonitoring'])->name('live');
        Route::get('/logs/{attempt}', [ProctoringController::class, 'logs'])->name('logs');
        Route::post('/resolve/{log}', [ProctoringController::class, 'resolveLog'])->name('resolve');
        Route::get('/analytics', [ProctoringController::class, 'analytics'])->name('analytics');
        Route::get('/dashboard', [ProctoringController::class, 'dashboard'])->name('dashboard');
    });
    
    // Results Management
    Route::prefix('results')->name('results.')->group(function () {
        Route::get('/', [ResultController::class, 'index'])->name('index');
        Route::get('/{result}', [ResultController::class, 'show'])->name('show');
        Route::post('/publish/{exam}', [ResultController::class, 'publishResults'])->name('publish');
        Route::get('/transcript/{student}', [ResultController::class, 'transcript'])->name('transcript');
        Route::get('/rankings/{exam}', [ResultController::class, 'rankings'])->name('rankings');
        Route::get('/analytics', [ResultController::class, 'analytics'])->name('analytics');
    });
    
    // Student Routes (with student middleware)
    Route::prefix('student')->name('student.')->middleware(['role:student'])->group(function () {
        Route::get('/my-exams', [ExamController::class, 'studentExams'])->name('exams');
        Route::get('/my-results', [ResultController::class, 'studentResults'])->name('results');
        Route::get('/exam-history', [ExamController::class, 'examHistory'])->name('history');
    });
    
    // Teacher Routes (with teacher middleware)
    Route::prefix('teacher')->name('teacher.')->middleware(['role:teacher'])->group(function () {
        Route::get('/my-exams', [ExamController::class, 'teacherExams'])->name('exams');
        Route::get('/grade-exams', [ExamController::class, 'gradeExams'])->name('grade');
        Route::post('/grade-answer/{answer}', [ExamController::class, 'gradeAnswer'])->name('grade-answer');
        Route::get('/exam-analytics', [ExamController::class, 'examAnalytics'])->name('analytics');
    });
    
    // Admin Routes (with admin middleware)
    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [ExamController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/settings', [ExamController::class, 'settings'])->name('settings');
        Route::post('/settings', [ExamController::class, 'updateSettings'])->name('update-settings');
        Route::get('/reports', [ExamController::class, 'reports'])->name('reports');
        Route::get('/backup', [ExamController::class, 'backup'])->name('backup');
    });

    // Add this route for online exam creation
    Route::get('exams/online/create', [ExamController::class, 'createOnline'])->name('online-exams.create');
    Route::post('exam-types', [ExamTypeController::class, 'store'])->name('exam-types.store');
});

// API Routes for real-time features
Route::prefix('api/examination')->name('api.examination.')->middleware(['auth:sanctum'])->group(function () {
    Route::post('/proctoring/heartbeat', [OnlineExamController::class, 'heartbeat'])->name('proctoring.heartbeat');
    Route::post('/proctoring/screenshot', [OnlineExamController::class, 'screenshot'])->name('proctoring.screenshot');
    Route::post('/auto-save/{attempt}', [OnlineExamController::class, 'autoSave'])->name('auto-save');
    Route::get('/exam-status/{attempt}', [OnlineExamController::class, 'examStatus'])->name('exam-status');
});
