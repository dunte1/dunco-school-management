@extends('layouts.app')

@section('title', 'Library Publishers')

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
    
    .publishers-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .publishers-header {
        background: #f8fafc;
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: between;
        align-items: center;
    }
    
    .publishers-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }
    
    .btn-add-publisher {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-add-publisher:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .publishers-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .publishers-table th {
        background: #f1f5f9;
        font-weight: 700;
        color: #475569;
        padding: 1rem;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .publishers-table td {
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
    }
    
    .publishers-table tbody tr:hover {
        background: #f8fafc;
        transition: background 0.2s;
    }
    
    .publisher-name {
        font-weight: 600;
        color: #1e293b;
    }
    
    .publisher-email {
        color: #3b82f6;
        text-decoration: none;
    }
    
    .publisher-email:hover {
        text-decoration: underline;
    }
    
    .publisher-phone {
        color: #64748b;
    }
    
    .publisher-address {
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .publisher-date {
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
        <p class="library-subtitle">Manage publishers, books, and library resources</p>
    </div>
    
    <div class="publishers-card">
        <div class="publishers-header">
            <h2 class="publishers-title">Publishers</h2>
            <a href="{{ route('library.publishers.create') }}" class="btn-add-publisher">
                <i class="fas fa-plus me-2"></i>Add Publisher
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="publishers-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publishers as $publisher)
                    <tr>
                        <td>
                            <div class="publisher-name">{{ $publisher->name }}</div>
                        </td>
                        <td>
                            <a href="mailto:{{ $publisher->email }}" class="publisher-email">
                                {{ $publisher->email }}
                            </a>
                        </td>
                        <td>
                            <span class="publisher-phone">{{ $publisher->phone }}</span>
                        </td>
                        <td>
                            <span class="publisher-address">{{ $publisher->address }}</span>
                        </td>
                        <td>
                            <span class="publisher-date">{{ \Carbon\Carbon::parse($publisher->created_at)->format('M d, Y') }}</span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('library.publishers.show', $publisher->id) }}" class="btn-view">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('library.publishers.edit', $publisher->id) }}" class="btn-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('library.publishers.destroy', $publisher->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this publisher?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle me-2"></i>No publishers found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
