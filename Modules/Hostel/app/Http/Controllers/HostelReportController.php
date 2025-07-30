<?php

namespace Modules\Hostel\Http\Controllers;

use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Models\Room;
use Modules\Hostel\Models\Bed;
use Modules\Hostel\Models\RoomAllocation;
use Modules\Hostel\Models\HostelFee;
use Modules\Hostel\Models\HostelIssue;
use Modules\Hostel\Models\HostelVisitor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HostelReportController extends Controller
{
    public function dashboard()
    {
        return view('hostel::reports.dashboard');
    }

    public function occupancy(Request $request)
    {
        $hostels = Hostel::with('rooms.beds')->get();
        return view('hostel::reports.occupancy', compact('hostels'));
    }

    public function allocations(Request $request)
    {
        $allocations = RoomAllocation::with(['bed.room.hostel', 'student'])->latest()->paginate(50);
        return view('hostel::reports.allocations', compact('allocations'));
    }

    public function maintenance(Request $request)
    {
        $issues = HostelIssue::with(['room', 'bed', 'student', 'assignedTo'])->latest()->paginate(50);
        return view('hostel::reports.maintenance', compact('issues'));
    }

    public function movement(Request $request)
    {
        $visitors = HostelVisitor::with(['hostel', 'student'])->latest()->paginate(50);
        return view('hostel::reports.movement', compact('visitors'));
    }

    public function feeDefaulters(Request $request)
    {
        $defaulters = HostelFee::with(['hostel', 'student'])
            ->where('status', '!=', 'paid')
            ->latest()->paginate(50);
        return view('hostel::reports.fee_defaulters', compact('defaulters'));
    }

    public function damages(Request $request)
    {
        $issues = HostelIssue::whereNotNull('resolution_notes')->where('priority', 'high')->latest()->paginate(50);
        return view('hostel::reports.damages', compact('issues'));
    }
} 