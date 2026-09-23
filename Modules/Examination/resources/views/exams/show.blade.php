@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex items-center gap-3 mb-4">
        <a href="{{ route('examination.exams.index') }}" class="text-blue-600 hover:underline">&larr; Back</a>
        <h1 class="text-2xl font-bold">{{ $exam->name }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Exam Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded shadow p-6 space-y-3">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Code</p>
                        <p class="font-medium">{{ $exam->code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Type</p>
                        <p class="font-medium">{{ $exam->type->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Academic Year</p>
                        <p class="font-medium">{{ $exam->academic_year }} ({{ $exam->term }})</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            {{ $exam->status === 'published' ? 'bg-green-100 text-green-800' :
                               ($exam->status === 'ongoing' ? 'bg-blue-100 text-blue-800' :
                               ($exam->status === 'completed' ? 'bg-gray-100 text-gray-800' : 'bg-yellow-100 text-yellow-800')) }}">
                            {{ ucfirst($exam->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Dates</p>
                        <p class="font-medium">{{ $exam->start_date }} → {{ $exam->end_date }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Duration</p>
                        <p class="font-medium">{{ $exam->duration_minutes ?? '—' }} minutes</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total / Passing Marks</p>
                        <p class="font-medium">{{ $exam->total_marks }} / {{ $exam->passing_marks }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Results</p>
                        <p class="font-medium">{{ $exam->results_count ?? $exam->results()->count() }}</p>
                    </div>
                </div>

                @if($exam->description)
                <div class="border-t pt-3">
                    <p class="text-sm text-gray-500 mb-1">Description</p>
                    <p class="text-gray-700">{{ $exam->description }}</p>
                </div>
                @endif

                <div class="border-t pt-3 flex flex-wrap gap-2">
                    @if($exam->is_online)
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">Online Exam</span>
                    @endif
                    @if($exam->enable_proctoring)
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Proctored</span>
                    @endif
                    @if($exam->shuffle_questions)
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">Shuffled</span>
                    @endif
                    @if($exam->negative_marking)
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">Negative Marking</span>
                    @endif
                </div>
            </div>

            <!-- Schedules -->
            @if($exam->schedules->count())
            <div class="bg-white rounded shadow p-6 mt-4">
                <h3 class="text-lg font-semibold mb-3">Exam Schedule</h3>
                <div class="space-y-3">
                    @foreach($exam->schedules as $schedule)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium">{{ $schedule->class_name }}{{ $schedule->section ? ' - ' . $schedule->section : '' }}</p>
                                <p class="text-sm text-gray-600">{{ $schedule->subject ?? 'All Subjects' }}</p>
                            </div>
                            <div class="text-right text-sm">
                                <p class="font-medium">{{ $schedule->exam_date }}</p>
                                <p class="text-gray-600">{{ date('g:i A', strtotime($schedule->start_time)) }} - {{ date('g:i A', strtotime($schedule->end_time)) }}</p>
                                @if($schedule->room_number)<p class="text-gray-500">Room: {{ $schedule->room_number }}</p>@endif
                            </div>
                        </div>
                        @if($schedule->instructions)
                            <p class="text-sm text-gray-500 mt-2 italic">{{ $schedule->instructions }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <!-- Fee Information -->
            @if($exam->fee_required && $exam->fee_amount)
            <div class="bg-white rounded shadow p-6">
                <h3 class="text-lg font-semibold mb-3">Exam Fee</h3>
                <div class="text-center mb-4">
                    <p class="text-3xl font-bold text-green-600">{{ $exam->currency ?? 'KES' }} {{ number_format($exam->fee_amount, 2) }}</p>
                    <p class="text-sm text-gray-500">{{ $exam->fee_description ?? 'Examination Fee' }}</p>
                </div>

                @php
                    $student = Auth::user()->academicStudent;
                    $isPaid = $student ? \Modules\Examination\Models\ExamPayment::where('exam_id', $exam->id)
                        ->where('student_id', $student->id)
                        ->where('status', 'completed')
                        ->exists() : false;
                @endphp

                @if($isPaid)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                        <p class="text-sm font-medium text-green-800">Payment Confirmed</p>
                        <a href="{{ route('examination.payments.index') }}" class="text-xs text-green-600 hover:underline">View Payment History</a>
                    </div>
                @else
                    <a href="{{ route('examination.payments.select-method', $exam) }}" class="block w-full bg-green-600 text-white py-3 rounded-lg font-medium hover:bg-green-700 transition text-center">
                        Pay Now
                    </a>
                    <p class="text-xs text-gray-500 text-center mt-2">Payment required before starting the exam</p>
                @endif
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white rounded shadow p-6">
                <h3 class="text-lg font-semibold mb-3">Actions</h3>
                <div class="space-y-2">
                    @if($exam->is_online && $exam->status === 'published')
                        @if(!$isPaid && $exam->fee_required && $exam->fee_amount)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <p class="text-sm text-yellow-800">Complete payment to start the exam</p>
                            </div>
                        @else
                            <a href="{{ route('examination.online.start', $exam) }}" class="block w-full bg-blue-600 text-white py-2 rounded text-center hover:bg-blue-700">Start Online Exam</a>
                        @endif
                    @endif

                    @if(!$exam->is_online && $exam->status === 'published')
                        @if(!$isPaid && $exam->fee_required && $exam->fee_amount)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <p class="text-sm text-yellow-800">Complete payment to access exam details</p>
                            </div>
                        @else
                            <a href="{{ route('examination.exams.show', $exam) }}" class="block w-full bg-green-600 text-white py-2 rounded text-center hover:bg-green-700">View Exam Details</a>
                        @endif
                    @endif

                    <a href="{{ route('examination.exams.results', $exam) }}" class="block w-full bg-gray-100 text-gray-700 py-2 rounded text-center hover:bg-gray-200">View Results</a>
                    <a href="{{ route('examination.exams.edit', $exam) }}" class="block w-full bg-yellow-500 text-white py-2 rounded text-center hover:bg-yellow-600">Edit Exam</a>
                </div>
            </div>

            <!-- Payment History -->
            @if($exam->fee_required && $exam->fee_amount)
            <div class="bg-white rounded shadow p-6">
                <h3 class="text-lg font-semibold mb-3">Payment History</h3>
                @php
                    $payments = \Modules\Examination\Models\ExamPayment::where('exam_id', $exam->id)
                        ->where('school_id', Auth::user()->school_id)
                        ->with('student')
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp
                @if($payments->count())
                    <div class="space-y-2">
                        @foreach($payments as $p)
                        <div class="flex items-center justify-between text-sm">
                            <div>
                                <p class="font-medium">{{ $p->student->name ?? '—' }}</p>
                                <p class="text-xs text-gray-500">{{ $p->reference }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $p->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">No payments recorded.</p>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
