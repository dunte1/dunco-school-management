<?php

use Illuminate\Support\Facades\Route;
use Modules\ChatBot\Http\Controllers\ChatBotController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('chatbots', ChatBotController::class)->names('chatbot');
});
