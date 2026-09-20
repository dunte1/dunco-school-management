@extends('layouts.app')

@section('title', 'Edit Notification')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-pen me-2"></i>Edit Notification #{{ $notification->id }}</h4>
        <a href="{{ route('notification.show', $notification->id) }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('notification.update', $notification->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Recipient Email <span class="text-danger">*</span></label>
                            <input type="email" name="recipient_email" class="form-control" value="{{ old('recipient_email', $notification->recipient_email) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Recipient Phone</label>
                            <input type="text" name="recipient_phone" class="form-control" value="{{ old('recipient_phone', $notification->recipient_phone) }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject', $notification->subject) }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Channel <span class="text-danger">*</span></label>
                            <select name="channel" class="form-select" required>
                                <option value="email" {{ old('channel', $notification->channel) == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="sms" {{ old('channel', $notification->channel) == 'sms' ? 'selected' : '' }}>SMS</option>
                                <option value="both" {{ old('channel', $notification->channel) == 'both' ? 'selected' : '' }}>Both</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message Body <span class="text-danger">*</span></label>
                    <textarea name="body" class="form-control" rows="6" required>{{ old('body', $notification->body) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update Notification</button>
            </form>
        </div>
    </div>
</div>
@endsection
