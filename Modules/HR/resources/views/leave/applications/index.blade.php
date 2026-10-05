@extends('layouts.app')

@section('title', 'Leave Applications')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Leave Applications</h4>
                    <a href="{{ route('hr.leave.applications.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Application
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('hr.leave.applications.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="staff_id" class="form-select">
                                    <option value="">All Staff</option>
                                    @foreach($staff as $s)
                                        <option value="{{ $s->id }}" {{ request('staff_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->first_name }} {{ $s->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="type" class="form-select">
                                    <option value="">All Types</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-outline-primary">Filter</button>
                                <a href="{{ route('hr.leave.applications.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>

                    <!-- Applications Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaves as $leave)
                                    <tr>
                                        <td>{{ $leave->staff->first_name }} {{ $leave->staff->last_name }}</td>
                                        <td>{{ $leave->leaveType->name ?? $leave->type }}</td>
                                        <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</td>
                                        <td>{{ $leave->days }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($leave->status == 'approved') bg-success
                                                @elseif($leave->status == 'rejected') bg-danger
                                                @else bg-warning
                                                @endif">
                                                {{ ucfirst($leave->status) }}
                                            </span>
                                        </td>
                                        <td>{{ Str::limit($leave->reason, 50) }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('hr.leave.applications.show', $leave->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.leave.applications.edit', $leave->id) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($leave->status == 'pending')
                                                    <form method="POST" action="{{ route('hr.leave.approve', $leave->id) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Approve this leave application?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form method="POST" action="{{ route('hr.leave.reject', $leave->id) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Reject this leave application?')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('hr.leave.applications.destroy', $leave->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this leave application?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No leave applications found.</td>
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
