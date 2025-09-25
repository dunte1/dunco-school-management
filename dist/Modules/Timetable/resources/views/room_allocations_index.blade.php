@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Room Allocations</h2>
        <div>
            <a href="#" class="btn btn-primary">Add Allocation</a>
            <a href="{{ route('room_allocations.export.csv') }}" class="btn btn-outline-secondary">Export CSV</a>
            <a href="{{ route('room_allocations.export.pdf') }}" class="btn btn-outline-secondary">Export PDF</a>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Room</th>
                <th>Class Schedule</th>
                <th>Allocation Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($allocations as $a)
                <tr>
                    <td>{{ $a->id }}</td>
                    <td>{{ $a->room_id }}</td>
                    <td>{{ $a->class_schedule_id }}</td>
                    <td>{{ $a->allocation_date }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">No room allocations found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 