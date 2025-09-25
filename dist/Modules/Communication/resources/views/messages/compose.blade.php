@extends('communication::layouts.master')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Compose Message</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('communication.send') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="recipients" class="form-label">Recipients</label>
                            <select name="recipients[]" id="recipients" class="form-select" multiple required>
                                @foreach($users as $user)
                                    @if($user->id !== auth()->id())
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endif
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple recipients.</small>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" maxlength="255" placeholder="Enter subject (optional)">
                        </div>
                        <div class="mb-3">
                            <label for="body" class="form-label">Message</label>
                            <textarea name="body" id="body" class="form-control" rows="7" required placeholder="Type your message here..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="attachments" class="form-label">Attachments</label>
                            <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                            <small class="form-text text-muted">You can attach multiple files. Max size: 10MB each.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Send via:</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="send_email" id="send_email" value="1" checked>
                                <label class="form-check-label" for="send_email">Email</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="send_sms" id="send_sms" value="1" checked>
                                <label class="form-check-label" for="send_sms">SMS</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="send_push" id="send_push" value="1" checked>
                                <label class="form-check-label" for="send_push">App Notification</label>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">Send <i class="fas fa-paper-plane ms-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    // If you have Select2 or similar, you can initialize it here
    // $('#recipients').select2({ placeholder: 'Select recipients' });
</script>
@endpush
@endsection 