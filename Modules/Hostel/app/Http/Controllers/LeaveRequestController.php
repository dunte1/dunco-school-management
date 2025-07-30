<?php

namespace Modules\Hostel\Http\Controllers;

use Modules\Hostel\Models\LeaveRequest;
use Modules\Hostel\App\Models\Warden;
use Modules\Hostel\Http\Requests\StoreLeaveRequest;
use Modules\Hostel\Http\Requests\UpdateLeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        $leaves = LeaveRequest::with(['student', 'warden'])
            ->when($request->input('status'), fn($q, $status) => $q->where('status', $status))
            ->when($request->input('student_id'), fn($q, $studentId) => $q->where('student_id', $studentId))
            ->latest()->paginate(15);
        $students = User::all();
        $wardens = User::all();
        return view('hostel::leaves.index', compact('leaves', 'students', 'wardens'));
    }

    public function create()
    {
        $students = User::all();
        $wardens = User::all();
        return view('hostel::leaves.create', compact('students', 'wardens'));
    }

    public function store(StoreLeaveRequest $request)
    {
        $data = $request->validated();
        $data['status'] = 'pending';
        LeaveRequest::create($data);
        return redirect()->route('hostel.leave_requests.index')->with('success', 'Leave request submitted successfully.');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['student', 'warden']);
        return view('hostel::leaves.show', compact('leaveRequest'));
    }

    public function edit(LeaveRequest $leaveRequest)
    {
        $students = User::all();
        $wardens = User::all();
        return view('hostel::leaves.edit', compact('leaveRequest', 'students', 'wardens'));
    }

    public function update(UpdateLeaveRequest $request, LeaveRequest $leaveRequest)
    {
        $leaveRequest->update($request->validated());
        return redirect()->route('hostel.leave_requests.index')->with('success', 'Leave request updated successfully.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();
        return redirect()->route('hostel.leave_requests.index')->with('success', 'Leave request deleted successfully.');
    }
} 