@extends('layouts.app')

@section('title', 'Overtime Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Overtime Management</h4>
                    <a href="{{ route('hr.overtime.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Overtime
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('hr.overtime.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="date_from" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="date_to" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('hr.overtime.index') }}" class="btn btn-secondary">Reset</a>
                    </form>

                    <!-- Overtime Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Hours</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($overtimes as $overtime)
                                    <tr>
                                        <td>{{ $overtime->staff->first_name ?? 'N/A' }} {{ $overtime->staff->last_name ?? '' }}</td>
                                        <td>{{ $overtime->date ?? 'N/A' }}</td>
                                        <td>{{ $overtime->start_time ?? 'N/A' }}</td>
                                        <td>{{ $overtime->end_time ?? 'N/A' }}</td>
                                        <td>{{ $overtime->hours ?? 0 }}</td>
                                        <td>{{ $overtime->rate ?? 0 }}</td>
                                        <td>{{ $overtime->amount ?? 0 }}</td>
                                        <td>{{ Str::limit($overtime->reason ?? '', 30) }}</td>
                                        <td>
                                            <span class="badge bg-{{ $overtime->status == 'approved' ? 'success' : ($overtime->status == 'rejected' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($overtime->status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('hr.overtime.show', $overtime->id) }}" class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('hr.overtime.edit', $overtime->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('hr.overtime.destroy', $overtime->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No overtime records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($overtimes, 'hasPages') && $overtimes->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $overtimes->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
