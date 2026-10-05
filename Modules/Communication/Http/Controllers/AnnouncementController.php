<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Communication\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')
            ->when(request('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
            })
            ->when(request('type'), function ($query, $type) {
                $query->where('type', $type);
            })
            ->when(request('priority'), function ($query, $priority) {
                $query->where('priority', $priority);
            })
            ->when(request('status'), function ($query, $status) {
                if ($status === 'active') {
                    $query->active();
                } elseif ($status === 'published') {
                    $query->published();
                } elseif ($status === 'expired') {
                    $query->where('expires_at', '<=', now());
                }
            })
            ->latest()
            ->paginate(15);

        return view('communication::announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('communication::announcements.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,academic,event,emergency',
            'priority' => 'required|string|in:low,normal,high,urgent',
            'is_active' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at'
        ]);

        $announcement = Announcement::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'priority' => $request->priority,
            'is_active' => $request->boolean('is_active', true),
            'is_published' => $request->boolean('is_published', false),
            'published_at' => $request->published_at ?: ($request->boolean('is_published') ? now() : null),
            'expires_at' => $request->expires_at,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('communication.announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function show(Announcement $announcement)
    {
        return view('communication::announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('communication::announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,academic,event,emergency',
            'priority' => 'required|string|in:low,normal,high,urgent',
            'is_active' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at'
        ]);

        $announcement->update([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'priority' => $request->priority,
            'is_active' => $request->boolean('is_active', true),
            'is_published' => $request->boolean('is_published', false),
            'published_at' => $request->published_at ?: ($request->boolean('is_published') ? now() : null),
            'expires_at' => $request->expires_at,
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('communication.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('communication.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    public function toggleStatus(Announcement $announcement)
    {
        $announcement->update([
            'is_active' => !$announcement->is_active,
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('communication.announcements.index')
            ->with('success', 'Announcement status updated successfully.');
    }

    public function publish(Announcement $announcement)
    {
        $announcement->update([
            'published_at' => now(),
            'updated_by' => Auth::id()
        ]);

        return redirect()->route('communication.announcements.index')
            ->with('success', 'Announcement published successfully.');
    }

    public function markAsRead(Announcement $announcement)
    {
        // This would typically update a pivot table or user_announcement_reads table
        // For now, we'll just return success
        return redirect()->back()
            ->with('success', 'Announcement marked as read.');
    }
}
