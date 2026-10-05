@extends('layouts.app')

@section('title', 'Timesheets')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Timesheets</h4>
                    <a href="{{ route('hr.timesheets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Timesheet
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('hr.timesheets.index') }}" class="mb-4">
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
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('hr.timesheets.index') }}" class="btn btn-secondary">Reset</a>
                    </form>

                    <!-- Timesheets Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Week Starting</th>
                                    <th>Total Hours</th>
                                    <th>Overtime Hours</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($timesheets as $timesheet)
                                    <tr>
                                        <td>{{ $timesheet->staff->first_name ?? 'N/A' }} {{ $timesheet->staff->last_name ?? '' }}</td>
                                        <td>{{ $timesheet->week_starting ?? 'N/A' }}</td>
                                        <td>{{ $timesheet->total_hours ?? 0 }}</td>
                                        <td>{{ $timesheet->overtime_hours ?? 0 }}</td>
                                        <td>
                                            <span class="badge bg-{{ $timesheet->status == 'approved' ? 'success' : ($timesheet->status == 'rejected' ? 'danger' : ($timesheet->status == 'submitted' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst($timesheet->status ?? 'draft') }}
                                            </span>
                                        </td>
                                        <td>{{ $timesheet->created_at ? $timesheet->created_at->format('M d, Y') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('hr.timesheets.show', $timesheet->id) }}" class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('hr.timesheets.edit', $timesheet->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                            <form action="{{ route('hr.timesheets.destroy', $timesheet->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No timesheets found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(method_exists($timesheets, 'hasPages') && $timesheets->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $timesheets->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
