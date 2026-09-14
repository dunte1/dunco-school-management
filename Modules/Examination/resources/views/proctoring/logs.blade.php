@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Proctoring Logs</h1>

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left">Event</th>
                <th class="px-4 py-2 text-left">Description</th>
                <th class="px-4 py-2 text-left">Severity</th>
                <th class="px-4 py-2 text-left">Time</th>
            </tr>
        </thead>
        <tbody>
        @forelse(($logs ?? collect()) as $log)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $log->event_type ?? '—' }}</td>
                <td class="px-4 py-2">{{ $log->description ?? '—' }}</td>
                <td class="px-4 py-2">{{ $log->severity ?? '—' }}</td>
                <td class="px-4 py-2">{{ $log->timestamp ?? ($log->created_at ?? '—') }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">No proctoring logs.</td></tr>
        @endforelse
        </tbody>
    </table>

    @if(isset($logs) && method_exists($logs, 'links'))
        <div class="mt-4">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
