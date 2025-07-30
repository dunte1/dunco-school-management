@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Hostels</h1>
    <a href="{{ route('hostel.hostels.create') }}" class="btn btn-primary mb-3">Add Hostel</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Gender Restriction</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hostels as $hostel)
                <tr>
                    <td>{{ $hostel->name }}</td>
                    <td>{{ $hostel->location }}</td>
                    <td>{{ ucfirst($hostel->gender_restriction) }}</td>
                    <td>
                        <a href="{{ route('hostel.show', $hostel) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('hostel.edit', $hostel) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('hostel.destroy', $hostel) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $hostels->links() }}
</div>
@endsection 