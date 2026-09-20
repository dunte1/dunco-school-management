<?php

namespace Modules\Hostel\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Models\Room;
use Modules\Hostel\Models\RoomAllocation;
use Modules\Hostel\Models\HostelIssue;
use Modules\Hostel\Http\Requests\StoreHostelRequest;
use Modules\Hostel\Http\Requests\UpdateHostelRequest;
use App\Http\Controllers\Controller;

class HostelController extends Controller
{
    public function index()
    {
        $hostels = Hostel::withCount('rooms')->paginate(15);
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
        $hostel->load(['floors.rooms.beds', 'wardens.user']);
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
        $stats = [
            'hostels' => Hostel::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'total_beds' => \Modules\Hostel\Models\Bed::count(),
            'occupied_beds' => \Modules\Hostel\Models\Bed::where('status', 'occupied')->count(),
            'allocations' => RoomAllocation::where('status', 'active')->count(),
            'pending_issues' => HostelIssue::where('status', 'pending')->count(),
            'open_issues' => HostelIssue::where('status', 'open')->count(),
            'total_issues' => HostelIssue::count(),
            'pending_leaves' => \Modules\Hostel\Models\LeaveRequest::where('status', 'pending')->count(),
        ];

        $stats['occupancy_rate'] = $stats['total_beds'] > 0
            ? round(($stats['occupied_beds'] / $stats['total_beds']) * 100, 1)
            : 0;

        $recentAllocations = RoomAllocation::with(['bed.room', 'student'])
            ->latest('created_at')->take(5)->get();

        $recentIssues = HostelIssue::with(['room', 'reportedBy'])
            ->latest('created_at')->take(5)->get();

        $recentActivity = [];
        foreach ($recentAllocations as $alloc) {
            $recentActivity[] = [
                'type' => 'allocation',
                'message' => 'Room ' . ($alloc->bed->room->name ?? '-') . ' bed ' . ($alloc->bed->bed_number ?? '-') .
                    ' allocated to ' . ($alloc->student->name ?? 'Unknown') .
                    ' on ' . $alloc->created_at->format('M d, Y H:i'),
                'created_at' => $alloc->created_at,
            ];
        }
        foreach ($recentIssues as $issue) {
            $recentActivity[] = [
                'type' => 'issue',
                'message' => 'Issue reported in Room ' . ($issue->room->name ?? '-') .
                    ' by ' . ($issue->reportedBy->name ?? 'Unknown') .
                    ': ' . ($issue->description ? \Str::limit($issue->description, 40) : 'No description') .
                    ' [' . ucfirst($issue->status) . ']',
                'created_at' => $issue->created_at,
            ];
        }

        usort($recentActivity, fn($a, $b) => $b['created_at'] <=> $a['created_at']);
        $recentActivity = array_slice($recentActivity, 0, 10);

        return view('hostel::dashboard', compact('stats', 'recentActivity'));
    }
}
