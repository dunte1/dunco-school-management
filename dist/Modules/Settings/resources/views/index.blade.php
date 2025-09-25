@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Settings</h1>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('settings.create') }}" class="btn btn-primary mb-3">Add Setting</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Key</th>
                    <th>Value</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($settings as $setting)
                    <tr>
                        <td>{{ $setting->id }}</td>
                        <td>{{ $setting->key }}</td>
                        <td>{{ $setting->value }}</td>
                        <td>{{ $setting->type }}</td>
                        <td>{{ $setting->description }}</td>
                        <td>
                            <a href="{{ route('settings.show', $setting->id) }}" class="btn btn-info btn-sm">Show</a>
                            <a href="{{ route('settings.edit', $setting->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('settings.destroy', $setting->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $settings->links() }}
    </div>
@endsection
