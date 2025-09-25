<?php

namespace Modules\Timetable\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotificationPreferenceController extends Controller
{
    /**
     * Get the authenticated user's notification preferences
     */
    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json($user->notification_preferences);
    }

    /**
     * Update the authenticated user's notification preferences
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $prefs = $request->validate([
            'in_app' => 'sometimes|boolean',
            'email' => 'sometimes|boolean',
            'sms' => 'sometimes|boolean',
            // Add more types as needed
        ]);
        $user->notification_preferences = array_merge($user->notification_preferences, $prefs);
        $user->save();
        return response()->json(['success' => true, 'preferences' => $user->notification_preferences]);
    }
}
