@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <a href="{{ route('examination.student.exams') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to My Exams</a>

    <h1 class="text-2xl font-bold mb-4">{{ $exam->name }}</h1>

    <div class="bg-white rounded shadow p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Exam Code</p>
                <p class="font-medium">{{ $exam->code }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Type</p>
                <p class="font-medium">{{ $exam->type->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Date</p>
                <p class="font-medium">{{ $exam->start_date }} to {{ $exam->end_date }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Duration</p>
                <p class="font-medium">{{ $exam->duration_minutes ?? '—' }} minutes</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Marks</p>
                <p class="font-medium">{{ $exam->total_marks }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Passing Marks</p>
                <p class="font-medium">{{ $exam->passing_marks }}</p>
            </div>
        </div>

        @if($exam->description)
        <div>
            <p class="text-sm text-gray-500 mb-1">Description</p>
            <p class="text-gray-700">{{ $exam->description }}</p>
        </div>
        @endif

        @if($exam->schedules->count())
        <div>
            <p class="text-sm text-gray-500 mb-2">Schedule</p>
            @foreach($exam->schedules as $schedule)
            <div class="bg-gray-50 rounded p-3 mb-2">
                <p class="font-medium">{{ $schedule->class_name }} {{ $schedule->section ? '- ' . $schedule->section : '' }}</p>
                <p class="text-sm text-gray-600">{{ $schedule->exam_date }} | {{ date('g:i A', strtotime($schedule->start_time)) }} - {{ date('g:i A', strtotime($schedule->end_time)) }}</p>
                @if($schedule->room_number)<p class="text-sm text-gray-600">Room: {{ $schedule->room_number }}</p>@endif
                @if($schedule->instructions)<p class="text-sm text-gray-500 italic">{{ $schedule->instructions }}</p>@endif
            </div>
            @endforeach
        </div>
        @endif

        @if($existingAttempt)
        <div class="bg-yellow-50 border border-yellow-200 rounded p-4">
            <p class="font-medium text-yellow-800">You have already submitted this exam.</p>
            <p class="text-sm text-yellow-700">Submitted: {{ $existingAttempt->submitted_at }}</p>
            <a href="{{ route('examination.exams.result', $exam) }}" class="text-blue-600 hover:underline text-sm">View Result</a>
        </div>
        @else
        <div class="border-t pt-4">
            <form method="POST" action="{{ route('examination.exams.start', $exam) }}">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700" onclick="return confirm('Start this exam? You will have {{ $exam->duration_minutes ?? 'limited' }} minutes.')">
                    Start Exam
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
