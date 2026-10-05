@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Category Details</h2>
        <div>
            <a href="{{ route('document.categories.edit', $category) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Edit Category
            </a>
            <a href="{{ route('document.categories.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if($category->color)
                            <div class="me-3" style="width: 20px; height: 20px; background-color: {{ $category->color }}; border-radius: 3px;"></div>
                        @endif
                        <h3 class="card-title mb-0">{{ $category->name }}</h3>
                        @if($category->is_active)
                            <span class="badge bg-success ms-2">Active</span>
                        @else
                            <span class="badge bg-secondary ms-2">Inactive</span>
                        @endif
                    </div>
                    
                    @if($category->description)
                        <div class="mb-4">
                            <h5>Description</h5>
                            <p class="text-muted">{{ $category->description }}</p>
                        </div>
                    @endif

                    @if($category->parent)
                        <div class="mb-4">
                            <h6>Parent Category</h6>
                            <p class="text-muted">
                                <a href="{{ route('document.categories.show', $category->parent) }}" class="text-decoration-none">
                                    {{ $category->parent->name }}
                                </a>
                            </p>
                        </div>
                    @endif

                    @if($category->children->count() > 0)
                        <div class="mb-4">
                            <h6>Subcategories</h6>
                            <div class="row">
                                @foreach($category->children as $child)
                                <div class="col-md-6 mb-2">
                                    <a href="{{ route('document.categories.show', $child) }}" class="text-decoration-none">
                                        <div class="card border">
                                            <div class="card-body py-2">
                                                <div class="d-flex align-items-center">
                                                    @if($child->color)
                                                        <div class="me-2" style="width: 12px; height: 12px; background-color: {{ $child->color }}; border-radius: 2px;"></div>
                                                    @endif
                                                    <span>{{ $child->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6>Created</h6>
                            <p class="text-muted">{{ $category->created_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Last Updated</h6>
                            <p class="text-muted">{{ $category->updated_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h3 class="text-primary">{{ $category->documents_count ?? 0 }}</h3>
                        <p class="text-muted mb-0">Documents</p>
                    </div>
                    
                    <div class="text-center mb-3">
                        <h3 class="text-success">{{ $category->children->count() }}</h3>
                        <p class="text-muted mb-0">Subcategories</p>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('document.categories.edit', $category) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Category
                        </a>
                        <form action="{{ route('document.categories.destroy', $category) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                                <i class="fas fa-trash me-2"></i>Delete Category
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
