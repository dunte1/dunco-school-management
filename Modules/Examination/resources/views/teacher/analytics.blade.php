@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Exam Analytics</h1>

    <div class="bg-white rounded shadow p-4">
        <a href="{{ route('examination.results.analytics') }}" class="text-blue-600 hover:underline">View results analytics &rarr;</a>
    </div>
</div>
@endsection
