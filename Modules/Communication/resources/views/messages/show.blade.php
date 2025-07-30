@extends('communication::layouts.master')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Message Details</h1>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <strong>From:</strong> {{ $recipient->message->sender->name ?? 'Unknown' }}<br>
            <strong>Subject:</strong> {{ $recipient->message->subject ?? '(No Subject)' }}
        </div>
        <div class="card-body">
            <p>{!! nl2br(e($recipient->message->body)) !!}</p>
            @php
                $attachments = $recipient->message->attachments ?? collect();
            @endphp
            @if($attachments->count())
                <div class="mb-2">
                    <strong>Attachments:</strong>
                    <ul class="list-unstyled mb-0">
                        @foreach($attachments as $attachment)
                            <li>
                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank">{{ $attachment->file_name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <hr>
            <div class="mb-2">
                <strong>Status:</strong>
                @if($recipient->is_read)
                    <span class="badge bg-success">Read</span>
                @else
                    <span class="badge bg-warning">Unread</span>
                @endif
            </div>
            <div class="mb-2">
                <strong>Received At:</strong> {{ $recipient->created_at->format('Y-m-d H:i') }}
            </div>
            <div class="mb-2">
                <strong>Delivery Logs:</strong>
                @php
                    $logs = \Modules\Communication\Models\MessageDeliveryLog::where('message_id', $recipient->message_id)
                        ->where('recipient_id', $recipient->recipient_id)
                        ->get();
                @endphp
                @if($logs->count())
                    <ul class="list-unstyled mb-0">
                        @foreach($logs as $log)
                            <li>
                                <span class="badge bg-light text-dark">{{ ucfirst($log->channel) }}</span>
                                <span class="badge {{ $log->status == 'sent' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($log->status) }}</span>
                                @if($log->response)
                                    <span class="text-muted small">({{ \Illuminate\Str::limit($log->response, 60) }})</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <span class="text-muted">No delivery logs found.</span>
                @endif
            </div>
        </div>
    </div>
    <a href="{{ route('communication.message.reply', $recipient->id) }}" class="btn btn-primary me-2">Reply</a>
    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
</div>
@endsection 