@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Add Questions to: {{ $exam->name }}</h1>
        <a href="{{ route('examination.exams.show', $exam) }}" class="text-blue-600 hover:underline">&larr; Back to Exam</a>
    </div>

    <div class="bg-white rounded shadow p-4 mb-4">
        <form method="POST" action="{{ route('examination.exams.generate-questions', $exam) }}" class="flex items-end gap-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category_id" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Difficulty</label>
                <select name="difficulty" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Any</option>
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Count</label>
                <input type="number" name="count" value="10" min="1" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm w-20">
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Auto-Generate</button>
        </form>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Question</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Marks</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($exam->questions as $question)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($question->question_text, 80) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $question->category->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $question->marks }}</td>
                    <td class="px-6 py-4 text-sm">
                        <form method="POST" action="{{ route('examination.exams.remove-question', [$exam, $question]) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Remove this question?')">Remove</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No questions attached yet. Use the auto-generate tool above or add questions manually.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <h3 class="text-lg font-semibold mb-2">Available Questions (not yet attached)</h3>
        <div class="bg-white rounded shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Question</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Marks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($availableQuestions as $question)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($question->question_text, 80) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $question->category->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $question->marks }}</td>
                        <td class="px-6 py-4 text-sm">
                            <form method="POST" action="{{ route('examination.exams.add-questions', $exam) }}" class="inline">
                                @csrf
                                <input type="hidden" name="question_ids[]" value="{{ $question->id }}">
                                <button type="submit" class="text-green-600 hover:underline">Add</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">All questions are already attached to this exam.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
