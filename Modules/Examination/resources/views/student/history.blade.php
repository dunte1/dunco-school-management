@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Exam History</h1>

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left">Exam</th>
                <th class="px-4 py-2 text-left">Marks</th>
                <th class="px-4 py-2 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
        @forelse(($attempts ?? collect()) as $attempt)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $attempt->exam->name ?? '—' }}</td>
                <td class="px-4 py-2">{{ $attempt->obtained_marks }} / {{ $attempt->total_marks }}</td>
                <td class="px-4 py-2">{{ ucfirst($attempt->status ?? '—') }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">No exam history.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
