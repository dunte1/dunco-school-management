@extends('layouts.app')

@section('title', 'Enquiry Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-envelope me-2 text-primary"></i>Enquiry Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.cms.enquiries.index') }}">Enquiries</a></li>
                    <li class="breadcrumb-item active">{{ $enquiry->reference }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.cms.enquiries.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-comment me-2"></i>Message</h5>
                    @php
                        $statusColors = [
                            'new' => 'warning',
                            'in_progress' => 'info',
                            'replied' => 'secondary',
                            'resolved' => 'success',
                            'closed' => 'dark',
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$enquiry->status] ?? 'secondary' }} fs-6">
                        {{ ucfirst(str_replace('_', ' ', $enquiry->status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="border-bottom pb-3 mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted d-block">From</small>
                                <strong>{{ $enquiry->name }}</strong>
                                <br><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a>
                                @if($enquiry->phone)
                                    <br><a href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a>
                                @endif
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted d-block">Reference</small>
                                <code>{{ $enquiry->reference }}</code>
                                <br><small class="text-muted">{{ $enquiry->created_at->format('M d, Y \a\t h:i A') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <h6>Subject: {{ $enquiry->subject }}</h6>
                        <div class="bg-light p-3 rounded" style="white-space: pre-wrap;">{{ $enquiry->message }}</div>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Notes</h5>
                </div>
                <div class="card-body">
                    @forelse($enquiry->notes ?? [] as $note)
                        <div class="border-start border-primary border-3 ps-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $note->user->name ?? 'System' }}</strong>
                                <small class="text-muted">{{ $note->created_at->format('M d, Y h:i A') }}</small>
                            </div>
                            <p class="mb-0 mt-1">{{ $note->content }}</p>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No notes yet</p>
                    @endforelse
                </div>
            </div>

            {{-- Add Note --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add Note</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cms.enquiries.note', $enquiry) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control @error('note') is-invalid @enderror" name="note" rows="3" placeholder="Add an internal note..." required>{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-save me-1"></i> Save Note
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Status Update --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cms.enquiries.update', $enquiry) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <select class="form-select" name="status">
                                <option value="new" {{ $enquiry->status == 'new' ? 'selected' : '' }}>New</option>
                                <option value="in_progress" {{ $enquiry->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="replied" {{ $enquiry->status == 'replied' ? 'selected' : '' }}>Replied</option>
                                <option value="resolved" {{ $enquiry->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ $enquiry->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            {{-- Enquiry Info --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width: 120px;">Reference</td>
                            <td><code>{{ $enquiry->reference }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Created</td>
                            <td>{{ $enquiry->created_at->format('M d, Y') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Updated</td>
                            <td>{{ $enquiry->updated_at->format('M d, Y') }}</td>
                        </tr>
                        @if($enquiry->ip_address)
                        <tr>
                            <td class="text-muted">IP Address</td>
                            <td>{{ $enquiry->ip_address }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="mailto:{{ $enquiry->email }}?subject=Re: {{ $enquiry->subject }}" class="btn btn-outline-primary">
                        <i class="fas fa-reply me-1"></i> Reply via Email
                    </a>
                    <form action="{{ route('admin.cms.enquiries.destroy', $enquiry) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this enquiry?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-1"></i> Delete Enquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
