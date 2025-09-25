@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Outbox</h1>
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search sent messages...">
        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
    </form>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form method="POST" action="#">
    @csrf
    {{-- Bulk actions can be added here in the future --}}
    @if($messages->count())
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-all"></th>
                        <th>To</th>
                        <th>Subject & Preview</th>
                        <th>Date</th>
                        <th>Delivery Status</th>
                        <th>Recipients</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $message)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $message->id }}"></td>
                            <td>
                                @foreach($message->recipients as $recipient)
                                    <span class="badge bg-secondary">{{ $recipient->recipient->name ?? 'Unknown' }}</span>
                                @endforeach
                            </td>
                            <td>
                                <strong>{{ $message->subject ?? '(No Subject)' }}</strong><br>
                                <span class="text-muted small">{{ \Illuminate\Support\Str::limit(strip_tags($message->body), 50) }}</span>
                            </td>
                            <td>{{ $message->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                @php
                                    $logs = \Modules\Communication\Models\MessageDeliveryLog::where('message_id', $message->id)->get();
                                @endphp
                                @foreach($logs as $log)
                                    <div class="small mb-1">
                                        <span class="badge bg-light text-dark">{{ ucfirst($log->channel) }}</span>
                                        <span class="badge {{ $log->status == 'sent' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($log->status) }}</span>
                                        <span class="text-muted">to {{ optional($log->recipient)->name ?? $log->recipient_id }}</span>
                                    </div>
                                @endforeach
                            </td>
                            <td>{{ $message->recipients->count() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $messages->links() }}
    @else
        <p>No sent messages found.</p>
    @endif
</form>
@push('scripts')
<script>
    document.getElementById('select-all')?.addEventListener('change', function(e) {
        document.querySelectorAll('input[name=\"ids[]\"]').forEach(cb => cb.checked = e.target.checked);
    });
</script>
@endpush
@endsection 