@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Grade Exams</h1>

    <div class="bg-white rounded shadow p-4">
        <p class="text-gray-500">Select an exam and student answers to grade. Grading is available from the exam results page.</p>
        <a href="{{ route('examination.exams.index') }}" class="inline-block mt-3 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Go to Exams</a>
    </div>
</div>
@endsection
