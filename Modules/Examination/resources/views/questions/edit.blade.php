@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Edit Question</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-800 rounded p-3 mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('examination.questions.update', $question) }}" class="bg-white rounded shadow p-4 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1">Question Text</label>
            <textarea name="question_text" class="w-full border rounded px-3 py-2">{{ old('question_text', $question->question_text) }}</textarea>
        </div>
        <div>
            <label class="block mb-1">Type</label>
            <select name="type" class="w-full border rounded px-3 py-2">
                @foreach(['mcq','fill_blank','essay','true_false','short_answer','coding','matching'] as $type)
                    <option value="{{ $type }}" {{ old('type', $question->type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block mb-1">Marks</label>
            <input type="number" step="0.01" name="marks" value="{{ old('marks', $question->marks) }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block mb-1">Difficulty</label>
            <select name="difficulty" class="w-full border rounded px-3 py-2">
                @foreach(['easy','medium','hard'] as $d)
                    <option value="{{ $d }}" {{ old('difficulty', $question->difficulty) === $d ? 'selected' : '' }}>{{ ucfirst($d) }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        <a href="{{ route('examination.questions.index') }}" class="ml-2 text-blue-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
