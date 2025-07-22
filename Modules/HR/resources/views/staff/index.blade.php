@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Staff List</h2>
        <a href="{{ route('hr.staff.create') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i> Add Staff</a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="role_id" class="form-select">
        <option value="">All Roles</option>
        @foreach($roles as $role)
            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
        @endforeach
    </select>
                </div>
                <div class="col-md-3">
                    <select name="department_id" class="form-select">
        <option value="">All Departments</option>
        @foreach($departments as $department)
            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
        @endforeach
    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
                </div>
</form>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-light">
        <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Department</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
                        @forelse($staff as $member)
        <tr>
                            <td>@if($member->photo)<img src="{{ asset($member->photo) }}" width="40" class="rounded-circle">@endif</td>
            <td>{{ $member->first_name }} {{ $member->last_name }}</td>
            <td>{{ $member->email }}</td>
            <td>{{ $member->role->name ?? '' }}</td>
            <td>{{ $member->department->name ?? '' }}</td>
                            <td><span class="badge bg-{{ $member->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($member->status) }}</span></td>
            <td>
                                <a href="{{ route('hr.staff.show', $member->id) }}" class="btn btn-sm btn-info" title="View"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('hr.staff.edit', $member->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                <form action="{{ route('hr.staff.destroy', $member->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this staff?')" title="Delete"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No staff found.</td>
                        </tr>
                        @endforelse
    </tbody>
</table>
            </div>
        </div>
        <div class="card-footer">
{{ $staff->links() }}
        </div>
    </div>
</div>
@endsection 