<x-hr::layouts.master>
    <div class="container py-4" style="min-height: 90vh;">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="card shadow rounded-4 border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-0 pb-0">
                        <h3 class="fw-bold mb-0"><i class="fas fa-star-half-alt text-warning me-2"></i> Add Performance Review</h3>
                        <a href="{{ route('hr.performance_reviews.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                    <div class="card-body p-4">
                        @php $errors = $errors ?? session('errors'); @endphp
                        @if($errors && $errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('hr.performance_reviews.store') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="mb-3">
                                <label for="staff_id" class="form-label">Staff <span class="text-danger">*</span></label>
                                <select name="staff_id" id="staff_id" class="form-select" required>
                                    <option value="">Select staff</option>
                                    @forelse($staff as $s)
                                        <option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }}</option>
                                    @empty
                                        <option value="">No staff available</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="reviewer_id" class="form-label">Reviewer <span class="text-danger">*</span></label>
                                <select name="reviewer_id" id="reviewer_id" class="form-select" required>
                                    <option value="">Select reviewer</option>
                                    @forelse($staff as $s)
                                        <option value="{{ $s->id }}">{{ $s->first_name }} {{ $s->last_name }}</option>
                                    @empty
                                        <option value="">No staff available</option>
                                    @endforelse
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="period" class="form-label">Period <span class="text-danger">*</span></label>
                                <input type="text" name="period" id="period" class="form-control" required placeholder="e.g., 2025-Q3 or 2025-Annual">
                            </div>
                            <div class="mb-3">
                                <label for="score" class="form-label">Score (1-10) <span class="text-danger">*</span></label>
                                <input type="number" name="score" id="score" class="form-control" min="1" max="10" required>
                            </div>
                            <div class="mb-3">
                                <label for="review_date" class="form-label">Review Date <span class="text-danger">*</span></label>
                                <input type="date" name="review_date" id="review_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="comments" class="form-label">Comments</label>
                                <textarea name="comments" id="comments" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="fas fa-save me-2"></i>Save Review
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-hr::layouts.master> 