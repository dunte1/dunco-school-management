@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Hostel Fees</h1>
    <a href="{{ route('hostel.fees.create') }}" class="btn btn-primary mb-3">Add Fee</a>
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
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
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
                <th>Amount</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fees as $fee)
                <tr>
                    <td>{{ $fee->student->name ?? 'N/A' }}</td>
                    <td>{{ $fee->bed->bed_number ?? 'N/A' }}</td>
                    <td>{{ $fee->room->name ?? 'N/A' }}</td>
                    <td>{{ $fee->hostel->name ?? 'N/A' }}</td>
                    <td>{{ $fee->amount }}</td>
                    <td>{{ ucfirst($fee->status) }}</td>
                    <td>{{ $fee->due_date }}</td>
                    <td>
                        <a href="{{ route('hostel.fees.show', $fee) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('hostel.fees.edit', $fee) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('hostel.fees.destroy', $fee) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $fees->links() }}
</div>
@endsection
