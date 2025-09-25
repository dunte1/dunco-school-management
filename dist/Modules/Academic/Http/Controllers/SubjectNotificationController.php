<?php

namespace Modules\Academic\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Academic\Models\SubjectNotification;
use Illuminate\Support\Facades\Auth;

class SubjectNotificationController extends Controller
{
    public function index($subjectId)
    {
        $notifications = SubjectNotification::where('subject_id', $subjectId)->with('user')->latest()->get();
        return response()->json(['notifications' => $notifications]);
    }
    public function store(Request $request, $subjectId)
    {
        $request->validate([
            'type' => 'required|string|max:50',
            'message' => 'required|string|max:255',
        ]);
        $notification = SubjectNotification::create([
            'subject_id' => $subjectId,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'message' => $request->message,
        ]);
        return response()->json(['success' => true, 'notification' => $notification]);
    }
    public function markRead($subjectId, $notificationId)
    {
        $notification = SubjectNotification::where('subject_id', $subjectId)->findOrFail($notificationId);
        $notification->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }
} 