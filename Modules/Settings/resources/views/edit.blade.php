@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Setting</h1>
    <form action="{{ route('settings.update', $setting->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="key" class="form-label">Key</label>
            <input type="text" name="key" id="key" class="form-control" value="{{ old('key', $setting->key) }}" required>
            @error('key')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="value" class="form-label">Value</label>
            <textarea name="value" id="value" class="form-control" required>{{ old('value', $setting->value) }}</textarea>
            @error('value')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <input type="text" name="type" id="type" class="form-control" value="{{ old('type', $setting->type) }}" required>
            @error('type')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control">{{ old('description', $setting->description) }}</textarea>
            @error('description')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('settings.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 