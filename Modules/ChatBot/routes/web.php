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
    
    // Document routes
    Route::post('/upload-document', [ChatBotController::class, 'uploadDocument'])->name('chatbot.upload-document');
    Route::post('/ask-document', [ChatBotController::class, 'askDocument'])->name('chatbot.ask-document');
    Route::get('/documents', [ChatBotController::class, 'getDocuments'])->name('chatbot.documents');
    Route::delete('/documents/{id}', [ChatBotController::class, 'deleteDocument'])->name('chatbot.delete-document');
    Route::get('/search-documents', [ChatBotController::class, 'searchDocuments'])->name('chatbot.search-documents');

    // Serve ChatBot assets
    Route::get('/assets/js/chatbot.js', function() {
        $path = base_path('Modules/ChatBot/resources/assets/js/chatbot.js');
        if (file_exists($path)) {
            return response()->file($path, ['Content-Type' => 'application/javascript']);
        }
        return response('JavaScript file not found', 404);
    })->name('chatbot.assets.js');
});

// Admin routes
Route::middleware(['web', 'auth', 'admin'])->prefix('chatbot/admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('chatbot.admin.dashboard');
    Route::get('/settings', [AdminController::class, 'settings'])->name('chatbot.admin.settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('chatbot.admin.settings.update');
    Route::get('/usage', [AdminController::class, 'getUsage'])->name('chatbot.admin.usage');
    Route::get('/models', [AdminController::class, 'getModels'])->name('chatbot.admin.models');
    Route::get('/health', [AdminController::class, 'getHealth'])->name('chatbot.admin.health');
    Route::post('/test-openai', [AdminController::class, 'testOpenAI'])->name('chatbot.admin.test-openai');
    Route::post('/clear-cache', [AdminController::class, 'clearCache'])->name('chatbot.admin.clear-cache');
});

