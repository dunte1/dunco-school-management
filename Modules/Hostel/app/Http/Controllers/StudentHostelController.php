<?php

namespace Modules\Hostel\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Models\RoomAllocation;
use Modules\Hostel\Models\Room;
use Modules\Hostel\Models\Bed;
use Modules\Hostel\Models\HostelFee;
use Modules\Hostel\Models\HostelIssue;
use Modules\Hostel\Models\LeaveRequest;
use Modules\Hostel\Models\HostelAnnouncement;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class StudentHostelController extends Controller
{
    public function dashboard()
    {
        $studentId = Auth::id();
        $allocation = RoomAllocation::with(['bed.room.hostel'])
            ->where('student_id', $studentId)
            ->where('status', 'active')
            ->latest()->first();
        $fees = HostelFee::where('student_id', $studentId)->latest()->get();
        $issues = HostelIssue::where('student_id', $studentId)->latest()->get();
        $leaves = LeaveRequest::where('student_id', $studentId)->latest()->get();
        $announcements = HostelAnnouncement::where('audience', 'all')
            ->orWhere('audience', 'residents')
            ->orderByDesc('published_at')
            ->take(10)->get();
        return view('hostel::student.dashboard', compact('allocation', 'fees', 'issues', 'leaves', 'announcements'));
    }
} 