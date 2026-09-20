@extends('layouts.app')

@section('title', 'FAQs Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-question-circle me-2 text-primary"></i>FAQs</h1>
            <p class="text-muted mb-0">Manage frequently asked questions</p>
        </div>
        <a href="{{ route('admin.cms.faqs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add FAQ
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
                            <th>Question</th>
                            <th>Category</th>
                            <th>Active</th>
                            <th>Sort Order</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faqs as $faq)
                        <tr>
                            <td>
                                <div class="fw-semibold" style="max-width: 400px;">{{ Str::limit($faq->question, 80) }}</div>
                                <small class="text-muted">{{ Str::limit($faq->answer, 60) }}</small>
                            </td>
                            <td>
                                @if($faq->category)
                                    <span class="badge bg-info">{{ $faq->category }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($faq->is_active)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Active</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Inactive</span>
                                @endif
                            </td>
                            <td>{{ $faq->sort_order }}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.cms.faqs.edit', $faq) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.cms.faqs.destroy', $faq) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this FAQ?')">
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
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No FAQs found</p>
                                <a href="{{ route('admin.cms.faqs.create') }}" class="btn btn-primary btn-sm mt-3">
                                    <i class="fas fa-plus me-1"></i> Add First FAQ
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($faqs, 'links'))
        <div class="card-footer">
            {{ $faqs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
