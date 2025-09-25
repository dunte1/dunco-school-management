@extends('layouts.app')

@section('content')
<h2>Leave Applications</h2>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-3">
        <select name="staff_id" class="form-select">
            <option value="">All Staff</option>
            @foreach($staff as $s)
                <option value="{{ $s->id }}" {{ request('staff_id') == $s->id ? 'selected' : '' }}>{{ $s->first_name }} {{ $s->last_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="type" class="form-select">
            <option value="">All Types</option>
            @foreach($types as $type)
                <option value="{{ $type->name }}" {{ request('type') == $type->name ? 'selected' : '' }}>{{ $type->name }}</option>
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
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('hr.leave.create') }}" class="btn btn-success">Apply for Leave</a>
    </div>
</form>
<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Staff</th>
            <th>Type</th>
            <th>Start</th>
            <th>End</th>
            <th>Days</th>
            <th>Status</th>
            <th>Reason</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($leaves as $leave)
        <tr>
            <td>{{ $leave->staff->first_name }} {{ $leave->staff->last_name }}</td>
            <td>{{ $leave->type }}</td>
            <td>{{ $leave->start_date }}</td>
            <td>{{ $leave->end_date }}</td>
            <td>{{ $leave->days }}</td>
            <td><span class="badge bg-{{ $leave->status == 'approved' ? 'success' : ($leave->status == 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($leave->status) }}</span></td>
            <td>{{ $leave->reason }}</td>
            <td>
                @if($leave->status == 'pending')
                    <form action="{{ route('hr.leave.approve', $leave->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                    </form>
                    <form action="{{ route('hr.leave.reject', $leave->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $leaves->links() }}
@endsection 