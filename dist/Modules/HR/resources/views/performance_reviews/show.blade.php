<x-hr::layouts.master>
    <div class="container py-4" style="min-height: 90vh;">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="card shadow rounded-4 border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-0 pb-0">
                        <h3 class="fw-bold mb-0"><i class="fas fa-star-half-alt text-warning me-2"></i> Performance Review Details</h3>
                        <a href="{{ route('hr.performance_reviews.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                    <div class="card-body p-4">
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Staff</dt>
                            <dd class="col-sm-8 fw-semibold">{{ $review->staff ? ($review->staff->first_name . ' ' . $review->staff->last_name) : '-' }}</dd>
                            <dt class="col-sm-4">Reviewer</dt>
                            <dd class="col-sm-8">{{ $review->reviewer ? ($review->reviewer->first_name . ' ' . $review->reviewer->last_name) : '-' }}</dd>
                            <dt class="col-sm-4">Score</dt>
                            <dd class="col-sm-8"><span class="badge bg-info fs-6">{{ $review->score }}</span></dd>
                            <dt class="col-sm-4">Review Date</dt>
                            <dd class="col-sm-8">{{ $review->review_date }}</dd>
                            <dt class="col-sm-4">Comments</dt>
                            <dd class="col-sm-8">{{ $review->comments ?? '-' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-hr::layouts.master> 