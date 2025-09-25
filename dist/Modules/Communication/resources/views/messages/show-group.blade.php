@extends('communication::layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Group Info & Members -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ $group->name }}</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ $group->description ?? 'No description' }}</p>
                    <hr>
                    <h6>Members ({{ $group->activeMembers->count() }})</h6>
                    <ul class="list-unstyled">
                        @foreach($group->activeMembers as $member)
                            <li class="mb-1">
                                <small>
                                    {{ $member->user->name }}
                                    @if($member->isAdmin())
                                        <span class="badge bg-primary">Admin</span>
                                    @endif
                                </small>
                            </li>
                        @endforeach
                    </ul>
                    
                    @if($isAdmin)
                        <hr>
                        <h6>Add Member</h6>
                        <form action="{{ route('communication.groups.members.add', $group->id) }}" method="POST">
                            @csrf
                            <select name="user_id" class="form-select form-select-sm mb-2">
                                <option value="">Select user...</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-primary">Add</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Chat Area -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Group Chat</h5>
                </div>
                <div class="card-body" style="height: 400px; overflow-y: auto;">
                    @forelse($group->messages as $message)
                        <div class="mb-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-grow-1">
                                    <div class="bg-light p-2 rounded">
                                        <small class="text-muted">{{ $message->sender->name }} • {{ $message->created_at->format('M j, g:i A') }}</small>
                                        <div class="mt-1">{!! nl2br(e($message->body)) !!}</div>
                                        
                                        @if($message->attachments->count())
                                            <div class="mt-2">
                                                <small class="text-muted">Attachments:</small>
                                                @foreach($message->attachments as $attachment)
                                                    <div>
                                                        <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="text-decoration-none">
                                                            <i class="fas fa-paperclip me-1"></i>{{ $attachment->file_name }}
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-comments fa-2x mb-2"></i>
                            <p>No messages yet. Start the conversation!</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Message Form -->
                <div class="card-footer">
                    <form action="{{ route('communication.groups.message', $group->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <textarea name="body" class="form-control" rows="2" placeholder="Type your message..." required></textarea>
                            </div>
                            <div class="col-md-2">
                                <input type="file" name="attachments[]" class="form-control" multiple>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 