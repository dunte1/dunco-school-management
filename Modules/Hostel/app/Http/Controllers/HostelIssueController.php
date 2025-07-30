<?php

namespace Modules\Hostel\Http\Controllers;

use Modules\Hostel\Models\HostelIssue;
use Modules\Hostel\Models\Room;
use Modules\Hostel\Models\Bed;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Http\Requests\StoreHostelIssueRequest;
use Modules\Hostel\Http\Requests\UpdateHostelIssueRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HostelIssueController extends Controller
{
    public function index(Request $request)
    {
        $issues = HostelIssue::with(['room', 'bed', 'student', 'reportedBy', 'assignedTo'])
            ->when($request->input('status'), fn($q, $status) => $q->where('status', $status))
            ->when($request->input('priority'), fn($q, $priority) => $q->where('priority', $priority))
            ->when($request->input('room_id'), fn($q, $roomId) => $q->where('room_id', $roomId))
            ->latest()->paginate(15);
        $rooms = Room::all();
        $beds = Bed::all();
        $students = User::all();
        return view('hostel::issues.index', compact('issues', 'rooms', 'beds', 'students'));
    }

    public function create()
    {
        $rooms = Room::all();
        $beds = Bed::all();
        $students = User::all();
        return view('hostel::issues.create', compact('rooms', 'beds', 'students'));
    }

    public function store(StoreHostelIssueRequest $request)
    {
        HostelIssue::create($request->validated());
        return redirect()->route('hostel.issues.index')->with('success', 'Issue reported successfully.');
    }

    public function show(HostelIssue $hostelIssue)
    {
        $hostelIssue->load(['room', 'bed', 'student', 'reportedBy', 'assignedTo']);
        return view('hostel::issues.show', compact('hostelIssue'));
    }

    public function edit(HostelIssue $hostelIssue)
    {
        $rooms = Room::all();
        $beds = Bed::all();
        $students = User::all();
        return view('hostel::issues.edit', compact('hostelIssue', 'rooms', 'beds', 'students'));
    }

    public function update(UpdateHostelIssueRequest $request, HostelIssue $hostelIssue)
    {
        $hostelIssue->update($request->validated());
        return redirect()->route('hostel.issues.index')->with('success', 'Issue updated successfully.');
    }

    public function destroy(HostelIssue $hostelIssue)
    {
        $hostelIssue->delete();
        return redirect()->route('hostel.issues.index')->with('success', 'Issue deleted successfully.');
    }
}