@extends('layouts.app')

@section('title', 'Demo Request Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-desktop me-2 text-primary"></i>Demo Request Details</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.cms.demos.index') }}">Demo Requests</a></li>
                    <li class="breadcrumb-item active">{{ $demo->reference }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.cms.demos.index') }}" class="btn btn-outline-secondary">
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
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Demo Information</h5>
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'scheduled' => 'info',
                            'completed' => 'success',
                            'cancelled' => 'danger',
                        ];
                    @endphp
                    <span class="badge bg-{{ $statusColors[$demo->status] ?? 'secondary' }} fs-6">
                        {{ ucfirst($demo->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Contact Person</h6>
                            <p class="fw-semibold mb-2">{{ $demo->name }}</p>
                            <p class="mb-1"><i class="fas fa-envelope me-2 text-muted"></i>{{ $demo->email }}</p>
                            @if($demo->phone)
                                <p class="mb-1"><i class="fas fa-phone me-2 text-muted"></i>{{ $demo->phone }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-1">Institution</h6>
                            <p class="fw-semibold mb-2">{{ $demo->institution ?? '-' }}</p>
                            @if($demo->school_type)
                                <p class="mb-1"><i class="fas fa-school me-2 text-muted"></i>{{ $demo->school_type }}</p>
                            @endif
                            @if($demo->student_count)
                                <p class="mb-1"><i class="fas fa-users me-2 text-muted"></i>{{ $demo->student_count }} students</p>
                            @endif
                        </div>
                        <div class="col-12">
                            <h6 class="text-muted mb-1">Requirements / Notes</h6>
                            <div class="bg-light p-3 rounded" style="white-space: pre-wrap;">{{ $demo->requirements ?? $demo->notes ?? 'No additional notes provided.' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Preferred Date --}}
            @if($demo->preferred_date || $demo->preferred_time)
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Scheduled Demo</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($demo->preferred_date)
                        <div class="col-md-6">
                            <small class="text-muted">Preferred Date</small>
                            <div class="fw-semibold">{{ \Carbon\Carbon::parse($demo->preferred_date)->format('l, F j, Y') }}</div>
                        </div>
                        @endif
                        @if($demo->preferred_time)
                        <div class="col-md-6">
                            <small class="text-muted">Preferred Time</small>
                            <div class="fw-semibold">{{ $demo->preferred_time }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            {{-- Status Update --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Update Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cms.demos.update', $demo) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <select class="form-select" name="status">
                                <option value="pending" {{ $demo->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="scheduled" {{ $demo->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="completed" {{ $demo->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $demo->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Demo Date</label>
                            <input type="date" class="form-control" name="demo_date" value="{{ $demo->demo_date ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Demo Time</label>
                            <input type="time" class="form-control" name="demo_time" value="{{ $demo->demo_time ?? '' }}">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            {{-- Request Info --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Request Info</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width: 120px;">Reference</td>
                            <td><code>{{ $demo->reference }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Created</td>
                            <td>{{ $demo->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Updated</td>
                            <td>{{ $demo->updated_at->format('M d, Y h:i A') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="mailto:{{ $demo->email }}?subject=Re: Demo Request {{ $demo->reference }}" class="btn btn-outline-primary">
                        <i class="fas fa-envelope me-1"></i> Send Email
                    </a>
                    @if($demo->phone)
                    <a href="tel:{{ $demo->phone }}" class="btn btn-outline-success">
                        <i class="fas fa-phone me-1"></i> Call
                    </a>
                    @endif
                    <form action="{{ route('admin.cms.demos.destroy', $demo) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this demo request?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
