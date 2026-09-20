@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Room Allocation Report</h1>

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
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="checked_out" {{ request('status') == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                <option value="swapped" {{ request('status') == 'swapped' ? 'selected' : '' }}>Swapped</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="From">
        </div>
        <div class="col-md-2">
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="To">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('hostel.reports.allocation') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <table class="table table-bordered table-striped mt-3">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Student</th>
                <th>Hostel</th>
                <th>Room</th>
                <th>Bed</th>
                <th>Type</th>
                <th>Status</th>
                <th>Check In</th>
                <th>Check Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allocations as $allocation)
                <tr>
                    <td>{{ $allocation->id }}</td>
                    <td>{{ $allocation->student->name ?? 'N/A' }}</td>
                    <td>{{ $allocation->bed->room->hostel->name ?? 'N/A' }}</td>
                    <td>{{ $allocation->bed->room->name ?? 'N/A' }}</td>
                    <td>{{ $allocation->bed->bed_number ?? 'N/A' }}</td>
                    <td>{{ ucfirst($allocation->allocation_type ?? 'N/A') }}</td>
                    <td>
                        <span class="badge badge-{{ $allocation->status === 'active' ? 'success' : ($allocation->status === 'checked_out' ? 'secondary' : 'warning') }}">
                            {{ ucfirst($allocation->status) }}
                        </span>
                    </td>
                    <td>{{ $allocation->check_in ? $allocation->check_in->format('M d, Y') : '-' }}</td>
                    <td>{{ $allocation->check_out ? $allocation->check_out->format('M d, Y') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">No allocations found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $allocations->links() }}
</div>
@endsection
