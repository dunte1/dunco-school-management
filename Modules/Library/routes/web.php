<?php

use Illuminate\Support\Facades\Route;
use Modules\Library\Http\Controllers\BookController;
use Modules\Library\Http\Controllers\MemberController;
use Modules\Library\Http\Controllers\BorrowController;
use Modules\Library\Http\Controllers\ReportController;
use Modules\Library\Http\Controllers\LibraryDashboardController;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->prefix('library')->name('library.')->group(function () {
    Route::get('/', [LibraryDashboardController::class, 'index'])->name('dashboard');
    Route::resource('books', BookController::class);
    Route::get('books/search', [BookController::class, 'search'])->name('books.search');
    Route::resource('members', MemberController::class);
    Route::resource('borrows', BorrowController::class);
    Route::resource('categories', \Modules\Library\Http\Controllers\CategoryController::class);
    Route::resource('authors', \Modules\Library\Http\Controllers\AuthorController::class);
    Route::resource('publishers', \Modules\Library\Http\Controllers\PublisherController::class);
    Route::get('reports/borrowed', [ReportController::class, 'borrowed'])->name('reports.borrowed');
    Route::get('reports/overdue', [ReportController::class, 'overdue'])->name('reports.overdue');
    Route::get('reports/most-borrowed', [ReportController::class, 'mostBorrowed'])->name('reports.most_borrowed');
});
