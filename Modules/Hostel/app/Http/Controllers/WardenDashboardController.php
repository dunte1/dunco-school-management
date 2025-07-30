<?php

namespace Modules\Hostel\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Models\RoomAllocation;
use Modules\Hostel\Models\LeaveRequest;
use Modules\Hostel\Models\HostelIssue;
use Modules\Hostel\Models\HostelAnnouncement;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class WardenDashboardController extends Controller
{
    public function dashboard()
    {
        $wardenId = Auth::id();
        $hostels = Hostel::where('warden_id', $wardenId)->get();
        $allocations = RoomAllocation::with(['bed.room.hostel', 'student'])
            ->whereHas('bed.room.hostel', function($q) use ($wardenId) {
                $q->where('warden_id', $wardenId);
            })->latest()->take(10)->get();
        $pendingLeaves = LeaveRequest::with(['student'])
            ->where('status', 'pending')
            ->whereHas('student.allocations.bed.room.hostel', function($q) use ($wardenId) {
                $q->where('warden_id', $wardenId);
            })->latest()->take(10)->get();
        $issues = HostelIssue::with(['room', 'student'])
            ->where('status', 'open')
            ->whereHas('room.hostel', function($q) use ($wardenId) {
                $q->where('warden_id', $wardenId);
            })->latest()->take(10)->get();
        $announcements = HostelAnnouncement::where('audience', 'all')
            ->orWhere('audience', 'wardens')
            ->orderByDesc('published_at')
            ->take(5)->get();
        return view('hostel::warden.dashboard', compact('hostels', 'allocations', 'pendingLeaves', 'issues', 'announcements'));
    }
} 