@extends('layouts.app')

@php($errors = $errors ?? app('view')->shared('errors', new \Illuminate\Support\ViewErrorBag))

@section('content')
<style>
    .schools-premium-card {
        background: rgba(255,255,255,0.85);
        border-radius: 1.5rem;
        box-shadow: 0 8px 32px 0 rgba(30,167,255,0.10), 0 1.5px 6px 0 rgba(21,101,192,0.08);
        border: 1.5px solid rgba(30,167,255,0.10);
        padding: 2.2rem 2rem 2rem 2rem;
        margin-bottom: 2rem;
    }
    .schools-header-premium {
        display: flex;
        align-items: center;
        gap: 1.1rem;
        margin-bottom: 1.5rem;
    }
    .schools-header-premium .icon {
        font-size: 2.5rem;
        color: #1a237e;
        background: linear-gradient(135deg, #1ea7ff 0%, #1a237e 100%);
        border-radius: 1rem;
        padding: 0.7rem 1.1rem;
        box-shadow: 0 2px 12px rgba(30,167,255,0.13);
    }
    .schools-header-premium h1 {
        font-weight: 800;
        font-size: 2.1rem;
        color: #1a237e;
        letter-spacing: 1.2px;
        margin-bottom: 0;
        text-shadow: 0 1px 8px rgba(30,167,255,0.10);
    }
    .schools-add-btn {
        border-radius: 1.2rem;
        font-weight: 700;
        font-size: 1.08rem;
        padding: 0.7rem 1.5rem;
        box-shadow: 0 2px 8px rgba(30,167,255,0.08);
        background: linear-gradient(90deg, #1ea7ff 0%, #1565c0 100%);
        color: #fff;
        border: none;
        transition: box-shadow 0.18s, background 0.18s, color 0.18s, transform 0.18s;
    }
    .schools-add-btn:hover, .schools-add-btn:focus {
        box-shadow: 0 8px 24px rgba(30,167,255,0.13);
        background: linear-gradient(90deg, #1565c0 0%, #1ea7ff 100%);
        color: #fff;
        transform: translateY(-2px) scale(1.04);
        z-index: 2;
    }
    .schools-table-premium {
        border-radius: 1.2rem;
        overflow: hidden;
        background: rgba(255,255,255,0.97);
        box-shadow: 0 4px 16px rgba(30,167,255,0.08);
        border: 1.5px solid rgba(30,167,255,0.08);
    }
    .schools-table-premium th {
        background: linear-gradient(90deg, #e3e9f7 0%, #f8fbff 100%);
        font-weight: 700;
        color: #1a237e;
        font-size: 1.05rem;
        border-bottom: 2px solid #e3e9f7;
    }
    .schools-table-premium td {
        vertical-align: middle;
        font-size: 1.04rem;
        background: transparent;
    }
    .schools-table-premium tbody tr:hover {
        background: #e3f2fd;
        transition: background 0.2s;
    }
    .schools-action-btn {
        border-radius: 0.7rem;
        font-size: 1.1rem;
        margin-right: 0.2rem;
        transition: background 0.18s, color 0.18s, transform 0.18s;
        box-shadow: 0 1px 4px rgba(30,167,255,0.08);
    }
    .schools-action-btn.view { background: #1ea7ff; color: #fff; }
    .schools-action-btn.edit { background: #ffb300; color: #fff; }
    .schools-action-btn.delete { background: #e53935; color: #fff; }
    .schools-action-btn:hover { opacity: 0.85; transform: scale(1.08); }
    .schools-breadcrumb {
        background: rgba(255,255,255,0.92);
        border-radius: 0.8rem;
        box-shadow: 0 2px 8px rgba(30,167,255,0.06);
        font-size: 1.08rem;
        margin-bottom: 1.2rem;
        padding: 0.7rem 1.2rem;
    }
    @media (max-width: 767.98px) {
        .schools-premium-card { padding: 1.2rem 0.5rem; }
        .schools-header-premium h1 { font-size: 1.3rem; }
        .schools-header-premium .icon { font-size: 1.3rem; padding: 0.4rem 0.7rem; }
    }
</style>
<div class="schools-premium-card">
    <div class="schools-header-premium mb-3">
        <span class="icon"><i class="fas fa-school"></i></span>
        <h1>Schools</h1>
        <button class="schools-add-btn ms-auto" data-bs-toggle="modal" data-bs-target="#addSchoolModal">
            <i class="fas fa-plus"></i> Add School
        </button>
    </div>
    <nav aria-label="breadcrumb" class="schools-breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Schools</li>
        </ol>
    </nav>
    <div class="table-responsive schools-table-premium mt-3">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Domain</th>
                    <th>Status</th>
                    <th>Users</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schools as $school)
                <tr>
                    <td>
                        @if($school->logo)
                            <img src="{{ asset('storage/' . $school->logo) }}" alt="Logo" class="rounded" width="40" height="40">
                        @else
                            <div class="bg-light rounded text-center" style="width:40px;height:40px;line-height:40px;">
                                <i class="fas fa-school text-muted"></i>
                            </div>
                        @endif
                    </td>
                    <td>{{ $school->id }}</td>
                    <td>{{ $school->name }}</td>
                    <td><span class="badge bg-secondary">{{ $school->code }}</span></td>
                    <td>{{ $school->domain }}</td>
                    <td>
                        @if($school->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-info">{{ optional($school->users)->count() ?? 0 }}</span>
                    </td>
                    <td>{{ $school->created_at ? $school->created_at->format('M d, Y') : '' }}</td>
                    <td>
                        <a href="{{ route('core.schools.show', $school->id) }}" class="schools-action-btn view" title="View" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('core.schools.edit', $school->id) }}" class="schools-action-btn edit" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="schools-action-btn delete" title="Delete" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="confirmDelete({{ $school->id }}, '{{ $school->name }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form id="delete-form-{{ $school->id }}" action="{{ route('core.schools.destroy', $school->id) }}" method="POST" style="display:none;">
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
        {{ $schools->links() }}
    </div>
</div>
@include('core::schools.modals')
<script>
    // Delete confirmation modal
    let schoolIdToDelete = null;
    function confirmDelete(schoolId, schoolName) {
        schoolIdToDelete = schoolId;
        document.getElementById('deleteSchoolName').textContent = schoolName;
        let modal = new bootstrap.Modal(document.getElementById('deleteSchoolModal'));
        modal.show();
        document.getElementById('confirmDeleteBtn').onclick = function() {
            document.getElementById('delete-form-' + schoolIdToDelete).submit();
        };
    }
    window.confirmDelete = confirmDelete;
    // Enable Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>
@endsection