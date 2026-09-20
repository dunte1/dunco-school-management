<?php

use Illuminate\Support\Facades\Route;
use Modules\Document\Http\Controllers\DocumentController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('documents/upload', [DocumentController::class, 'upload'])->name('document.upload');
    Route::post('documents/upload', [DocumentController::class, 'handleUpload'])->name('document.upload.handle');
    Route::get('documents/manage', [DocumentController::class, 'manage'])->name('document.manage');
    Route::get('documents/{id}/download', [DocumentController::class, 'download'])->name('document.download');
    Route::post('documents/{id}/toggle-status', [DocumentController::class, 'toggleStatus'])->name('document.toggle-status');

    Route::resource('documents', DocumentController::class)->names('document');
});
