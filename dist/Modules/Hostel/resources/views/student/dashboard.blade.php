@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Hostel Dashboard</h1>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Allocated Room/Bed</div>
                <div class="card-body">
                    @if($allocation)
                        <p><strong>Hostel:</strong> {{ $allocation->bed->room->hostel->name ?? 'N/A' }}</p>
                        <p><strong>Room:</strong> {{ $allocation->bed->room->name ?? 'N/A' }}</p>
                        <p><strong>Bed:</strong> {{ $allocation->bed->bed_number ?? 'N/A' }}</p>
                    @else
                        <p>You have not been allocated a room yet.</p>
                    @endif
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">My Hostel Fees</div>
                <div class="card-body">
                    @if($fees->count())
                        <table class="table table-sm">
                            <thead><tr><th>Amount</th><th>Status</th><th>Due</th><th>Paid</th></tr></thead>
                            <tbody>
                                @foreach($fees as $fee)
                                    <tr>
                                        <td>{{ $fee->amount }}</td>
                                        <td>{{ ucfirst($fee->status) }}</td>
                                        <td>{{ $fee->due_date }}</td>
                                        <td>{{ $fee->paid_at ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No hostel fees found.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">My Maintenance/Room Issues</div>
                <div class="card-body">
                    @if($issues->count())
                        <ul class="list-group">
                            @foreach($issues as $issue)
                                <li class="list-group-item">
                                    <strong>{{ $issue->issue_type }}</strong> ({{ ucfirst($issue->status) }})<br>
                                    {{ $issue->description }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No issues reported.</p>
                    @endif
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">My Leave Requests</div>
                <div class="card-body">
                    @if($leaves->count())
                        <ul class="list-group">
                            @foreach($leaves as $leave)
                                <li class="list-group-item">
                                    <strong>{{ $leave->reason }}</strong> ({{ ucfirst($leave->status) }})<br>
                                    {{ $leave->from_date }} to {{ $leave->to_date }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No leave requests found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">Hostel Announcements</div>
        <div class="card-body">
            @if($announcements->count())
                <ul class="list-group">
                    @foreach($announcements as $announcement)
                        <li class="list-group-item">
                            <strong>{{ $announcement->title }}</strong> ({{ $announcement->published_at }})<br>
                            {{ $announcement->message }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p>No announcements found.</p>
            @endif
        </div>
    </div>
</div>
@endsection 