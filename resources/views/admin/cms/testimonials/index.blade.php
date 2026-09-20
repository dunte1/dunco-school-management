@extends('layouts.app')

@section('title', 'Testimonials Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-quote-left me-2 text-primary"></i>Testimonials</h1>
            <p class="text-muted mb-0">Manage customer testimonials and reviews</p>
        </div>
        <a href="{{ route('admin.cms.testimonials.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Testimonial
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Institution</th>
                            <th>Rating</th>
                            <th>Featured</th>
                            <th>Active</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $testimonial)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($testimonial->image)
                                        <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}" class="rounded-circle me-2" width="36" height="36" style="object-fit: cover;">
                                    @else
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <strong>{{ $testimonial->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $testimonial->position ?? '-' }}</td>
                            <td>{{ $testimonial->institution ?? '-' }}</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </td>
                            <td>
                                @if($testimonial->is_featured)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>Featured</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                @if($testimonial->is_active)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Active</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.cms.testimonials.edit', $testimonial) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.cms.testimonials.destroy', $testimonial) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this testimonial?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-quote-left fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No testimonials found</p>
                                <a href="{{ route('admin.cms.testimonials.create') }}" class="btn btn-primary btn-sm mt-3">
                                    <i class="fas fa-plus me-1"></i> Add First Testimonial
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($testimonials, 'links'))
        <div class="card-footer">
            {{ $testimonials->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
