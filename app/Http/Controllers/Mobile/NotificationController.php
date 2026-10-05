<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $items = [];
        
        try {
            if (class_exists('Modules\\Communication\\Models\\Notification')) {
                $items = \Modules\Communication\Models\Notification::where('notifiable_id', $user->id)
                    ->latest()->take(50)->get(['id','title','type','data','created_at']);
            }
        } catch (\Throwable $e) { 
            $items = collect(); 
        }
        
        return response()->json($items->map(function($n){
            return [
                'id' => $n->id,
                'title' => $n->title,
                'type' => $n->type,
                'created_at' => optional($n->created_at)->toIso8601String(),
            ];
        }));
    }

    public function markRead(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|integer'
        ]);

        try {
            if (class_exists('Modules\\Communication\\Models\\Notification')) {
                $notification = \Modules\Communication\Models\Notification::where('id', $request->notification_id)
                    ->where('notifiable_id', $request->user()->id)
                    ->first();
                
                if ($notification) {
                    $notification->update(['read_at' => now()]);
                }
            }
        } catch (\Throwable $e) {
            // Ignore errors
        }

        return response()->json(['message' => 'Notification marked as read']);
    }
}
