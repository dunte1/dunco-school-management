<?php

namespace Modules\Hostel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Hostel\Models\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::with(['recipient']);

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search by title
        if ($request->has('search') && $request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('hostel::notifications.index', compact('notifications'));
    }

    public function create()
    {
        $students = \Modules\Hostel\Models\Student::all();
        $hostels = \Modules\Hostel\Models\Hostel::all();
        return view('hostel::notifications.create', compact('students', 'hostels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:announcement,reminder,alert,maintenance',
            'recipient_type' => 'required|in:all,hostel,specific',
            'hostel_id' => 'nullable|exists:hostels,id',
            'recipient_ids' => 'nullable|array',
            'scheduled_at' => 'nullable|date|after:now'
        ]);

        try {
            $data = $request->all();
            $data['created_by'] = auth()->id();
            $data['status'] = 'pending';

            Notification::create($data);

            return redirect()->route('hostel.notifications.index')
                           ->with('success', 'Notification created successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create notification: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $notification = Notification::with(['recipient', 'createdBy'])->findOrFail($id);
        return view('hostel::notifications.show', compact('notification'));
    }

    public function edit($id)
    {
        $notification = Notification::findOrFail($id);
        $students = \Modules\Hostel\Models\Student::all();
        $hostels = \Modules\Hostel\Models\Hostel::all();
        return view('hostel::notifications.edit', compact('notification', 'students', 'hostels'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:announcement,reminder,alert,maintenance',
            'recipient_type' => 'required|in:all,hostel,specific',
            'hostel_id' => 'nullable|exists:hostels,id',
            'recipient_ids' => 'nullable|array',
            'scheduled_at' => 'nullable|date',
            'status' => 'required|in:pending,sent,failed'
        ]);

        try {
            $notification = Notification::findOrFail($id);
            $notification->update($request->all());

            return redirect()->route('hostel.notifications.index')
                           ->with('success', 'Notification updated successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update notification: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();

            return redirect()->route('hostel.notifications.index')
                           ->with('success', 'Notification deleted successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete notification: ' . $e->getMessage()]);
        }
    }
}
