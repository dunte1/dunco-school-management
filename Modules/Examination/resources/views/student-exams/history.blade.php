@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Exam History</h1>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($attempts as $attempt)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $attempt->exam->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $attempt->submitted_at ? $attempt->submitted_at->format('M d, Y') : '—' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $attempt->obtained_marks }}/{{ $attempt->total_marks }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $attempt->result->grade ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $attempt->status === 'submitted' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($attempt->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($attempt->result)
                            <a href="{{ route('examination.results.show', $attempt->result) }}" class="text-blue-600 hover:underline">View Result</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">No exam history found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
