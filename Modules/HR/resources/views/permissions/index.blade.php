@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-start" style="min-height: 80vh;">
    <div class="w-100" style="max-width: 900px;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm">
                <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Permissions</li>
            </ol>
        </nav>
        <div class="card shadow rounded-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0"><i class="fas fa-key"></i> Permissions</h2>
                    <a href="{{ route('hr.permissions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Permission
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
    <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($permissions as $perm)
        <tr>
            <td>{{ $perm->name }}</td>
            <td>{{ $perm->description }}</td>
            <td>
                                    <a href="{{ route('hr.permissions.edit', $perm->id) }}" class="btn btn-sm btn-warning me-1" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                <form action="{{ route('hr.permissions.destroy', $perm->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="return confirm('Delete this permission?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
                </div>
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
</script>
@endsection 