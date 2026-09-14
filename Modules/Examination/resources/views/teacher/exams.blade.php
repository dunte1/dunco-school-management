@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">My Exams (Teacher)</h1>

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left">Exam</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Start</th>
            </tr>
        </thead>
        <tbody>
        @forelse(($exams ?? collect()) as $exam)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $exam->name ?? '—' }}</td>
                <td class="px-4 py-2">{{ ucfirst($exam->status ?? '—') }}</td>
                <td class="px-4 py-2">{{ optional($exam->start_date)->format('Y-m-d') ?? '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">No exams available.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
