@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Setting</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <form action="{{ route('settings.update', $setting->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="key" class="form-label">Key</label>
                <input type="text" class="form-control @error('key') is-invalid @enderror" id="key" name="key" value="{{ old('key', $setting->key) }}" required>
                @error('key')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="value" class="form-label">Value</label>
                <textarea class="form-control @error('value') is-invalid @enderror" id="value" name="value" rows="3">{{ old('value', $setting->value) }}</textarea>
                @error('value')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                    <option value="">Select Type</option>
                    <option value="string" {{ old('type', $setting->type) == 'string' ? 'selected' : '' }}>String</option>
                    <option value="integer" {{ old('type', $setting->type) == 'integer' ? 'selected' : '' }}>Integer</option>
                    <option value="boolean" {{ old('type', $setting->type) == 'boolean' ? 'selected' : '' }}>Boolean</option>
                    <option value="json" {{ old('type', $setting->type) == 'json' ? 'selected' : '' }}>JSON</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $setting->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update Setting</button>
            <a href="{{ route('settings.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection 