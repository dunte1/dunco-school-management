@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Room Allocations</h1>
    <a href="{{ route('hostel.room_allocations.create') }}" class="btn btn-primary mb-3">Allocate Room</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="GET" class="row g-3 mb-3">
        <div class="col-md-3">
            <select name="hostel_id" class="form-control" onchange="this.form.submit()">
                <option value="">All Hostels</option>
                @foreach($hostels as $hostel)
                    <option value="{{ $hostel->id }}" {{ request('hostel_id') == $hostel->id ? 'selected' : '' }}>{{ $hostel->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="student_id" class="form-control" onchange="this.form.submit()">
                <option value="">All Students</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="checked_out" {{ request('status') == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                <option value="swapped" {{ request('status') == 'swapped' ? 'selected' : '' }}>Swapped</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-secondary w-100">Filter</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Student</th>
                <th>Bed</th>
                <th>Room</th>
                <th>Hostel</th>
                <th>Status</th>
                <th>Check In</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allocations as $allocation)
                <tr>
                    <td>{{ $allocation->student->name ?? 'N/A' }}</td>
                    <td>{{ $allocation->bed->bed_number ?? 'N/A' }}</td>
                    <td>{{ $allocation->bed->room->name ?? 'N/A' }}</td>
                    <td>{{ $allocation->bed->room->hostel->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($allocation->status) }}</td>
                    <td>{{ $allocation->check_in }}</td>
                    <td>
                        <a href="{{ route('hostel.room_allocations.show', $allocation) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('hostel.room_allocations.edit', $allocation) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('hostel.room_allocations.destroy', $allocation) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $allocations->links() }}
</div>
@endsection
