@extends('layouts.app')

@section('content')
<h2>Edit Permission</h2>
<form method="POST" action="{{ route('hr.permissions.update', $permission->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Permission Name</label>
        <input type="text" name="name" class="form-control" value="{{ $permission->name }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <input type="text" name="description" class="form-control" value="{{ $permission->description }}">
    </div>
    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('hr.permissions.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection 