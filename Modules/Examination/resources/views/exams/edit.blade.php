@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Edit Exam</h1>
    @if($errors->any())
        <div class="bg-red-100 text-red-800 rounded p-3 mb-4">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif
    <form method="POST" action="{{ route('examination.exams.update', $exam) }}" class="bg-white rounded shadow p-4 space-y-4">
        @csrf @method('PUT')
        <div><label class="block mb-1">Name *</label><input name="name" class="w-full border rounded px-3 py-2" value="{{ old('name', $exam->name) }}" required></div>
        <div><label class="block mb-1">Code *</label><input name="code" class="w-full border rounded px-3 py-2" value="{{ old('code', $exam->code) }}" required></div>
        <div><label class="block mb-1">Status</label><select name="status" class="w-full border rounded px-3 py-2">@foreach(['draft','published','ongoing','completed','archived'] as $s)<option value="{{ $s }}" {{ old('status', $exam->status)===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block mb-1">Total Marks</label><input type="number" name="total_marks" class="w-full border rounded px-3 py-2" value="{{ old('total_marks', $exam->total_marks) }}"></div>
            <div><label class="block mb-1">Passing Marks</label><input type="number" name="passing_marks" class="w-full border rounded px-3 py-2" value="{{ old('passing_marks', $exam->passing_marks) }}"></div>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        <a href="{{ route('examination.exams.index') }}" class="ml-2 text-blue-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
