@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Setting Details</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    <div class="card">
        <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="card-title">Setting Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th>ID:</th>
                                <td>{{ $setting->id }}</td>
                            </tr>
                            <tr>
                                <th>Key:</th>
                                <td>{{ $setting->key }}</td>
                            </tr>
                            <tr>
                                <th>Value:</th>
                                <td>{{ $setting->value }}</td>
                            </tr>
                            <tr>
                                <th>Type:</th>
                                <td>{{ $setting->type }}</td>
                            </tr>
                            <tr>
                                <th>Description:</th>
                                <td>{{ $setting->description ?? 'No description' }}</td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>{{ $setting->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Updated:</th>
                                <td>{{ $setting->updated_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="mt-3">
            <a href="{{ route('settings.edit', $setting->id) }}" class="btn btn-warning">Edit</a>
                    <a href="{{ route('settings.index') }}" class="btn btn-secondary">Back to List</a>
                    <form action="{{ route('settings.destroy', $setting->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this setting?')">Delete</button>
                    </form>
                </div>
        </div>
    </div>
</div>
@endsection 