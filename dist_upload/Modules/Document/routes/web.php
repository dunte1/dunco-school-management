<?php

use Illuminate\Support\Facades\Route;
use Modules\Document\Http\Controllers\DocumentController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('documents', DocumentController::class)->names('document');
    
    // Add missing routes
    Route::get('documents/upload', [DocumentController::class, 'upload'])->name('document.upload');
    Route::get('documents/manage', [DocumentController::class, 'manage'])->name('document.manage');
});

