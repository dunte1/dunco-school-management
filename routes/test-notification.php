<?php

use App\Models\User;
use App\Notifications\GenericDatabaseNotification;
use Illuminate\Support\Facades\Route;

Route::get('/test-notification', function () {
    $user = User::first();
    
    if (!$user) {
        return 'No users found in the database';
    }
    
    try {
        $notification = new GenericDatabaseNotification([
            'title' => 'Test Notification',
            'message' => 'This is a test notification',
            'type' => 'info'
        ]);
        
        $user->notify($notification);
        
        return 'Notification sent successfully to user: ' . $user->email;
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
    }
});
