<?php

use Illuminate\Support\Facades\Route;
use Modules\Nursing\Http\Controllers\NursingDashboardController;
use Modules\Nursing\Http\Controllers\FacilityController;
use Modules\Nursing\Http\Controllers\PlacementController;
use Modules\Nursing\Http\Controllers\LogbookController;
use Modules\Nursing\Http\Controllers\SkillController;
use Modules\Nursing\Http\Controllers\SkillAssessmentController;
use Modules\Nursing\Http\Controllers\ClinicalHoursController;
use Modules\Nursing\Http\Controllers\NursingAttendanceController;
use Modules\Nursing\Http\Controllers\InstructorController;
use Modules\Nursing\Http\Controllers\ReferenceController;
use Modules\Nursing\Http\Controllers\ScenarioController;
use Modules\Nursing\Http\Controllers\CpdController;
use Modules\Nursing\Http\Controllers\CalculatorController;
use Modules\Nursing\Http\Controllers\NursingReportController;
use Modules\Nursing\Http\Controllers\NursingSettingController;
use Modules\Nursing\Http\Controllers\StudyCenterController;
use Modules\Nursing\Http\Controllers\StudyAssistantController;

Route::middleware(['auth'])->prefix('nursing')->name('nursing.')->group(function () {

    // Dashboard
    Route::get('/', [NursingDashboardController::class, 'index'])->name('dashboard');

    // Facilities
    Route::prefix('facilities')->name('facilities.')->middleware('permission:nursing.placements.manage')->group(function () {
        Route::get('/', [FacilityController::class, 'index'])->name('index');
        Route::get('/create', [FacilityController::class, 'create'])->name('create');
        Route::post('/', [FacilityController::class, 'store'])->name('store');
        Route::get('/{facility}', [FacilityController::class, 'show'])->name('show');
        Route::get('/{facility}/edit', [FacilityController::class, 'edit'])->name('edit');
        Route::put('/{facility}', [FacilityController::class, 'update'])->name('update');
        Route::delete('/{facility}', [FacilityController::class, 'destroy'])->name('destroy');
    });

    // Placements
    Route::prefix('placements')->name('placements.')->group(function () {
        Route::get('/', [PlacementController::class, 'index'])->name('index')->middleware('permission:nursing.placements.view');
        Route::get('/create', [PlacementController::class, 'create'])->name('create')->middleware('permission:nursing.placements.create');
        Route::post('/', [PlacementController::class, 'store'])->name('store')->middleware('permission:nursing.placements.create');
        Route::get('/{placement}', [PlacementController::class, 'show'])->name('show')->middleware('permission:nursing.placements.view');
        Route::get('/{placement}/edit', [PlacementController::class, 'edit'])->name('edit')->middleware('permission:nursing.placements.edit');
        Route::put('/{placement}', [PlacementController::class, 'update'])->name('update')->middleware('permission:nursing.placements.edit');
        Route::delete('/{placement}', [PlacementController::class, 'destroy'])->name('destroy')->middleware('permission:nursing.placements.delete');
    });

    // Logbook
    Route::prefix('logbook')->name('logbook.')->group(function () {
        Route::get('/', [LogbookController::class, 'index'])->name('index');
        Route::get('/create', [LogbookController::class, 'create'])->name('create');
        Route::post('/', [LogbookController::class, 'store'])->name('store');
        Route::get('/{logbook}', [LogbookController::class, 'show'])->name('show');
        Route::get('/{logbook}/edit', [LogbookController::class, 'edit'])->name('edit');
        Route::put('/{logbook}', [LogbookController::class, 'update'])->name('update');
        Route::delete('/{logbook}', [LogbookController::class, 'destroy'])->name('destroy');
        Route::post('/{logbook}/submit', [LogbookController::class, 'submit'])->name('submit');
        Route::post('/{logbook}/approve', [LogbookController::class, 'approve'])->name('approve')->middleware('permission:nursing.logbooks.review');
        Route::post('/{logbook}/return', [LogbookController::class, 'returnEntry'])->name('return')->middleware('permission:nursing.logbooks.review');
        Route::post('/{logbook}/reject', [LogbookController::class, 'reject'])->name('reject')->middleware('permission:nursing.logbooks.review');
        Route::get('/pending-reviews', [LogbookController::class, 'pendingReviews'])->name('pending-reviews')->middleware('permission:nursing.logbooks.review');
    });

    // Skills
    Route::prefix('skills')->name('skills.')->group(function () {
        Route::get('/', [SkillController::class, 'index'])->name('index');
        Route::get('/my-progress', [SkillController::class, 'myProgress'])->name('my-progress');
        Route::get('/{skill}', [SkillController::class, 'show'])->name('show');
        Route::post('/{skill}/update-status', [SkillController::class, 'updateStatus'])->name('update-status')->middleware('permission:nursing.skills.manage');
    });

    // Skill Assessments
    Route::prefix('skill-assessments')->name('skill-assessments.')->group(function () {
        Route::get('/', [SkillAssessmentController::class, 'index'])->name('index')->middleware('permission:nursing.skills.assess');
        Route::get('/create', [SkillAssessmentController::class, 'create'])->name('create')->middleware('permission:nursing.skills.assess');
        Route::post('/', [SkillAssessmentController::class, 'store'])->name('store')->middleware('permission:nursing.skills.assess');
        Route::get('/{assessment}', [SkillAssessmentController::class, 'show'])->name('show');
    });

    // Clinical Hours
    Route::prefix('hours')->name('hours.')->group(function () {
        Route::get('/', [ClinicalHoursController::class, 'index'])->name('index');
        Route::post('/', [ClinicalHoursController::class, 'store'])->name('store')->middleware('permission:nursing.hours.manage');
        Route::post('/{hours}/approve', [ClinicalHoursController::class, 'approve'])->name('approve')->middleware('permission:nursing.hours.approve');
    });

    // Clinical Attendance
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [NursingAttendanceController::class, 'index'])->name('index');
        Route::post('/mark', [NursingAttendanceController::class, 'mark'])->name('mark')->middleware('permission:nursing.attendance.mark');
    });

    // Instructor Portal
    Route::prefix('instructor')->name('instructor.')->middleware('role:nursing_instructor,clinical_instructor,admin,super_admin')->group(function () {
        Route::get('/', [InstructorController::class, 'dashboard'])->name('dashboard');
        Route::get('/students', [InstructorController::class, 'students'])->name('students');
        Route::get('/students/{student}', [InstructorController::class, 'studentDetail'])->name('student-detail');
    });

    // Reference Center
    Route::prefix('reference')->name('reference.')->group(function () {
        Route::get('/', [ReferenceController::class, 'index'])->name('index');
        Route::get('/{article}', [ReferenceController::class, 'show'])->name('show');
        Route::get('/create', [ReferenceController::class, 'create'])->name('create')->middleware('permission:nursing.reference.create');
        Route::post('/', [ReferenceController::class, 'store'])->name('store')->middleware('permission:nursing.reference.create');
        Route::get('/{article}/edit', [ReferenceController::class, 'edit'])->name('edit')->middleware('permission:nursing.reference.create');
        Route::put('/{article}', [ReferenceController::class, 'update'])->name('update')->middleware('permission:nursing.reference.create');
        Route::post('/{article}/approve', [ReferenceController::class, 'approve'])->name('approve')->middleware('permission:nursing.reference.approve');
    });

    // Clinical Scenarios
    Route::prefix('scenarios')->name('scenarios.')->group(function () {
        Route::get('/', [ScenarioController::class, 'index'])->name('index');
        Route::get('/create', [ScenarioController::class, 'create'])->name('create')->middleware('permission:nursing.scenarios.create');
        Route::post('/', [ScenarioController::class, 'store'])->name('store')->middleware('permission:nursing.scenarios.create');
        Route::get('/{scenario}', [ScenarioController::class, 'show'])->name('show');
        Route::get('/{scenario}/attempt', [ScenarioController::class, 'attempt'])->name('attempt');
        Route::post('/{scenario}/submit', [ScenarioController::class, 'submit'])->name('submit');
        Route::get('/{scenario}/results', [ScenarioController::class, 'results'])->name('results');
    });

    // CPD
    Route::prefix('cpd')->name('cpd.')->group(function () {
        Route::get('/', [CpdController::class, 'index'])->name('index');
        Route::get('/create', [CpdController::class, 'create'])->name('create');
        Route::post('/', [CpdController::class, 'store'])->name('store');
        Route::get('/{activity}', [CpdController::class, 'show'])->name('show');
        Route::post('/{activity}/approve', [CpdController::class, 'approve'])->name('approve')->middleware('permission:nursing.cpd.manage');
    });

    // Calculators
    Route::prefix('calculators')->name('calculators.')->group(function () {
        Route::get('/', [CalculatorController::class, 'index'])->name('index');
        Route::post('/calculate', [CalculatorController::class, 'calculate'])->name('calculate');
    });

    // Study Center
    Route::prefix('study')->name('study.')->group(function () {
        Route::get('/', [StudyCenterController::class, 'index'])->name('index');
        Route::get('/flashcards', [StudyCenterController::class, 'flashcards'])->name('flashcards');
        Route::get('/quizzes', [StudyCenterController::class, 'quizzes'])->name('quizzes');
        Route::post('/quiz/submit', [StudyCenterController::class, 'submitQuiz'])->name('quiz.submit');
    });

    // Study Assistant
    Route::prefix('study-assistant')->name('study-assistant.')->group(function () {
        Route::get('/', [StudyAssistantController::class, 'index'])->name('index');
        Route::post('/ask', [StudyAssistantController::class, 'ask'])->name('ask');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->middleware('permission:nursing.reports.view')->group(function () {
        Route::get('/', [NursingReportController::class, 'index'])->name('index');
        Route::get('/student/{student}', [NursingReportController::class, 'studentReport'])->name('student');
        Route::get('/clinical-hours', [NursingReportController::class, 'clinicalHoursReport'])->name('clinical-hours');
        Route::get('/skills-competency', [NursingReportController::class, 'skillsCompetencyReport'])->name('skills-competency');
        Route::get('/attendance', [NursingReportController::class, 'attendanceReport'])->name('attendance');
        Route::get('/export/{type}', [NursingReportController::class, 'export'])->name('export');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->middleware('permission:nursing.settings.manage')->group(function () {
        Route::get('/', [NursingSettingController::class, 'index'])->name('index');
        Route::put('/', [NursingSettingController::class, 'update'])->name('update');
    });
});
