@extends('hr::layouts.app')

@section('content')
<h2>Add Permission</h2>
<form method="POST" action="{{ route('hr.permissions.store') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Permission Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <input type="text" name="description" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Save</button>
    <a href="{{ route('hr.permissions.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection 