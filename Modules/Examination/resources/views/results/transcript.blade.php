@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">
        Transcript {{ $studentUser->name ?? '' }}
    </h1>

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left">Exam</th>
                <th class="px-4 py-2 text-left">Marks</th>
                <th class="px-4 py-2 text-left">Percentage</th>
                <th class="px-4 py-2 text-left">Grade</th>
                <th class="px-4 py-2 text-left">Status</th>
            </tr>
        </thead>
        <tbody>
        @forelse($results as $result)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $result->exam->name ?? '—' }}</td>
                <td class="px-4 py-2">{{ $result->obtained_marks }} / {{ $result->total_marks }}</td>
                <td class="px-4 py-2">{{ $result->percentage }}%</td>
                <td class="px-4 py-2">{{ $result->grade ?? '—' }}</td>
                <td class="px-4 py-2">{{ ucfirst($result->result_status ?? '—') }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No results available.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
