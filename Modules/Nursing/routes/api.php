<?php

use Illuminate\Support\Facades\Route;
use Modules\Nursing\Http\Controllers\Api\NursingApiController;

Route::middleware(['auth:sanctum'])->prefix('v1/nursing')->name('api.nursing.')->group(function () {
    Route::get('/dashboard', [NursingApiController::class, 'dashboard'])->name('dashboard');
    Route::get('/placements', [NursingApiController::class, 'placements'])->name('placements');
    Route::get('/logbook', [NursingApiController::class, 'logbook'])->name('logbook');
    Route::post('/logbook', [NursingApiController::class, 'storeLogbook'])->name('logbook.store');
    Route::get('/skills', [NursingApiController::class, 'skills'])->name('skills');
    Route::get('/hours', [NursingApiController::class, 'hours'])->name('hours');
    Route::post('/attendance', [NursingApiController::class, 'markAttendance'])->name('attendance.mark');
});
