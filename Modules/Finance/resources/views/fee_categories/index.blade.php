@extends('layouts.app')

@section('title', 'Fee Categories - Finance Module')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Fee Categories</h1>
            <p class="text-muted mb-0">Organize fees into categories</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('finance.fees.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Back to Fees
            </a>
            <a href="{{ route('finance.fee-categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Category
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Fee Categories Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Fee Categories List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Description</th>
                            <th class="px-4 py-3 text-center">Actions</th>
            </tr>
        </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td class="px-4 py-3">
                                <strong>{{ $category->name }}</strong>
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-muted">{{ $category->description ?: 'No description provided' }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('finance.fee-categories.edit', $category) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('finance.fee-categories.destroy', $category) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this fee category?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-tags fa-3x mb-3"></i>
                                    <h5>No Fee Categories Found</h5>
                                    <p>Start by creating your first fee category.</p>
                                    <a href="{{ route('finance.fee-categories.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Add First Category
                                    </a>
                                </div>
                </td>
            </tr>
                        @endforelse
        </tbody>
    </table>
                </div>
        </div>
    </div>
</div>
@endsection 