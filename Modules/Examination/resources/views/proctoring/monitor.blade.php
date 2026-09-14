@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Proctoring Monitor {{ isset($exam) && $exam ? '— '.$exam->name : '' }}</h1>

    <div class="bg-white rounded shadow p-4">
        <p class="text-gray-500">
            Live proctoring stream is not available until the realtime integration is enabled.
        </p>
    </div>

    <a href="{{ route('examination.proctoring.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">&larr; Back</a>
</div>
@endsection
