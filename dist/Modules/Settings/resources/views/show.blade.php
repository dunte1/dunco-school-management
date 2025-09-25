@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Setting Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Key: {{ $setting->key }}</h5>
            <p class="card-text"><strong>Value:</strong> {{ $setting->value }}</p>
            <p class="card-text"><strong>Type:</strong> {{ $setting->type }}</p>
            <p class="card-text"><strong>Description:</strong> {{ $setting->description }}</p>
            <a href="{{ route('settings.edit', $setting->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('settings.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>
@endsection 