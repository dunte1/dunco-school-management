@extends('layouts.app')

@section('content')
<h2>Edit Role</h2>
<form method="POST" action="{{ route('hr.roles.update', $role->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Role Name</label>
        <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <input type="text" name="description" class="form-control" value="{{ $role->description }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Permissions</label>
        <select name="permissions[]" class="form-select" multiple>
            @foreach($permissions as $perm)
                <option value="{{ $perm->id }}" {{ $role->permissions->contains($perm->id) ? 'selected' : '' }}>{{ $perm->name }}</option>
            @endforeach
        </select>
        <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</small>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('hr.roles.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection 