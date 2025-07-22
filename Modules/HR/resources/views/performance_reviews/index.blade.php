<x-hr::layouts.master>
    <div class="container-fluid py-4" style="min-height: 90vh; background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm">
                <li class="breadcrumb-item"><a href="/hr"><i class="fas fa-users"></i> HR Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Performance Reviews</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold mb-0" style="letter-spacing: -1px;"><i class="fas fa-star-half-alt text-warning me-2"></i>Performance Reviews</h1>
            <a href="{{ route('hr.performance_reviews.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="fas fa-plus"></i> Add Review
            </a>
        </div>
        <div class="card shadow rounded-4 border-0">
            <div class="card-body p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Staff</th>
                                <th>Reviewer</th>
                                <th>Score</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                            <tr>
                                <td class="fw-semibold">{{ $review->staff->name ?? '-' }}</td>
                                <td>{{ $review->reviewer->name ?? '-' }}</td>
                                <td><span class="badge bg-info fs-6">{{ $review->score }}</span></td>
                                <td>{{ $review->review_date }}</td>
                                <td>
                                    <a href="{{ route('hr.performance_reviews.show', $review->id) }}" class="btn btn-sm btn-info me-1" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('hr.performance_reviews.edit', $review->id) }}" class="btn btn-sm btn-warning me-1" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('hr.performance_reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="my-4">
                                        <img src="https://cdn.jsdelivr.net/gh/edent/SuperTinyIcons/images/svg/clipboard.svg" alt="No reviews" width="64" class="mb-3 opacity-50">
                                        <div class="fs-5 text-muted">No performance reviews found.<br>Add your first review to get started!</div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</x-hr::layouts.master> 