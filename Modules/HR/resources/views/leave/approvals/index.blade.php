@extends('layouts.app')

@section('title', 'Leave Approvals')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Leave Approvals</h4>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('hr.leave.approvals.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="staff_id" class="form-label">Staff</label>
                                    <select class="form-control" id="staff_id" name="staff_id">
                                        <option value="">All Staff</option>
                                        @foreach($staff as $s)
                                            <option value="{{ $s->id }}" {{ request('staff_id') == $s->id ? 'selected' : '' }}>{{ $s->first_name }} {{ $s->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Leave Type</label>
                                    <select class="form-control" id="type" name="type">
                                        <option value="">All Types</option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->name }}" {{ request('type') == $type->name ? 'selected' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('hr.leave.approvals.index') }}" class="btn btn-secondary">Reset</a>
                    </form>

                    <!-- Leave Approvals Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaves as $leave)
                                    <tr>
                                        <td>{{ $leave->staff->first_name }} {{ $leave->staff->last_name }}</td>
                                        <td>{{ $leave->leaveType->name ?? $leave->type }}</td>
                                        <td>{{ $leave->start_date }}</td>
                                        <td>{{ $leave->end_date }}</td>
                                        <td>{{ $leave->days }}</td>
                                        <td>{{ Str::limit($leave->reason, 50) }}</td>
                                        <td>
                                            <span class="badge bg-warning">
                                                {{ ucfirst($leave->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('hr.leave.applications.show', $leave->id) }}" class="btn btn-info btn-sm">View</a>
                                            <form action="{{ route('hr.leave.approvals.approve', $leave->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this leave application?')">Approve</button>
                                            </form>
                                            <form action="{{ route('hr.leave.approvals.reject', $leave->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this leave application?')">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No pending leave applications found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($leaves, 'hasPages') && $leaves->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $leaves->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
