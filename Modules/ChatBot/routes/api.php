<?php

use Illuminate\Support\Facades\Route;
use Modules\ChatBot\Http\Controllers\ChatBotController;

Route::prefix('chatbot')->middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::post('/send', [ChatBotController::class, 'sendMessage'])->name('chatbot.api.send');
    Route::get('/history', [ChatBotController::class, 'getHistory'])->name('chatbot.api.history');
    Route::delete('/history/{id}', [ChatBotController::class, 'deleteMessage'])->name('chatbot.api.delete');
    Route::post('/clear', [ChatBotController::class, 'clearHistory'])->name('chatbot.api.clear');
});

