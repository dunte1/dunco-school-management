@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Visitor Logs</h1>
    <a href="{{ route('hostel.visitors.create') }}" class="btn btn-primary mb-3">Log Visitor Entry</a>
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
            <input type="date" name="date" class="form-control" value="{{ request('date') }}" onchange="this.form.submit()">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-secondary w-100">Filter</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Visitor Name</th>
                <th>Student</th>
                <th>Hostel</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($visitors as $visitor)
                <tr>
                    <td>{{ $visitor->visitor_name }}</td>
                    <td>{{ $visitor->student->name ?? 'N/A' }}</td>
                    <td>{{ $visitor->hostel->name ?? 'N/A' }}</td>
                    <td>{{ $visitor->time_in }}</td>
                    <td>{{ $visitor->time_out ?? '-' }}</td>
                    <td>
                        <a href="{{ route('hostel.visitors.show', $visitor) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('hostel.visitors.edit', $visitor) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('hostel.visitors.destroy', $visitor) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $visitors->links() }}
</div>
@endsection 