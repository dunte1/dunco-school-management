@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Announcement Details</h1>
    <div class="card mb-3">
        <div class="card-body">
            <h4 class="card-title">{{ $hostelAnnouncement->title }}</h4>
            <p><strong>Hostel:</strong> {{ $hostelAnnouncement->hostel->name ?? 'N/A' }}</p>
            <p><strong>Warden:</strong> {{ $hostelAnnouncement->warden->user->name ?? 'N/A' }}</p>
            <p><strong>Audience:</strong> {{ ucfirst($hostelAnnouncement->audience) }}</p>
            <p><strong>Published At:</strong> {{ $hostelAnnouncement->published_at }}</p>
            <p><strong>Message:</strong> {{ $hostelAnnouncement->message }}</p>
            <p><strong>Attachment:</strong> {{ $hostelAnnouncement->attachment }}</p>
        </div>
    </div>
    <a href="{{ route('hostel.announcements.edit', $hostelAnnouncement) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('hostel.announcements.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
