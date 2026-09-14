@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Edit Result</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-800 rounded p-3 mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('examination.results.update', $result) }}" class="bg-white rounded shadow p-4 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1">Total Marks</label>
            <input type="number" step="0.01" name="total_marks" value="{{ old('total_marks', $result->total_marks) }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block mb-1">Obtained Marks</label>
            <input type="number" step="0.01" name="obtained_marks" value="{{ old('obtained_marks', $result->obtained_marks) }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block mb-1">Grade</label>
            <input type="text" name="grade" value="{{ old('grade', $result->grade) }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block mb-1">Remarks</label>
            <textarea name="remarks" class="w-full border rounded px-3 py-2">{{ old('remarks', $result->remarks) }}</textarea>
        </div>
        <label class="inline-flex items-center">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" value="1" class="mr-2" {{ old('is_published', $result->is_published) ? 'checked' : '' }}> Published
        </label>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        <a href="{{ route('examination.results.index') }}" class="ml-2 text-blue-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
