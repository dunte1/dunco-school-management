<?php

namespace Modules\Hostel\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Http\Requests\StoreHostelRequest;
use Modules\Hostel\Http\Requests\UpdateHostelRequest;
use App\Http\Controllers\Controller;

class HostelController extends Controller
{
    public function index()
    {
        $hostels = Hostel::paginate(15);
        return view('hostel::hostels.index', compact('hostels'));
    }

    public function create()
    {
        return view('hostel::hostels.create');
    }

    public function store(StoreHostelRequest $request)
    {
        $hostel = Hostel::create($request->validated());
        return redirect()->route('hostel.hostels.index')->with('success', 'Hostel created successfully.');
    }

    public function show(Hostel $hostel)
    {
        return view('hostel::hostels.show', compact('hostel'));
    }

    public function edit(Hostel $hostel)
    {
        return view('hostel::hostels.edit', compact('hostel'));
    }

    public function update(UpdateHostelRequest $request, Hostel $hostel)
    {
        $hostel->update($request->validated());
        return redirect()->route('hostel.hostels.index')->with('success', 'Hostel updated successfully.');
    }

    public function destroy(Hostel $hostel)
    {
        $hostel->delete();
        return redirect()->route('hostel.hostels.index')->with('success', 'Hostel deleted successfully.');
    }

    public function dashboard()
    {
        // Debug: Log that this method is being called
        \Log::info('HostelController@dashboard method called');
        
        // Example stats (replace with real queries as needed)
        $stats = [
            'hostels' => \Modules\Hostel\Models\Hostel::count(),
            'available_rooms' => \Modules\Hostel\Models\Room::where('status', 'available')->count(),
            'allocations' => \Modules\Hostel\Models\RoomAllocation::count(),
            'issues' => \Modules\Hostel\Models\HostelIssue::where('status', 'pending')->count(),
        ];
        // Real recent activity: last 5 allocations and last 5 issues
        $recentAllocations = \Modules\Hostel\Models\RoomAllocation::with(['student', 'bed.room'])
            ->latest('created_at')->take(5)->get();
        $recentIssues = \Modules\Hostel\Models\HostelIssue::with(['room', 'reportedBy'])
            ->latest('created_at')->take(5)->get();
        $recentActivity = [];
        foreach ($recentAllocations as $alloc) {
            $recentActivity[] = 'Room ' . ($alloc->bed->room->name ?? '-') . ' bed ' . ($alloc->bed->bed_number ?? '-') .
                ' allocated to ' . ($alloc->student->name ?? 'Unknown') .
                ' on ' . $alloc->created_at->format('M d, Y H:i');
        }
        foreach ($recentIssues as $issue) {
            $recentActivity[] = 'Issue reported in Room ' . ($issue->room->name ?? '-') .
                ' by ' . ($issue->reportedBy->name ?? 'Unknown') .
                ': ' . ($issue->description ? \Str::limit($issue->description, 40) : 'No description') .
                ' [' . ucfirst($issue->status) . ']';
        }
        // Debug: Check user and roles
        $user = auth()->user();
        \Log::info('User: ' . $user->name . ', Roles: ' . $user->roles->pluck('name')->implode(', '));
        // Debug: Check if we can access the view
        if (!view()->exists('hostel::dashboard')) {
            \Log::error('Hostel dashboard view does not exist');
            return response('Hostel dashboard view not found', 500);
        }
        // You can load summary data here later
        return view('hostel::dashboard', compact('stats', 'recentActivity'));
    }
}
