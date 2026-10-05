@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Membership Details</h2>
        <div>
            <a href="{{ route('library.memberships.edit', $membership) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Edit Membership
            </a>
            <a href="{{ route('library.memberships.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Memberships
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <h3 class="card-title mb-0">{{ $membership->member->name ?? 'Unknown Member' }}</h3>
                        <span class="badge bg-{{ $membership->status == 'active' ? 'success' : ($membership->status == 'expired' ? 'danger' : 'warning') }} ms-2">
                            {{ ucfirst($membership->status) }}
                        </span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Member</h6>
                            <p class="text-muted">
                                <a href="{{ route('library.members.show', $membership->member) }}" class="text-decoration-none">
                                    {{ $membership->member->name ?? 'Unknown Member' }}
                                </a>
                                @if($membership->member->email)
                                    <br><small class="text-muted">{{ $membership->member->email }}</small>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Membership Type</h6>
                            <p class="text-muted">
                                <span class="badge bg-info">{{ ucfirst($membership->membership_type) }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Start Date</h6>
                            <p class="text-muted">{{ \Carbon\Carbon::parse($membership->start_date)->format('F d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>End Date</h6>
                            <p class="text-muted">
                                @if($membership->end_date)
                                    {{ \Carbon\Carbon::parse($membership->end_date)->format('F d, Y') }}
                                    @if($membership->end_date < now())
                                        <span class="badge bg-danger ms-2">Expired</span>
                                    @elseif($membership->end_date < now()->addDays(30))
                                        <span class="badge bg-warning ms-2">Expires Soon</span>
                                    @endif
                                @else
                                    <span class="text-muted">No end date</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($membership->notes)
                        <div class="row">
                            <div class="col-12">
                                <h6>Notes</h6>
                                <p class="text-muted">{{ $membership->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6>Created</h6>
                            <p class="text-muted">{{ $membership->created_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Last Updated</h6>
                            <p class="text-muted">{{ $membership->updated_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('library.memberships.edit', $membership) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Membership
                        </a>
                        <form action="{{ route('library.memberships.destroy', $membership) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Are you sure you want to delete this membership? This action cannot be undone.')">
                                <i class="fas fa-trash me-2"></i>Delete Membership
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
