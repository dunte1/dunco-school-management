<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $items = [];
        
        try {
            // Try to get announcements from Communication module
            if (class_exists('Modules\\Communication\\Models\\Notification')) {
                $items = \Modules\Communication\Models\Notification::where('notifiable_id', $user->id)
                    ->latest()->take(50)->get(['id','title','type','data','created_at','read_at']);
            }
            // Fallback to general announcements if Communication module not available
            elseif (class_exists('App\\Models\\Announcement')) {
                $items = \App\Models\Announcement::latest()->take(50)->get();
            }
            // Create sample announcements if no module available
            else {
                $items = $this->getSampleAnnouncements();
            }
        } catch (\Throwable $e) { 
            $items = $this->getSampleAnnouncements();
        }
        
        return response()->json($items->map(function($n){
            return [
                'id' => $n->id ?? $n['id'],
                'title' => $n->title ?? $n['title'],
                'content' => $n->content ?? $n['content'] ?? $n->data ?? '',
                'type' => $n->type ?? $n['type'] ?? 'announcement',
                'priority' => $n->priority ?? $n['priority'] ?? 'normal',
                'image_url' => $n->image_url ?? $n['image_url'] ?? null,
                'is_read' => isset($n->read_at) ? !is_null($n->read_at) : ($n['is_read'] ?? false),
                'created_at' => optional($n->created_at ?? $n['created_at'])->toIso8601String(),
                'read_at' => optional($n->read_at ?? $n['read_at'])->toIso8601String(),
            ];
        }));
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        
        try {
            if (class_exists('Modules\\Communication\\Models\\Notification')) {
                $announcement = \Modules\Communication\Models\Notification::where('notifiable_id', $user->id)
                    ->where('id', $id)->first();
            } elseif (class_exists('App\\Models\\Announcement')) {
                $announcement = \App\Models\Announcement::find($id);
            } else {
                $announcement = $this->getSampleAnnouncement($id);
            }
            
            if (!$announcement) {
                return response()->json(['message' => 'Announcement not found'], 404);
            }
            
            // Mark as read
            if (isset($announcement->read_at) && is_null($announcement->read_at)) {
                $announcement->update(['read_at' => now()]);
            }
            
            return response()->json([
                'id' => $announcement->id ?? $announcement['id'],
                'title' => $announcement->title ?? $announcement['title'],
                'content' => $announcement->content ?? $announcement['content'] ?? $announcement->data ?? '',
                'type' => $announcement->type ?? $announcement['type'] ?? 'announcement',
                'priority' => $announcement->priority ?? $announcement['priority'] ?? 'normal',
                'image_url' => $announcement->image_url ?? $announcement['image_url'] ?? null,
                'is_read' => true,
                'created_at' => optional($announcement->created_at ?? $announcement['created_at'])->toIso8601String(),
                'read_at' => now()->toIso8601String(),
            ]);
            
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error fetching announcement'], 500);
        }
    }

    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();
        
        try {
            if (class_exists('Modules\\Communication\\Models\\Notification')) {
                $announcement = \Modules\Communication\Models\Notification::where('notifiable_id', $user->id)
                    ->where('id', $id)->first();
                    
                if ($announcement) {
                    $announcement->update(['read_at' => now()]);
                }
            }
            
            return response()->json(['message' => 'Marked as read', 'status' => 'success']);
            
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error marking as read'], 500);
        }
    }

    private function getSampleAnnouncements()
    {
        return collect([
            [
                'id' => 1,
                'title' => 'Welcome to Dunco School!',
                'content' => 'Welcome to the new academic year! We are excited to have you all back. Please check your timetables and ensure all fees are paid on time.',
                'type' => 'announcement',
                'priority' => 'high',
                'image_url' => null,
                'is_read' => false,
                'created_at' => now()->subDays(1),
                'read_at' => null,
            ],
            [
                'id' => 2,
                'title' => 'Parent-Teacher Meeting',
                'content' => 'Parent-teacher meetings will be held next week. Please check the schedule and book your preferred time slot.',
                'type' => 'event',
                'priority' => 'normal',
                'image_url' => null,
                'is_read' => false,
                'created_at' => now()->subDays(2),
                'read_at' => null,
            ],
            [
                'id' => 3,
                'title' => 'Library Week Celebration',
                'content' => 'Join us for Library Week celebrations with book fairs, reading competitions, and author visits.',
                'type' => 'event',
                'priority' => 'normal',
                'image_url' => null,
                'is_read' => true,
                'created_at' => now()->subDays(3),
                'read_at' => now()->subDays(1),
            ],
        ]);
    }

    private function getSampleAnnouncement($id)
    {
        $announcements = $this->getSampleAnnouncements();
        return $announcements->firstWhere('id', $id);
    }
}
