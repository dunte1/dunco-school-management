<?php

use Illuminate\Support\Facades\Route;
use Modules\ChatBot\Http\Controllers\ChatBotController;
use Modules\ChatBot\Http\Controllers\AdminController;

Route::middleware(['web', 'auth'])->prefix('chatbot')->group(function () {
    Route::get('/', [ChatBotController::class, 'index'])->name('chatbot.index');
    Route::post('/send', [ChatBotController::class, 'sendMessage'])->name('chatbot.send');
    Route::get('/history', [ChatBotController::class, 'getHistory'])->name('chatbot.history');
    Route::delete('/history/{id}', [ChatBotController::class, 'deleteMessage'])->name('chatbot.delete');
    Route::post('/clear', [ChatBotController::class, 'clearHistory'])->name('chatbot.clear');
});

// Admin routes
Route::middleware(['web', 'auth'])->prefix('chatbot/admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('chatbot.admin.dashboard');
    Route::get('/settings', [AdminController::class, 'settings'])->name('chatbot.admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('chatbot.admin.settings.update');
    Route::get('/usage', [AdminController::class, 'getUsage'])->name('chatbot.admin.usage');
    Route::get('/models', [AdminController::class, 'getModels'])->name('chatbot.admin.models');
    Route::get('/health', [AdminController::class, 'getHealth'])->name('chatbot.admin.health');
    Route::post('/test-openai', [AdminController::class, 'testOpenAI'])->name('chatbot.admin.test-openai');
    Route::post('/clear-cache', [AdminController::class, 'clearCache'])->name('chatbot.admin.clear-cache');
});

