@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Hostel Announcements</h1>
    <a href="{{ route('hostel.announcements.create') }}" class="btn btn-primary mb-3">Create Announcement</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-4">
            <select name="hostel_id" class="form-control" onchange="this.form.submit()">
                <option value="">All Hostels</option>
                @foreach($hostels as $hostel)
                    <option value="{{ $hostel->id }}" {{ request('hostel_id') == $hostel->id ? 'selected' : '' }}>{{ $hostel->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <select name="audience" class="form-control" onchange="this.form.submit()">
                <option value="">All Audiences</option>
                <option value="all" {{ request('audience') == 'all' ? 'selected' : '' }}>All</option>
                <option value="residents" {{ request('audience') == 'residents' ? 'selected' : '' }}>Residents</option>
                <option value="staff" {{ request('audience') == 'staff' ? 'selected' : '' }}>Staff</option>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-secondary w-100">Filter</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Hostel</th>
                <th>Warden</th>
                <th>Audience</th>
                <th>Published</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($announcements as $announcement)
                <tr>
                    <td>{{ $announcement->title }}</td>
                    <td>{{ $announcement->hostel->name ?? 'N/A' }}</td>
                    <td>{{ $announcement->warden->user->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($announcement->audience) }}</td>
                    <td>{{ $announcement->published_at }}</td>
                    <td>
                        <a href="{{ route('hostel.announcements.show', $announcement) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('hostel.announcements.edit', $announcement) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('hostel.announcements.destroy', $announcement) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $announcements->links() }}
</div>
@endsection
