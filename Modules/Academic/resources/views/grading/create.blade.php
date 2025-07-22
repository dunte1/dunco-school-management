@extends('academic::layouts.app')

@section('content')
<div class="container">
    <h1>Add Grading Scale</h1>
    <form method="POST" action="{{ route('academic.grading.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('academic.grading.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 