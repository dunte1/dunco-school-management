@extends('core::layouts.master')

@section('title', 'Create Role')

@section('content')
<div class="container">
    <h1 class="mb-4">Create Role</h1>
    <form method="POST" action="{{ route('core.roles.store') }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label for="display_name" class="form-label">Display Name</label>
            <input type="text" class="form-control" id="display_name" name="display_name" value="{{ old('display_name') }}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label for="permissions" class="form-label">Permissions</label>
            <select class="form-select" id="permissions" name="permissions[]" multiple>
                @foreach($permissions as $permission)
                    <option value="{{ $permission->id }}">{{ $permission->display_name ?? $permission->name }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple permissions.</small>
        </div>
        <button type="submit" class="btn btn-primary">Create Role</button>
        <a href="{{ route('core.roles.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 