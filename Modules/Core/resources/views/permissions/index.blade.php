@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0"><i class="fas fa-key"></i> Permissions</h1>
            <a href="{{ route('core.permissions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Permission
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Display Name</th>
                        <th>Description</th>
                        <th>Module</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->display_name ?? $permission->name }}</td>
                        <td>{{ $permission->description ?? 'No description' }}</td>
                        <td>{{ $permission->module ?? 'Core' }}</td>
                        <td>
                            <a href="{{ route('core.permissions.show', $permission->id) }}" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('core.permissions.edit', $permission->id) }}" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $permissions->links() }}
        </div>
    </div>
@endsection 