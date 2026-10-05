@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">{{ $group->name }}</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('communication.groups.edit', $group) }}" class="btn btn-outline-primary">
            <i class="fas fa-edit me-2"></i>Edit Group
        </a>
        <a href="{{ route('communication.groups.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Groups
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Group Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Name:</strong>
                        <p>{{ $group->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Type:</strong>
                        <p><span class="badge bg-info">{{ ucfirst($group->type) }}</span></p>
                    </div>
                </div>
                
                @if($group->description)
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p>{{ $group->description }}</p>
                    </div>
                @endif
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <p>
                            <span class="badge {{ $group->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $group->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <strong>Members:</strong>
                        <p><span class="badge bg-secondary">{{ $group->member_count }} members</span></p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Created By:</strong>
                        <p>{{ $group->creator->name ?? 'Unknown' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Created At:</strong>
                        <p>{{ $group->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Group Members</h5>
            </div>
            <div class="card-body">
                @if($group->members->count())
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($group->members as $member)
                                    <tr>
                                        <td>{{ $member->name }}</td>
                                        <td>{{ $member->email }}</td>
                                        <td>
                                            <span class="badge {{ $member->pivot->role === 'admin' ? 'bg-danger' : 'bg-secondary' }}">
                                                {{ ucfirst($member->pivot->role) }}
                                            </span>
                                        </td>
                                        <td>{{ $member->pivot->joined_at->format('M j, Y') }}</td>
                                        <td>
                                            @if($member->id !== $group->created_by)
                                                <form method="POST" action="{{ route('communication.groups.remove-member', [$group, $member->id]) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this member?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-user-minus"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">Creator</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No members in this group.</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Group Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Last Updated:</strong>
                    <p>{{ $group->updated_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
                
                <div class="mb-3">
                    <strong>Actions:</strong>
                    <div class="d-grid gap-2">
                        <form method="POST" action="{{ route('communication.groups.toggle-status', $group) }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-toggle-{{ $group->is_active ? 'off' : 'on' }} me-2"></i>
                                {{ $group->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('communication.groups.destroy', $group) }}" onsubmit="return confirm('Are you sure you want to delete this group?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-2"></i>Delete Group
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Send Message to Group</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('communication.groups.send-message', $group) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="body" class="form-label">Message</label>
                        <textarea class="form-control" id="body" name="body" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                            <option value="notification">Notification</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-paper-plane me-2"></i>Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
