@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Beds</h1>
    <a href="{{ route('hostel.beds.create') }}" class="btn btn-primary mb-3">Add Bed</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Bed Number</th>
                <th>Room</th>
                <th>Hostel</th>
                <th>Status</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($beds as $bed)
                <tr>
                    <td>{{ $bed->bed_number }}</td>
                    <td>{{ $bed->room->name ?? 'N/A' }}</td>
                    <td>{{ $bed->room->hostel->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($bed->status) }}</td>
                    <td>{{ $bed->price }}</td>
                    <td>
                        <a href="{{ route('hostel.beds.show', $bed) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('hostel.beds.edit', $bed) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('hostel.beds.destroy', $bed) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $beds->links() }}
</div>
@endsection 