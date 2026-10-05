@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Communication Groups</h1>
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="general" {{ request('type') == 'general' ? 'selected' : '' }}>General</option>
                <option value="academic" {{ request('type') == 'academic' ? 'selected' : '' }}>Academic</option>
                <option value="staff" {{ request('type') == 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="students" {{ request('type') == 'students' ? 'selected' : '' }}>Students</option>
                <option value="parents" {{ request('type') == 'parents' ? 'selected' : '' }}>Parents</option>
            </select>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search groups...">
            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
        </form>
        <a href="{{ route('communication.groups.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Group
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($groups->count())
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>Group Name</th>
                    <th>Type</th>
                    <th>Members</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $group)
                    <tr>
                        <td>
                            <strong>{{ $group->name }}</strong>
                            @if($group->description)
                                <br>
                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($group->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($group->type) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $group->member_count }} members</span>
                        </td>
                        <td>
                            <span class="badge {{ $group->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $group->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $group->creator->name ?? 'Unknown' }}</td>
                        <td>{{ $group->created_at->format('M j, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('communication.groups.show', $group) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('communication.groups.edit', $group) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('communication.groups.toggle-status', $group) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-toggle-{{ $group->is_active ? 'off' : 'on' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('communication.groups.destroy', $group) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this group?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $groups->links() }}
@else
    <div class="text-center py-5">
        <i class="fas fa-users fa-3x text-muted mb-3"></i>
        <h4>No groups found</h4>
        <p class="text-muted">Create your first communication group to get started.</p>
        <a href="{{ route('communication.groups.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Group
        </a>
    </div>
@endif
@endsection
