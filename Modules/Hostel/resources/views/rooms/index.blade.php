@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Rooms</h1>
    <a href="{{ route('hostel.rooms.create') }}" class="btn btn-primary mb-3">Add Room</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Hostel</th>
                <th>Floor</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
                <tr>
                    <td>{{ $room->name }}</td>
                    <td>{{ ucfirst($room->type) }}</td>
                    <td>{{ $room->hostel->name ?? 'N/A' }}</td>
                    <td>{{ $room->floor->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($room->status) }}</td>
                    <td>
                        <a href="{{ route('hostel.rooms.show', $room) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('hostel.rooms.edit', $room) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('hostel.rooms.destroy', $room) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $rooms->links() }}
</div>
@endsection 