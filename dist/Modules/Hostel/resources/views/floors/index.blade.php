@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Floors</h1>
    <a href="{{ route('hostel.floors.create') }}" class="btn btn-primary mb-3">Add Floor</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Block</th>
                <th>Hostel</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($floors as $floor)
                <tr>
                    <td>{{ $floor->name }}</td>
                    <td>{{ $floor->block }}</td>
                    <td>{{ $floor->hostel->name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('hostel.floors.show', $floor) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('hostel.floors.edit', $floor) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('hostel.floors.destroy', $floor) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $floors->links() }}
</div>
@endsection 