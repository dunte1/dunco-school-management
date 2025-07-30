@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-comments me-2"></i>Communication</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.communication') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            {{-- Announcements --}}
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Announcements & Circulars</h5></div>
                <div class="card-body">
                    @if($announcements->count())
                    <ul class="list-group list-group-flush">
                        @foreach($announcements as $announcement)
                        <li class="list-group-item">
                            <div class="fw-bold">{{ $announcement->title }}</div>
                            <small class="text-muted">{{ $announcement->published_at->format('M d, Y') }}</small>
                            <p class="mb-0">{{ $announcement->message }}</p>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-center text-muted py-4"><i class="fas fa-bullhorn fa-2x mb-2"></i><p>No announcements.</p></div>
                    @endif
                </div>
            </div>
            
            {{-- Direct Messaging --}}
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Direct Message with Admin</h5></div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if($messages->count())
                    @foreach($messages as $message)
                    <div class="d-flex justify-content-{{ $message->sender_id == auth()->id() ? 'end' : 'start' }} mb-2">
                        <div class="card bg-{{ $message->sender_id == auth()->id() ? 'primary text-white' : 'light' }}" style="max-width: 75%;">
                            <div class="card-body p-2">
                                <small class="fw-bold">{{ $message->sender->name }}</small>
                                <div>{{ $message->message }}</div>
                                <small class="text-muted">{{ $message->created_at->format('H:i') }}</small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
                <div class="card-footer">
                    <form method="POST" action="{{ route('portal.communication.send-message') }}" class="d-flex gap-2">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $admin->id }}">
                        <input type="text" name="message" class="form-control" placeholder="Type your message...">
                        <button class="btn btn-primary" type="submit">Send</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            {{-- Notification Preferences --}}
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Notification Preferences</h5></div>
                <div class="card-body">
                    <p>Receive notifications via:</p>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="notif_inapp" checked disabled><label class="form-check-label" for="notif_inapp">In-App</label></div>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="notif_email" checked><label class="form-check-label" for="notif_email">Email</label></div>
                    <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="notif_sms"><label class="form-check-label" for="notif_sms">SMS</label></div>
                    <hr>
                    <p>Send a test notification to check your channels:</p>
                    <form method="POST" action="{{ route('portal.communication.send-test-notification') }}">
                        @csrf
                        <button class="btn btn-outline-primary" type="submit">Send Test</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
