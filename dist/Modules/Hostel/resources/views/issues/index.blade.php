@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Hostel Issues</h1>
    <a href="{{ route('hostel.issues.create') }}" class="btn btn-primary mb-3">Report Issue</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-3">
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="priority" class="form-control" onchange="this.form.submit()">
                <option value="">All Priorities</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="room_id" class="form-control" onchange="this.form.submit()">
                <option value="">All Rooms</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-secondary w-100">Filter</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Issue Type</th>
                <th>Room</th>
                <th>Bed</th>
                <th>Student</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($issues as $issue)
                <tr>
                    <td>{{ $issue->issue_type }}</td>
                    <td>{{ $issue->room->name ?? 'N/A' }}</td>
                    <td>{{ $issue->bed->bed_number ?? 'N/A' }}</td>
                    <td>{{ $issue->student->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($issue->status) }}</td>
                    <td>{{ ucfirst($issue->priority) }}</td>
                    <td>
                        <a href="{{ route('hostel.issues.show', $issue) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('hostel.issues.edit', $issue) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('hostel.issues.destroy', $issue) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $issues->links() }}
</div>
@endsection
