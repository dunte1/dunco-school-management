@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Edit Exam</h1>

    @if($errors->any())
        <div class="bg-red-100 text-red-800 rounded p-3 mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('examination.exams.update', $exam) }}" class="bg-white rounded shadow p-4 space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $exam->name) }}" class="w-full border rounded px-3 py-2">
        </div>
        <div>
            <label class="block mb-1">Exam Type</label>
            <select name="exam_type_id" class="w-full border rounded px-3 py-2">
                @foreach($examTypes as $type)
                    <option value="{{ $type->id }}" {{ (int) old('exam_type_id', $exam->exam_type_id) === $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block mb-1">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2">
                @foreach(['draft','published','ongoing','completed','archived'] as $status)
                    <option value="{{ $status }}" {{ old('status', $exam->status) === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block mb-1">Total Marks</label>
            <input type="number" step="0.01" name="total_marks" value="{{ old('total_marks', $exam->total_marks) }}" class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        <a href="{{ route('examination.exams.index') }}" class="ml-2 text-blue-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
