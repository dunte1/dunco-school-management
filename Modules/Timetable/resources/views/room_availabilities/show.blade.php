@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Room Availability Details</h2>
        <div>
            <a href="{{ route('room_availabilities.edit', $availability->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('room_availabilities.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">Availability Information</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th>ID:</th>
                            <td>{{ $availability->id }}</td>
                        </tr>
                        <tr>
                            <th>Room:</th>
                            <td>{{ $availability->room->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Day of Week:</th>
                            <td><span class="badge bg-primary">{{ $availability->day_of_week }}</span></td>
                        </tr>
                        <tr>
                            <th>Start Time:</th>
                            <td><span class="badge bg-success">{{ $availability->start_time }}</span></td>
                        </tr>
                        <tr>
                            <th>End Time:</th>
                            <td><span class="badge bg-danger">{{ $availability->end_time }}</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5 class="card-title">Room Details</h5>
                    @if($availability->room)
                        <table class="table table-borderless">
                            <tr>
                                <th>Room Name:</th>
                                <td>{{ $availability->room->name }}</td>
                            </tr>
                            <tr>
                                <th>Capacity:</th>
                                <td>{{ $availability->room->capacity }}</td>
                            </tr>
                            <tr>
                                <th>Location:</th>
                                <td>{{ $availability->room->location ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Type:</th>
                                <td>{{ $availability->room->type ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Equipment:</th>
                                <td>
                                    @if($availability->room->equipment)
                                        @foreach(explode(',', $availability->room->equipment) as $item)
                                            <span class="badge bg-info text-dark me-1">{{ trim($item) }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @else
                        <p class="text-muted">Room information not available.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <form action="{{ route('room_availabilities.destroy', $availability->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this room availability?')">Delete</button>
        </form>
    </div>
</div>
@endsection
