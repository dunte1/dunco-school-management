@extends('academic::components.layouts.master')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Edit Subject</h1>
    <form method="POST" action="{{ route('academic.subjects.update', $subject->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Subject Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $subject->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Subject Code</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $subject->code) }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $subject->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="credits" class="form-label">Credits</label>
            <input type="number" class="form-control" id="credits" name="credits" min="1" max="10" value="{{ old('credits', $subject->credits) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Subject</button>
        <a href="{{ route('academic.subjects.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection 