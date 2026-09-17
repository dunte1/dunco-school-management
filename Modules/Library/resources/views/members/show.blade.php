@extends('library::LibraryLayout')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Members Show</h1>
    <div class="bg-white rounded shadow p-4 space-y-2">
                <p><strong>member</strong> {{ member ?? '—' }}</p>
        <p><strong>borrows</strong> {{ borrows ?? '—' }}</p>
    </div>
    <a href="{{ url()->previous() }}" class="inline-block mt-4 text-blue-600 hover:underline">&larr; Back</a>
</div>
@endsection