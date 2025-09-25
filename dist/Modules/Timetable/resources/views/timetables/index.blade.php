@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Timetables</h2>
        <a href="{{ route('timetables.create') }}" class="btn btn-primary">Add Timetable</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Academic Year</th>
                <th>Term</th>
                <th>Level</th>
                <th>Active</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($timetables as $t)
                <tr>
                    <td>{{ $t->name }}</td>
                    <td>{{ $t->academic_year }}</td>
                    <td>{{ $t->term }}</td>
                    <td>{{ $t->school_level }}</td>
                    <td>{!! $t->is_active ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
                    <td>
                        @php
                            $statusColors = [
                                'draft' => 'secondary',
                                'pending' => 'warning',
                                'published' => 'success',
                                'archived' => 'dark',
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$t->status] ?? 'secondary' }} text-uppercase">{{ $t->status }}</span>
                    </td>
                    <td>
                        <a href="{{ route('timetables.edit', $t->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('timetables.destroy', $t->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this timetable?')">Delete</button>
                        </form>
                        {{-- Workflow actions --}}
                        @if($t->status === 'draft')
                            <form action="{{ route('timetables.submit', $t->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-info">Submit for Approval</button>
                            </form>
                            <form action="{{ route('timetables.publish', $t->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Publish</button>
                            </form>
                        @elseif($t->status === 'pending')
                            <form action="{{ route('timetables.approve', $t->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>
                            <form action="{{ route('timetables.reject', $t->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                            </form>
                        @elseif($t->status === 'published')
                            <form action="{{ route('timetables.archive', $t->id) }}" method="POST" style="display:inline-block">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-dark">Archive</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $timetables->links() }}
</div>
@endsection 