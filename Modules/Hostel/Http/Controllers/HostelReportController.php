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
        $stats = [
            'total_hostels' => Hostel::count(),
            'total_rooms' => Room::count(),
            'total_beds' => Bed::count(),
            'occupied_beds' => Bed::where('status', 'occupied')->count(),
            'available_beds' => Bed::where('status', 'available')->count(),
            'maintenance_beds' => Bed::where('status', 'maintenance')->count(),
            'active_allocations' => RoomAllocation::where('status', 'active')->count(),
            'open_issues' => HostelIssue::where('status', 'open')->count(),
            'resolved_issues' => HostelIssue::where('status', 'resolved')->count(),
            'total_visitors' => HostelVisitor::count(),
            'fee_pending' => HostelFee::where('status', 'unpaid')->count(),
            'fee_overdue' => HostelFee::where('status', 'overdue')->count(),
            'fee_collected' => HostelFee::where('status', 'paid')->sum('amount'),
            'fee_pending_amount' => HostelFee::where('status', '!=', 'paid')->sum('amount'),
        ];

        $stats['occupancy_rate'] = $stats['total_beds'] > 0
            ? round(($stats['occupied_beds'] / $stats['total_beds']) * 100, 1)
            : 0;

        $issuesByPriority = HostelIssue::selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->get();

        $recentIssues = HostelIssue::with(['room', 'reportedBy'])
            ->latest()->take(10)->get();

        return view('hostel::reports.dashboard', compact('stats', 'issuesByPriority', 'recentIssues'));
    }

    public function occupancy(Request $request)
    {
        $query = Hostel::with('rooms.beds');

        if ($request->filled('hostel_id')) {
            $query->where('id', $request->hostel_id);
        }

        $hostels = $query->get();

        $summary = [
            'total_beds' => Bed::count(),
            'occupied' => Bed::where('status', 'occupied')->count(),
            'available' => Bed::where('status', 'available')->count(),
            'maintenance' => Bed::where('status', 'maintenance')->count(),
            'reserved' => Bed::where('status', 'reserved')->count(),
        ];

        $summary['occupancy_rate'] = $summary['total_beds'] > 0
            ? round(($summary['occupied'] / $summary['total_beds']) * 100, 1)
            : 0;

        $allHostels = Hostel::orderBy('name')->get();

        return view('hostel::reports.occupancy', compact('hostels', 'summary', 'allHostels'));
    }

    public function allocations(Request $request)
    {
        $query = RoomAllocation::with(['bed.room.hostel', 'student']);

        if ($request->filled('hostel_id')) {
            $query->whereHas('bed.room', fn($q) => $q->where('hostel_id', $request->hostel_id));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->where('check_in', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('check_in', '<=', $request->date_to);
        }

        $allocations = $query->latest()->paginate(50)->withQueryString();

        $allHostels = Hostel::orderBy('name')->get();

        return view('hostel::reports.allocations', compact('allocations', 'allHostels'));
    }

    public function maintenance(Request $request)
    {
        $query = HostelIssue::with(['room', 'bed', 'student', 'assignedTo', 'reportedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('issue_type')) {
            $query->where('issue_type', $request->issue_type);
        }
        if ($request->filled('hostel_id')) {
            $query->whereHas('room', fn($q) => $q->where('hostel_id', $request->hostel_id));
        }

        $issues = $query->latest()->paginate(50)->withQueryString();

        $issueTypes = HostelIssue::distinct()->pluck('issue_type')->filter()->values();
        $allHostels = Hostel::orderBy('name')->get();

        return view('hostel::reports.maintenance', compact('issues', 'issueTypes', 'allHostels'));
    }

    public function movement(Request $request)
    {
        $query = HostelVisitor::with(['hostel', 'student']);

        if ($request->filled('hostel_id')) {
            $query->where('hostel_id', $request->hostel_id);
        }
        if ($request->filled('date_from')) {
            $query->where('time_in', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('time_in', '<=', $request->date_to . ' 23:59:59');
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('visitor_name', 'like', "%{$search}%")
                  ->orWhere('pass_number', 'like', "%{$search}%");
            });
        }

        $visitors = $query->latest()->paginate(50)->withQueryString();

        $summary = [
            'total_visitors' => HostelVisitor::count(),
            'today_visitors' => HostelVisitor::whereDate('time_in', today())->count(),
            'currently_in' => HostelVisitor::whereNull('time_out')->count(),
        ];

        $allHostels = Hostel::orderBy('name')->get();

        return view('hostel::reports.movement', compact('visitors', 'summary', 'allHostels'));
    }

    public function feeDefaulters(Request $request)
    {
        $query = HostelFee::with(['hostel', 'room', 'bed', 'student']);

        if ($request->filled('hostel_id')) {
            $query->where('hostel_id', $request->hostel_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', '!=', 'paid');
        }
        if ($request->filled('date_from')) {
            $query->where('due_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('due_date', '<=', $request->date_to);
        }

        $defaulters = $query->latest()->paginate(50)->withQueryString();

        $summary = [
            'total_unpaid' => HostelFee::where('status', '!=', 'paid')->count(),
            'total_overdue' => HostelFee::where('status', 'overdue')->count(),
            'total_unpaid_amount' => HostelFee::where('status', '!=', 'paid')->sum('amount'),
            'total_overdue_amount' => HostelFee::where('status', 'overdue')->sum('amount'),
            'total_fine' => HostelFee::where('status', '!=', 'paid')->sum('fine'),
        ];

        $allHostels = Hostel::orderBy('name')->get();

        return view('hostel::reports.fee_defaulters', compact('defaulters', 'summary', 'allHostels'));
    }

    public function damages(Request $request)
    {
        $query = HostelIssue::with(['room', 'bed', 'student', 'assignedTo'])
            ->where('priority', 'high');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('hostel_id')) {
            $query->whereHas('room', fn($q) => $q->where('hostel_id', $request->hostel_id));
        }

        $issues = $query->latest()->paginate(50)->withQueryString();

        $allHostels = Hostel::orderBy('name')->get();

        return view('hostel::reports.damages', compact('issues', 'allHostels'));
    }
}
