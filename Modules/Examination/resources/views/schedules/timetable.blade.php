@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Exam Timetable</h1>

    @forelse($schedulesByDate as $date => $daySchedules)
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</h2>
        <div class="bg-white rounded shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exam</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invigilators</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instructions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($daySchedules as $schedule)
                    <tr>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ date('g:i A', strtotime($schedule->start_time)) }} - {{ date('g:i A', strtotime($schedule->end_time)) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $schedule->exam->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $schedule->class_name }}{{ $schedule->section ? ' - ' . $schedule->section : '' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $schedule->subject ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $schedule->room_number ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            @if($schedule->invigilators)
                                {{ implode(', ', is_array($schedule->invigilators) ? $schedule->invigilators : json_decode($schedule->invigilators, true)) }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ Str::limit($schedule->instructions, 50) ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="bg-white rounded shadow p-8 text-center text-gray-500">
        No exam schedules found.
    </div>
    @endforelse
</div>
@endsection
