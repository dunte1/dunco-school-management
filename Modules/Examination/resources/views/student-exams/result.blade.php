@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <a href="{{ route('examination.student.history') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to History</a>

    <h1 class="text-2xl font-bold mb-4">Exam Result</h1>

    <div class="bg-white rounded shadow p-6">
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-500">Exam</p>
                <p class="text-lg font-semibold">{{ $attempt->exam->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Date</p>
                <p class="font-medium">{{ $attempt->submitted_at?->format('M d, Y H:i') ?? '—' }}</p>
            </div>
        </div>

        @if($attempt->result)
        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ $attempt->result->obtained_marks }}</p>
                <p class="text-xs text-gray-500">Marks Obtained</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-gray-600">{{ $attempt->result->total_marks }}</p>
                <p class="text-xs text-gray-500">Total Marks</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-green-600">{{ $attempt->result->percentage }}%</p>
                <p class="text-xs text-gray-500">Percentage</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-purple-600">{{ $attempt->result->grade ?? '—' }}</p>
                <p class="text-xs text-gray-500">Grade</p>
            </div>
        </div>

        <div class="mb-4">
            <span class="px-3 py-1 text-sm font-medium rounded-full {{ $attempt->result->result_status === 'pass' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ ucfirst($attempt->result->result_status) }}
            </span>
            @if($attempt->result->remarks)
                <p class="mt-2 text-sm text-gray-600">{{ $attempt->result->remarks }}</p>
            @endif
        </div>
        @else
        <p class="text-gray-500">Result not yet published.</p>
        @endif

        @if($attempt->answers->count())
        <h3 class="text-lg font-semibold mt-6 mb-3">Your Answers</h3>
        <div class="space-y-3">
            @foreach($attempt->answers as $index => $answer)
            <div class="border rounded-lg p-4">
                <p class="font-medium text-sm">Question {{ $index + 1 }}</p>
                <p class="text-sm text-gray-600 mb-2">{{ $answer->question->question_text ?? '—' }}</p>
                <p class="text-sm">
                    <span class="text-gray-500">Your answer:</span>
                    <span class="font-medium">{{ is_array($answer->student_answer) ? implode(', ', $answer->student_answer) : ($answer->student_answer ?? $answer->essay_answer ?? '—') }}</span>
                </p>
                @if($answer->marks_obtained !== null)
                <p class="text-sm text-gray-500">Score: {{ $answer->marks_obtained }}/{{ $answer->max_marks }}</p>
                @endif
                @if($answer->feedback)
                <p class="text-sm text-blue-600 mt-1">Feedback: {{ $answer->feedback }}</p>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
