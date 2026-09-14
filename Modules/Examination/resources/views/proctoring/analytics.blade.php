@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Proctoring Analytics</h1>

    @php($logCount = \Modules\Examination\Models\ProctoringLog::count())
    @php($unresolved = \Modules\Examination\Models\ProctoringLog::where('is_resolved', false)->count())

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-blue-50 rounded-lg p-5 text-center shadow-sm">
            <div class="text-2xl font-bold">{{ $logCount }}</div>
            <div class="text-sm text-gray-500">Total Proctoring Events</div>
        </div>
        <div class="bg-red-50 rounded-lg p-5 text-center shadow-sm">
            <div class="text-2xl font-bold">{{ $unresolved }}</div>
            <div class="text-sm text-gray-500">Unresolved Events</div>
        </div>
    </div>
</div>
@endsection
