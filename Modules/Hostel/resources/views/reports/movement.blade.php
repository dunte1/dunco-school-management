@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Student Movement Logs</h1>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Visitor Name</th>
                <th>Student</th>
                <th>Hostel</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Purpose</th>
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
                    <td>{{ $visitor->purpose }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $visitors->links() }}
</div>
@endsection 