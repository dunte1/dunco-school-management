@extends('layouts.app')

@section('title', 'Pricing Plans Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-tags me-2 text-primary"></i>Pricing Plans</h1>
            <p class="text-muted mb-0">Manage pricing plans and packages</p>
        </div>
        <a href="{{ route('admin.cms.pricing.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Plan
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
                            <th>Price</th>
                            <th>Billing Period</th>
                            <th>Featured</th>
                            <th>Active</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $plan->name }}</div>
                                <small class="text-muted">{{ Str::limit($plan->description, 50) }}</small>
                            </td>
                            <td>
                                <span class="fs-5 fw-bold text-primary">{{ $plan->currency ?? '$' }}{{ number_format($plan->price, 2) }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ ucfirst($plan->billing_period ?? 'monthly') }}</span>
                            </td>
                            <td>
                                @if($plan->is_featured)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-star me-1"></i>Featured</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                @if($plan->is_active)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Active</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.cms.pricing.edit', $plan) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.cms.pricing.destroy', $plan) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this plan?')">
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
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No pricing plans found</p>
                                <a href="{{ route('admin.cms.pricing.create') }}" class="btn btn-primary btn-sm mt-3">
                                    <i class="fas fa-plus me-1"></i> Add First Plan
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($plans, 'links'))
        <div class="card-footer">
            {{ $plans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
