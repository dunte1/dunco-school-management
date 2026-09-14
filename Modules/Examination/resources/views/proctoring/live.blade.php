@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Live Monitoring {{ isset($examModel) && $examModel ? '— '.$examModel->name : '' }}</h1>

    <div class="bg-white rounded shadow p-4">
        <p class="text-gray-500">
            Live monitoring requires the realtime proctoring channel to be enabled.
        </p>
    </div>

    <a href="{{ route('examination.proctoring.index') }}" class="inline-block mt-4 text-blue-600 hover:underline">&larr; Back</a>
</div>
@endsection
