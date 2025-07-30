@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Damage/Fine Reports</h1>
    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Issue Type</th>
                <th>Room</th>
                <th>Bed</th>
                <th>Student</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Resolution Notes</th>
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
                    <td>{{ $issue->resolution_notes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $issues->links() }}
</div>
@endsection 