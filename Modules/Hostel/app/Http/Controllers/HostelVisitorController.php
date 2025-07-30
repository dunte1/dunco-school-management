<?php

namespace Modules\Hostel\Http\Controllers;

use Modules\Hostel\Models\HostelVisitor;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Http\Requests\StoreHostelVisitorRequest;
use Modules\Hostel\Http\Requests\UpdateHostelVisitorRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HostelVisitorController extends Controller
{
    public function index(Request $request)
    {
        $visitors = HostelVisitor::with(['hostel', 'student'])
            ->when($request->input('hostel_id'), fn($q, $hostelId) => $q->where('hostel_id', $hostelId))
            ->when($request->input('student_id'), fn($q, $studentId) => $q->where('student_id', $studentId))
            ->when($request->input('date'), fn($q, $date) => $q->whereDate('time_in', $date))
            ->latest()->paginate(15);
        $hostels = Hostel::all();
        $students = User::all();
        return view('hostel::visitors.index', compact('visitors', 'hostels', 'students'));
    }

    public function create()
    {
        $hostels = Hostel::all();
        $students = User::all();
        return view('hostel::visitors.create', compact('hostels', 'students'));
    }

    public function store(StoreHostelVisitorRequest $request)
    {
        HostelVisitor::create($request->validated());
        return redirect()->route('hostel.visitors.index')->with('success', 'Visitor log created successfully.');
    }

    public function show(HostelVisitor $hostelVisitor)
    {
        $hostelVisitor->load(['hostel', 'student']);
        return view('hostel::visitors.show', compact('hostelVisitor'));
    }

    public function edit(HostelVisitor $hostelVisitor)
    {
        $hostels = Hostel::all();
        $students = User::all();
        return view('hostel::visitors.edit', compact('hostelVisitor', 'hostels', 'students'));
    }

    public function update(UpdateHostelVisitorRequest $request, HostelVisitor $hostelVisitor)
    {
        $hostelVisitor->update($request->validated());
        return redirect()->route('hostel.visitors.index')->with('success', 'Visitor log updated successfully.');
    }

    public function destroy(HostelVisitor $hostelVisitor)
    {
        $hostelVisitor->delete();
        return redirect()->route('hostel.visitors.index')->with('success', 'Visitor log deleted successfully.');
    }
} 