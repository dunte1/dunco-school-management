@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Warden Dashboard</h1>
    @if($warden)
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">Assigned Hostel</div>
                    <div class="card-body">
                        @foreach($hostels as $hostel)
                            <p><strong>{{ $hostel->name }}</strong></p>
                            <p>Rooms: {{ $hostel->rooms->count() }}</p>
                            <p>Beds: {{ $hostel->rooms->flatMap->beds->count() }}</p>
                        @endforeach
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">Occupancy Summary</div>
                    <div class="card-body">
                        @foreach($hostels as $hostel)
                            @foreach($hostel->rooms as $room)
                                <p><strong>Room {{ $room->name }}:</strong> 
                                    {{ $room->beds->where('status', 'occupied')->count() }} occupied / 
                                    {{ $room->beds->count() }} total
                                </p>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header">Pending Allocations</div>
                    <div class="card-body">
                        @if($pendingAllocations->count())
                            <ul class="list-group">
                                @foreach($pendingAllocations as $alloc)
                                    <li class="list-group-item">
                                        Student: {{ $alloc->student->name ?? 'N/A' }}<br>
                                        Room: {{ $alloc->bed->room->name ?? 'N/A' }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No pending allocations.</p>
                        @endif
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">Pending Leave Requests</div>
                    <div class="card-body">
                        @if($pendingLeaves->count())
                            <ul class="list-group">
                                @foreach($pendingLeaves as $leave)
                                    <li class="list-group-item">
                                        Student: {{ $leave->student->name ?? 'N/A' }}<br>
                                        {{ $leave->from_date }} to {{ $leave->to_date }}<br>
                                        Reason: {{ $leave->reason }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No pending leave requests.</p>
                        @endif
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">Open Issues</div>
                    <div class="card-body">
                        @if($openIssues->count())
                            <ul class="list-group">
                                @foreach($openIssues as $issue)
                                    <li class="list-group-item">
                                        {{ $issue->issue_type }} ({{ $issue->priority }})<br>
                                        Room: {{ $issue->room->name ?? 'N/A' }}<br>
                                        {{ $issue->description }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p>No open issues.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">My Announcements</div>
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
    @else
        <div class="alert alert-warning">You are not assigned as a warden to any hostel.</div>
    @endif
</div>
@endsection 