@extends('layouts.app')

@section('content')
<style>
    .roles-premium-card {
        background: #fafdf7;
        border-radius: 1.5rem;
        box-shadow: 0 4px 24px 0 rgba(30,167,255,0.07);
        border: 1px solid #e3e9f7;
        padding: 2.2rem 2rem 2rem 2rem;
        margin-bottom: 2rem;
    }
    .roles-header-premium {
        display: flex;
        align-items: center;
        gap: 1.1rem;
        margin-bottom: 1.5rem;
    }
    .roles-header-premium .icon {
        font-size: 2.5rem;
        color: #fff;
        background: #4f8cff;
        border-radius: 1rem;
        padding: 0.7rem 1.1rem;
        box-shadow: 0 2px 12px rgba(30,167,255,0.10);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .roles-header-premium h1 {
        font-weight: 700;
        font-size: 2rem;
        color: #1a237e;
        letter-spacing: 1.1px;
        margin-bottom: 0;
        text-shadow: none;
    }
    .roles-add-btn {
        border-radius: 1.2rem;
        font-weight: 600;
        font-size: 1.08rem;
        padding: 0.7rem 1.5rem;
        box-shadow: 0 2px 8px rgba(30,167,255,0.06);
        background: #4f8cff;
        color: #fff;
        border: none;
        transition: box-shadow 0.18s, background 0.18s, color 0.18s, transform 0.18s;
    }
    .roles-add-btn:hover, .roles-add-btn:focus {
        box-shadow: 0 8px 24px rgba(30,167,255,0.10);
        background: #2563eb;
        color: #fff;
        transform: translateY(-2px) scale(1.04);
        z-index: 2;
    }
    .roles-table-premium {
        border-radius: 1.2rem;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 8px rgba(30,167,255,0.04);
        border: 1px solid #e3e9f7;
    }
    .roles-table-premium th {
        background: #f3f6fa;
        font-weight: 600;
        color: #22304a;
        font-size: 1.04rem;
        border-bottom: 1px solid #e3e9f7;
        letter-spacing: 0.5px;
    }
    .roles-table-premium td {
        vertical-align: middle;
        font-size: 1.03rem;
        background: transparent;
        color: #22304a;
    }
    .roles-table-premium tbody tr:hover {
        background: #f5faff;
        transition: background 0.2s;
    }
    .roles-action-btn {
        border-radius: 0.7rem;
        font-size: 1.1rem;
        margin-right: 0.2rem;
        transition: background 0.18s, color 0.18s, transform 0.18s;
        box-shadow: 0 1px 4px rgba(30,167,255,0.06);
        border: none;
        outline: none;
        padding: 0.45rem 0.7rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .roles-action-btn.view { background: #e3f0ff; color: #2563eb; }
    .roles-action-btn.edit { background: #fff7e6; color: #ffb300; }
    .roles-action-btn:hover { opacity: 0.92; transform: scale(1.08); }
    .roles-breadcrumb {
        background: #f3f6fa;
        border-radius: 0.8rem;
        box-shadow: 0 2px 8px rgba(30,167,255,0.03);
        font-size: 1.08rem;
        margin-bottom: 1.2rem;
        padding: 0.7rem 1.2rem;
    }
    .roles-permission-badge {
        background: #e3f0ff;
        color: #2563eb;
        font-size: 0.97rem;
        font-weight: 500;
        border-radius: 0.7rem;
        margin: 0 0.2rem 0.2rem 0;
        padding: 0.32rem 0.85rem;
        display: inline-block;
        box-shadow: none;
        letter-spacing: 0.2px;
    }
    @media (max-width: 767.98px) {
        .roles-premium-card { padding: 1.2rem 0.5rem; }
        .roles-header-premium h1 { font-size: 1.3rem; }
        .roles-header-premium .icon { font-size: 1.3rem; padding: 0.4rem 0.7rem; }
    }
</style>
<div class="roles-premium-card">
    <div class="roles-header-premium mb-3">
        <span class="icon"><i class="fas fa-user-shield"></i></span>
        <h1>Roles</h1>
        <a href="{{ route('core.roles.create') }}" class="roles-add-btn ms-auto">
            <i class="fas fa-plus"></i> Add Role
        </a>
    </div>
    <nav aria-label="breadcrumb" class="roles-breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Roles</li>
        </ol>
    </nav>
    <div class="table-responsive roles-table-premium mt-3">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Display Name</th>
                    <th>Description</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->display_name }}</td>
                    <td>{{ $role->description }}</td>
                    <td>
                        @if($role->permissions->count() > 0)
                            @foreach($role->permissions as $permission)
                                <span class="roles-permission-badge">{{ $permission->display_name ?? $permission->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">No permissions assigned</span>
                        @endif
                        <!-- Debug: {{ $role->permissions->count() }} permissions loaded -->
                    </td>
                    <td>
                        <a href="{{ route('core.roles.show', $role->id) }}" class="roles-action-btn view" title="View" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('core.roles.edit', $role->id) }}" class="roles-action-btn edit" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection