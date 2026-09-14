@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Results Analytics</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-blue-50 rounded-lg p-5 text-center shadow-sm">
            <div class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</div>
            <div class="text-sm text-gray-500">Total Results</div>
        </div>
        <div class="bg-green-50 rounded-lg p-5 text-center shadow-sm">
            <div class="text-2xl font-bold">{{ $stats['published'] ?? 0 }}</div>
            <div class="text-sm text-gray-500">Published</div>
        </div>
        <div class="bg-yellow-50 rounded-lg p-5 text-center shadow-sm">
            <div class="text-2xl font-bold">{{ $stats['average'] ?? 0 }}%</div>
            <div class="text-sm text-gray-500">Average Score</div>
        </div>
        <div class="bg-indigo-50 rounded-lg p-5 text-center shadow-sm">
            <div class="text-2xl font-bold">{{ $stats['pass'] ?? 0 }}</div>
            <div class="text-sm text-gray-500">Passed</div>
        </div>
    </div>
</div>
@endsection
