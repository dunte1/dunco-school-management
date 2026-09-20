@extends('layouts.app')

@section('title', 'Send Notification')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Send Notification</h4>
        <a href="{{ route('notification.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Back</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('notification.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Recipient Email <span class="text-danger">*</span></label>
                            <input type="email" name="recipient_email" class="form-control" value="{{ old('recipient_email') }}" required>
                            @error('recipient_email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Recipient Phone</label>
                            <input type="text" name="recipient_phone" class="form-control" value="{{ old('recipient_phone') }}" placeholder="+1234567890">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Use Template (optional)</label>
                    <select name="template_id" id="templateSelect" class="form-select">
                        <option value="">-- None --</option>
                        @foreach($templates as $tpl)
                            <option value="{{ $tpl->id }}" data-subject="{{ $tpl->subject }}" data-body="{{ $tpl->body }}">{{ $tpl->name }} ({{ ucfirst($tpl->channel) }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" id="notifSubject" class="form-control" value="{{ old('subject') }}" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Channel <span class="text-danger">*</span></label>
                            <select name="channel" class="form-select" required>
                                <option value="email" {{ old('channel') == 'email' ? 'selected' : '' }}>Email</option>
                                <option value="sms" {{ old('channel') == 'sms' ? 'selected' : '' }}>SMS</option>
                                <option value="both" {{ old('channel') == 'both' ? 'selected' : '' }}>Both</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message Body <span class="text-danger">*</span></label>
                    <textarea name="body" id="notifBody" class="form-control" rows="6" required>{{ old('body') }}</textarea>
                    @error('body') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Send Notification</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('templateSelect').addEventListener('change', function() {
    var selected = this.options[this.selectedIndex];
    if (this.value) {
        document.getElementById('notifSubject').value = selected.dataset.subject || '';
        document.getElementById('notifBody').value = selected.dataset.body || '';
    }
});
</script>
@endpush
@endsection
