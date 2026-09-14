@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Add Exam Type</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-800 rounded p-3 mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('examination.exam-types.store') }}" class="bg-white rounded shadow p-4 space-y-4">
        @csrf

        <div>
            <label class="block mb-1">Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block mb-1">Code *</label>
            <input type="text" name="code" value="{{ old('code') }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block mb-1">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
        </div>
        <div class="flex gap-6">
            <label class="inline-flex items-center">
                <input type="hidden" name="is_online" value="0">
                <input type="checkbox" name="is_online" value="1" class="mr-2" {{ old('is_online') ? 'checked' : '' }}> Online
            </label>
            <label class="inline-flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="mr-2" {{ old('is_active', true) ? 'checked' : '' }}> Active
            </label>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create</button>
        <a href="{{ route('examination.exam-types.index') }}" class="ml-2 text-blue-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
