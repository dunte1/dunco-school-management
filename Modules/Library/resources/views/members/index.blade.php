@extends('layouts.app')

@section('title', 'Library Members')

@section('content')
<style>
    .library-container {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
        padding: 2rem;
    }
    
    .library-header {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(30, 64, 175, 0.2);
    }
    
    .library-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }
    
    .library-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    
    .members-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .members-header {
        background: #f8fafc;
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: between;
        align-items: center;
    }
    
    .members-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }
    
    .btn-add-member {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-add-member:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .members-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .members-table th {
        background: #f1f5f9;
        font-weight: 700;
        color: #475569;
        padding: 1rem;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .members-table td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
    }
    
    .members-table tbody tr:hover {
        background: #f8fafc;
        transition: background 0.2s;
    }
    
    .member-name {
        font-weight: 600;
        color: #1e293b;
    }
    
    .member-email {
        color: #3b82f6;
        text-decoration: none;
    }
    
    .member-email:hover {
        text-decoration: underline;
    }
    
    .member-phone {
        color: #64748b;
    }
    
    .member-number {
        background: #e0e7ff;
        color: #3730a3;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .member-status {
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .member-status.active {
        background: #dcfce7;
        color: #16a34a;
    }
    
    .member-status.inactive {
        background: #fef3c7;
        color: #d97706;
    }
    
    .member-date {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }
    
    .btn-view {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-view:hover {
        background: #2563eb;
        color: white;
        text-decoration: none;
    }
    
    .btn-edit {
        background: #f59e0b;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-edit:hover {
        background: #d97706;
        color: white;
        text-decoration: none;
    }
    
    .btn-delete {
        background: #ef4444;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-delete:hover {
        background: #dc2626;
        color: white;
        text-decoration: none;
    }
</style>

<div class="library-container">
    <div class="library-header">
        <h1 class="library-title">Library Management System</h1>
        <p class="library-subtitle">Manage library members and their accounts</p>
    </div>
    
    <div class="members-card">
        <div class="members-header">
            <h2 class="members-title">Library Members</h2>
            <a href="{{ route('library.members.create') }}" class="btn-add-member">
                <i class="fas fa-plus me-2"></i>Add Member
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="members-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Membership #</th>
                        <th>Status</th>
                        <th>Join Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                    <tr>
                        <td>
                            <div class="member-name">{{ $member->name }}</div>
                        </td>
                        <td>
                            <a href="mailto:{{ $member->email }}" class="member-email">
                                {{ $member->email }}
                            </a>
                        </td>
                        <td>
                            <span class="member-phone">{{ $member->phone }}</span>
                        </td>
                        <td>
                            <span class="member-number">{{ $member->membership_number }}</span>
                        </td>
                        <td>
                            <span class="member-status {{ $member->status }}">
                                {{ ucfirst($member->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="member-date">{{ \Carbon\Carbon::parse($member->join_date)->format('M d, Y') }}</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('library.members.show', $member->id) }}" class="btn-view">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('library.members.edit', $member->id) }}" class="btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('library.members.destroy', $member->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this member?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle me-2"></i>No members found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
