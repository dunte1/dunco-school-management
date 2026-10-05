@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Maintenance Requests</h2>
        <a href="{{ route('hostel.maintenance.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Request
        </a>
    </div>

    <!-- Search and Filter Form -->
    <form method="GET" action="{{ route('hostel.maintenance.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search requests..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="priority" class="form-control">
                    <option value="">All Priority</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary me-2">Filter</button>
                <a href="{{ route('hostel.maintenance.index') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Room/Hostel</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($maintenanceRequests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>{{ $request->title }}</td>
                            <td>
                                @if($request->room)
                                    {{ $request->room->name }} ({{ $request->room->hostel->name }})
                                @else
                                    {{ $request->hostel->name }}
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $request->priority == 'urgent' ? 'danger' : ($request->priority == 'high' ? 'warning' : ($request->priority == 'medium' ? 'info' : 'secondary')) }}">
                                    {{ ucfirst($request->priority) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $request->status == 'completed' ? 'success' : ($request->status == 'in_progress' ? 'warning' : ($request->status == 'cancelled' ? 'danger' : 'secondary')) }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td>{{ $request->requested_by_name }}</td>
                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('hostel.maintenance.show', $request) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('hostel.maintenance.edit', $request) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No maintenance requests found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $maintenanceRequests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
