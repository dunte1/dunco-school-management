@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Start Physical Exam</h1>

    <div class="bg-white rounded shadow p-6">
        <h2 class="text-lg font-semibold mb-2">{{ $exam->name }}</h2>
        <p class="text-gray-600 mb-4">{{ $exam->description }}</p>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-sm text-gray-500">Duration</p>
                <p class="font-medium">{{ $exam->duration_minutes ?? '—' }} minutes</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Marks</p>
                <p class="font-medium">{{ $exam->total_marks }}</p>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded p-4 mb-6">
            <p class="text-sm text-yellow-800">
                <strong>Important:</strong> Once you start this exam, the timer will begin. You have {{ $exam->duration_minutes ?? '—' }} minutes to complete and submit your answers.
            </p>
        </div>

        <form method="POST" action="{{ route('examination.exams.start', $exam) }}">
            @csrf
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700">
                I Understand, Start Exam
            </button>
        </form>
    </div>
</div>
@endsection
