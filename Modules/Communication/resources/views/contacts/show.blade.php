@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">{{ $contact->name }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('communication.contacts.edit', $contact) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-2"></i>Edit Contact
        </a>
        <a href="{{ route('communication.contacts.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Contacts
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Contact Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Name:</strong>
                        <p>{{ $contact->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Category:</strong>
                        <p><span class="badge bg-info">{{ ucfirst($contact->category) }}</span></p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Email:</strong>
                        <p>
                            @if($contact->email)
                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>Phone:</strong>
                        <p>
                            @if($contact->phone)
                                <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Organization:</strong>
                        <p>
                            @if($contact->organization)
                                {{ $contact->organization }}
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>Position:</strong>
                        <p>
                            @if($contact->position)
                                {{ $contact->position }}
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                @if($contact->notes)
                    <div class="mb-3">
                        <strong>Notes:</strong>
                        <p>{{ $contact->notes }}</p>
                    </div>
                @endif
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <p>
                            <span class="badge {{ $contact->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $contact->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>Created By:</strong>
                        <p>{{ $contact->creator->name ?? 'Unknown' }}</p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Created At:</strong>
                        <p>{{ $contact->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Last Updated:</strong>
                        <p>{{ $contact->updated_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        @if($contact->groups->count())
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Groups</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($contact->groups as $group)
                            <div class="col-md-4 mb-2">
                                <div class="card border">
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-1">{{ $group->name }}</h6>
                                        <small class="text-muted">{{ ucfirst($group->type) }}</small>
                                        <br>
                                        <span class="badge {{ $group->is_active ? 'bg-success' : 'bg-secondary' }} mt-1">
                                            {{ $group->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <form method="POST" action="{{ route('communication.contacts.toggle-status', $contact) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-toggle-{{ $contact->is_active ? 'off' : 'on' }} me-2"></i>
                            {{ $contact->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    
                    @if($contact->email)
                        <a href="mailto:{{ $contact->email }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-envelope me-2"></i>Send Email
                        </a>
                    @endif
                    
                    @if($contact->phone)
                        <a href="tel:{{ $contact->phone }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-phone me-2"></i>Call
                        </a>
                    @endif
                    
                    <form method="POST" action="{{ route('communication.contacts.destroy', $contact) }}" onsubmit="return confirm('Are you sure you want to delete this contact?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash me-2"></i>Delete Contact
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Contact Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">{{ $contact->groups->count() }}</h4>
                            <small class="text-muted">Groups</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-0">{{ $contact->is_active ? 'Active' : 'Inactive' }}</h4>
                        <small class="text-muted">Status</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
