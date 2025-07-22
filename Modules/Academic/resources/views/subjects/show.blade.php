@extends('academic::components.layouts.master')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Subject Details</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title">{{ $subject->name }}</h4>
            <p class="card-text"><strong>Code:</strong> {{ $subject->code }}</p>
            <p class="card-text"><strong>Description:</strong> {{ $subject->description }}</p>
            <p class="card-text"><strong>Credits:</strong> {{ $subject->credits }}</p>
            <p class="card-text"><strong>Status:</strong> {{ $subject->is_active ? 'Active' : 'Inactive' }}</p>
        </div>
    </div>
    <a href="{{ route('academic.subjects.edit', $subject->id) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('academic.subjects.index') }}" class="btn btn-secondary ms-2">Back to List</a>
</div>
@endsection 