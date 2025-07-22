@extends('academic::components.layouts.master')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Create Subject</h1>
    <form method="POST" action="{{ route('academic.subjects.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Subject Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="code" class="form-label">Subject Code</label>
            <input type="text" class="form-control" id="code" name="code" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="mb-3">
            <label for="credits" class="form-label">Credits</label>
            <input type="number" class="form-control" id="credits" name="credits" min="1" max="10" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Subject</button>
        <a href="{{ route('academic.subjects.index') }}" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
@endsection 