<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BroadcastingController extends Controller
{
    /**
     * Authenticate the request for channel access.
     */
    public function auth(Request $request)
    {
        $user = Auth::guard('sanctum')->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $channelName = $request->input('channel_name');
        
        // Validate channel access based on user permissions
        if ($this->canAccessChannel($user, $channelName)) {
            return response()->json([
                'auth' => 'authenticated_user_' . $user->id,
            ]);
        }

        return response()->json(['error' => 'Forbidden'], 403);
    }

    /**
     * Check if user can access the channel.
     */
    private function canAccessChannel($user, $channelName)
    {
        // User can access their own private channel
        if (preg_match('/^private-user\.(\d+)$/', $channelName, $matches)) {
            return $user->id == $matches[1];
        }

        // User can access role-based channels
        if (preg_match('/^private-role\.(.+)$/', $channelName, $matches)) {
            $role = $matches[1];
            return $user->type === $role || $user->hasRole($role);
        }

        // User can access school-wide channels
        if (preg_match('/^private-school\.(\d+)$/', $channelName, $matches)) {
            $schoolId = $matches[1];
            return $user->school_id == $schoolId;
        }

        return false;
    }
}
