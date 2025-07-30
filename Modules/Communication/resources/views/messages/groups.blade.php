@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Groups</h1>
    <a href="{{ route('communication.groups.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Create Group
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4">
    @forelse($userGroups as $group)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $group->name }}</h5>
                    <p class="card-text text-muted">{{ $group->description ?? 'No description' }}</p>
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-users me-1"></i>
                            {{ $group->activeMembers->count() }} members
                        </small>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fas fa-user me-1"></i>
                            Created by {{ $group->creator->name }}
                        </small>
                    </div>
                    <a href="{{ route('communication.groups.show', $group->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-comments me-1"></i>Open Chat
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No Groups Yet</h4>
                <p class="text-muted">You haven't joined any groups yet. Create a new group to start chatting!</p>
                <a href="{{ route('communication.groups.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create Your First Group
                </a>
            </div>
        </div>
    @endforelse
</div>
@endsection 