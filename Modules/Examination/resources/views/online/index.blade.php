@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Online Exams</h1>

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left">Exam</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Start</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse(($exams ?? collect()) as $exam)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $exam->name }}</td>
                <td class="px-4 py-2">{{ ucfirst($exam->status ?? '—') }}</td>
                <td class="px-4 py-2">{{ optional($exam->start_date)->format('Y-m-d') ?? '—' }}</td>
                <td class="px-4 py-2 text-right">
                    <a href="{{ route('examination.online.start', $exam) }}" class="text-blue-600 hover:underline">Start</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">No online exams available.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
