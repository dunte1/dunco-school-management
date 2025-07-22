@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-start" style="min-height: 80vh;">
        <div class="w-100" style="max-width: 1100px;">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm">
                    <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Users</li>
                </ol>
            </nav>
            <div class="card shadow rounded-4">
                <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0"><i class="fas fa-users"></i> Users</h1>
            <a href="{{ route('core.users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add User
            </a>
        </div>
        <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Avatar</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Status</th>
                        <th>School</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="rounded-circle" width="40" height="40">
                            @else
                                <div class="bg-light rounded-circle text-center" style="width:40px;height:40px;line-height:40px;">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge bg-secondary">{{ $role->display_name ?? $role->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>{{ optional($user->school)->name ?? 'N/A' }}</td>
                        <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : '' }}</td>
                        <td>
                                        <a href="{{ route('core.users.show', $user->id) }}" class="btn btn-sm btn-info me-1" title="View" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('core.users.edit', $user->id) }}" class="btn btn-sm btn-warning me-1" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" title="Delete" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                            <form id="delete-form-{{ $user->id }}" action="{{ route('core.users.destroy', $user->id) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteUserModalLabel">Confirm Delete</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete <strong id="deleteUserName"></strong>?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <style>
    .table td, .table th {
        vertical-align: middle;
    }
    .table-hover tbody tr:hover {
        background-color: #f1f3f6;
        transition: background 0.2s;
    }
    .breadcrumb {
        background: #fff;
        font-size: 1rem;
    }
    .card {
        border-radius: 1.5rem;
    }
    </style>
    <script>
        // Enable Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Delete confirmation modal
        let userIdToDelete = null;
        function confirmDelete(userId, userName) {
            userIdToDelete = userId;
            document.getElementById('deleteUserName').textContent = userName;
            let modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
            modal.show();
            document.getElementById('confirmDeleteBtn').onclick = function() {
                document.getElementById('delete-form-' + userIdToDelete).submit();
            };
        }
        window.confirmDelete = confirmDelete;
    </script>
@endsection 