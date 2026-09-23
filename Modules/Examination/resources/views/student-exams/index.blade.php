@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">My Exams</h1>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exam</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Marks</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($exams as $exam)
                <tr>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $exam->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $exam->type->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $exam->start_date }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $exam->duration_minutes ?? '—' }} min</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $exam->total_marks }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $exam->status === 'published' ? 'bg-green-100 text-green-800' :
                               ($exam->status === 'ongoing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($exam->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($exam->is_online)
                            <a href="{{ route('examination.online.start', $exam) }}" class="text-blue-600 hover:underline">Start Online</a>
                        @elseif($exam->status === 'published')
                            <a href="{{ route('examination.exams.show', $exam) }}" class="text-blue-600 hover:underline">View Details</a>
                        @else
                            <span class="text-gray-400">Not available</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">No exams available.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
