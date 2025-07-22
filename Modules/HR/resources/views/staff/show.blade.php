@extends('layouts.app')

@section('content')
<h2>Staff Profile</h2>
@if($staff->photo)
    <img src="{{ asset($staff->photo) }}" width="100">
@endif
<ul>
    <li><strong>Name:</strong> {{ $staff->first_name }} {{ $staff->last_name }} {{ $staff->other_names }}</li>
    <li><strong>Email:</strong> {{ $staff->email }}</li>
    <li><strong>Phone:</strong> {{ $staff->phone }}</li>
    <li><strong>Gender:</strong> {{ ucfirst($staff->gender) }}</li>
    <li><strong>Date of Birth:</strong> {{ $staff->dob }}</li>
    <li><strong>ID Number:</strong> {{ $staff->id_number }}</li>
    <li><strong>Passport Number:</strong> {{ $staff->passport_number }}</li>
    <li><strong>Address:</strong> {{ $staff->address }}, {{ $staff->city }}, {{ $staff->country }}</li>
    <li><strong>Job Title:</strong> {{ $staff->job_title }}</li>
    <li><strong>Role:</strong> {{ $staff->role->name ?? '' }}</li>
    <li><strong>Department:</strong> {{ $staff->department->name ?? '' }}</li>
    <li><strong>Status:</strong> {{ ucfirst($staff->status) }}</li>
    <li><strong>Emergency Contact:</strong> {{ $staff->emergency_contact_name }} ({{ $staff->emergency_contact_relation }}) - {{ $staff->emergency_contact_phone }}</li>
</ul>
<h3>Documents</h3>
<ul>
    @foreach($staff->documents as $doc)
        <li><a href="{{ asset($doc->file_path) }}" target="_blank">{{ $doc->type }}</a> - {{ $doc->description }}</li>
    @endforeach
</ul>
<form method="POST" action="#" enctype="multipart/form-data">
    @csrf
    <label>Upload Document: <input type="file" name="file"></label>
    <input type="text" name="type" placeholder="Type (CV, Contract, etc.)">
    <input type="text" name="description" placeholder="Description">
    <button type="submit">Upload</button>
</form>
<a href="{{ route('hr.staff.edit', $staff->id) }}">Edit</a> |
<a href="{{ route('hr.staff.index') }}">Back to List</a>
@endsection 