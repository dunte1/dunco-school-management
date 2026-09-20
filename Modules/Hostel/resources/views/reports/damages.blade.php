@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Damage/Fine Reports</h1>

    <form method="GET" class="row mb-3 mt-4">
        <div class="col-md-3">
            <select name="hostel_id" class="form-control">
                <option value="">All Hostels</option>
                @foreach($allHostels as $hostel)
                    <option value="{{ $hostel->id }}" {{ request('hostel_id') == $hostel->id ? 'selected' : '' }}>
                        {{ $hostel->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-control">
                <option value="">All Status</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('hostel.reports.damage') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <table class="table table-bordered table-striped mt-3">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Room</th>
                <th>Bed</th>
                <th>Issue Type</th>
                <th>Student</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th>Resolution Notes</th>
                <th>Reported On</th>
                <th>Resolved At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($issues as $issue)
                <tr>
                    <td>{{ $issue->id }}</td>
                    <td>{{ $issue->room->name ?? 'N/A' }}</td>
                    <td>{{ $issue->bed->bed_number ?? 'N/A' }}</td>
                    <td>{{ ucfirst($issue->issue_type ?? 'N/A') }}</td>
                    <td>{{ $issue->student->name ?? 'N/A' }}</td>
                    <td>{{ $issue->assignedTo->name ?? 'Unassigned' }}</td>
                    <td>
                        <span class="badge badge-{{ in_array($issue->status, ['resolved', 'closed']) ? 'success' : ($issue->status === 'in_progress' ? 'primary' : 'danger') }}">
                            {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                        </span>
                    </td>
                    <td>{{ Str::limit($issue->resolution_notes, 50) ?? '-' }}</td>
                    <td>{{ $issue->created_at->format('M d, Y') }}</td>
                    <td>{{ $issue->resolved_at ? $issue->resolved_at->format('M d, Y') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted">No damage/fine reports found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $issues->links() }}
</div>
@endsection
