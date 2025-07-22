@extends('layouts.app')

@section('content')
<h2>Roles</h2>
<a href="{{ route('hr.roles.create') }}" class="btn btn-primary mb-3">Add Role</a>
<table class="table table-bordered table-hover">
    <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Permissions</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $role)
        <tr>
            <td>{{ $role->name }}</td>
            <td>{{ $role->description }}</td>
            <td>
                @foreach($role->permissions as $perm)
                    <span class="badge bg-info text-dark">{{ $perm->name }}</span>
                @endforeach
            </td>
            <td>
                <a href="{{ route('hr.roles.edit', $role->id) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('hr.roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this role?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection 