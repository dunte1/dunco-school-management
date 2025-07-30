@extends('communication::layouts.master')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Reply to Message</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>From:</strong> {{ $recipient->message->sender->name ?? 'Unknown' }}<br>
                        <strong>Subject:</strong> {{ $recipient->message->subject ?? '(No Subject)' }}
                        <hr>
                        <div class="bg-light p-2 mb-2">{!! nl2br(e($recipient->message->body)) !!}</div>
                    </div>
                    <form action="{{ route('communication.message.sendReply', $recipient->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="body" class="form-label">Your Reply</label>
                            <textarea name="body" id="body" class="form-control" rows="6" required placeholder="Type your reply here..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="attachments" class="form-label">Attachments</label>
                            <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                            <small class="form-text text-muted">You can attach multiple files. Max size: 10MB each.</small>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">Send Reply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 