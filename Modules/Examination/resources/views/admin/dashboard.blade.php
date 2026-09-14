@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Examination Administration</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('examination.exams.index') }}" class="bg-blue-50 rounded-lg p-6 text-center shadow-sm hover:shadow">Manage Exams</a>
        <a href="{{ route('examination.questions.index') }}" class="bg-green-50 rounded-lg p-6 text-center shadow-sm hover:shadow">Question Bank</a>
        <a href="{{ route('examination.results.index') }}" class="bg-yellow-50 rounded-lg p-6 text-center shadow-sm hover:shadow">Results</a>
    </div>
</div>
@endsection
