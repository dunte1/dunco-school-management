@extends('layouts.app')

@section('title', 'Exam Result')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-5xl mx-auto px-4">
        <div class="bg-gray-800 rounded-xl border border-gray-700 p-8 mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">{{ $attempt->exam->name }} - Result</h1>
            <div class="flex flex-wrap gap-6 text-gray-300 text-sm mb-4">
                <div><span class="font-semibold text-white">Type:</span> {{ $attempt->exam->examType->name }}</div>
                <div><span class="font-semibold text-white">Academic Year:</span> {{ $attempt->exam->academic_year }}</div>
                <div><span class="font-semibold text-white">Term:</span> {{ $attempt->exam->term }}</div>
                <div><span class="font-semibold text-white">Total Marks:</span> {{ $attempt->exam->total_marks }}</div>
                <div><span class="font-semibold text-white">Obtained:</span> <span class="text-lg font-bold {{ $result && $result->percentage >= $attempt->exam->passing_marks ? 'text-green-400' : 'text-red-400' }}">{{ $result ? $result->obtained_marks : '-' }}</span></div>
                <div><span class="font-semibold text-white">Grade:</span> {{ $result ? $result->grade : '-' }}</div>
                <div><span class="font-semibold text-white">Status:</span> <span class="font-bold {{ $result && $result->result_status === 'pass' ? 'text-green-400' : 'text-red-400' }}">{{ $result ? strtoupper($result->result_status) : '-' }}</span></div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700 p-8">
            <h2 class="text-2xl font-semibold text-white mb-6">Question Feedback</h2>
            <div class="space-y-8">
                @foreach($answers as $answer)
                    @php
                        $q = $answer->question;
                        $isCorrect = $answer->is_correct;
                        $studentFile = $answer->file_path;
                        $showCorrect = $attempt->exam->show_results_immediately;
                    @endphp
                    <div class="p-6 rounded-lg border {{ $isCorrect === true ? 'border-green-600 bg-green-900/10' : ($isCorrect === false ? 'border-red-600 bg-red-900/10' : 'border-gray-700 bg-gray-900/30') }}">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-bold text-white">Q{{ $loop->iteration }}. {{ $q->question_text }}</h3>
                            <span class="text-xs px-3 py-1 rounded-full {{ $isCorrect === true ? 'bg-green-700 text-white' : ($isCorrect === false ? 'bg-red-700 text-white' : 'bg-gray-700 text-gray-300') }}">
                                {{ ucfirst($q->type) }} | {{ $q->marks }} mark{{ $q->marks == 1 ? '' : 's' }}
                            </span>
                        </div>
                        <div class="mb-2">
                            <span class="font-semibold text-gray-300">Your Answer:</span>
                            @if($q->type === 'essay' && $studentFile)
                                <a href="{{ route('download.exam.answer.file', ['answer' => $answer->id]) }}" class="text-blue-400 underline ml-2" target="_blank">Download Uploaded File</a>
                                @if($answer->essay_answer)
                                    <div class="mt-2 text-gray-200 bg-gray-700 rounded p-2">{{ $answer->essay_answer }}</div>
                                @endif
                            @elseif($q->type === 'essay')
                                <div class="mt-2 text-gray-200 bg-gray-700 rounded p-2">{{ $answer->essay_answer ?: '—' }}</div>
                            @elseif($q->type === 'coding')
                                <pre class="mt-2 bg-gray-900 text-green-300 rounded p-2 overflow-x-auto">{{ $answer->code_answer ?: '—' }}</pre>
                            @elseif(is_array($answer->student_answer))
                                <div class="mt-2 text-gray-200 bg-gray-700 rounded p-2">{{ implode(', ', $answer->student_answer) ?: '—' }}</div>
                            @else
                                <div class="mt-2 text-gray-200 bg-gray-700 rounded p-2">{{ $answer->student_answer ?: '—' }}</div>
                            @endif
                        </div>
                        @if($showCorrect)
                        <div class="mb-2">
                            <span class="font-semibold text-gray-300">Correct Answer:</span>
                            @if($q->type === 'essay')
                                <span class="ml-2 text-gray-400 italic">Subjective (teacher graded)</span>
                            @elseif($q->type === 'coding')
                                <span class="ml-2 text-gray-400 italic">See feedback</span>
                            @elseif(is_array($q->correct_answers))
                                <span class="ml-2 text-green-400">{{ implode(', ', $q->correct_answers) }}</span>
                            @else
                                <span class="ml-2 text-green-400">{{ $q->correct_answers ?? '—' }}</span>
                            @endif
                        </div>
                        @endif
                        @if($q->explanation || $q->feedback || $answer->feedback)
                        <div class="mb-2">
                            <span class="font-semibold text-gray-300">Feedback:</span>
                            <div class="mt-2 text-blue-200 bg-blue-900/40 rounded p-2">
                                {{ $answer->feedback ?? $q->feedback ?? $q->explanation }}
                            </div>
                        </div>
                        @endif
                        <div class="flex items-center gap-4 mt-2">
                            <span class="text-xs text-gray-400">Answered at: {{ $answer->answered_at ? $answer->answered_at->format('M d, Y H:i') : '—' }}</span>
                            <span class="text-xs text-gray-400">Marks: <span class="font-bold text-white">{{ $answer->marks_obtained ?? 0 }}</span> / {{ $q->marks }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection 