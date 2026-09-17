@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Create Examination</h1>
    @if($errors->any())
        <div class="bg-red-100 text-red-800 rounded p-3 mb-4">
            <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif
    <form method="POST" action="{{ route('examination.exams.store') }}" class="bg-white rounded shadow p-4 space-y-4">
        @csrf
        <div><label class="block mb-1">Name *</label><input name="name" class="w-full border rounded px-3 py-2" value="{{ old('name') }}" required></div>
        <div><label class="block mb-1">Code *</label><input name="code" class="w-full border rounded px-3 py-2" value="{{ old('code') }}" required></div>
        <div><label class="block mb-1">Exam Type *</label><select name="exam_type_id" class="w-full border rounded px-3 py-2" required>@foreach($examTypes as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach</select></div>
        <div><label class="block mb-1">Academic Year *</label><input name="academic_year" class="w-full border rounded px-3 py-2" value="{{ old('academic_year') }}" required></div>
        <div><label class="block mb-1">Term *</label><input name="term" class="w-full border rounded px-3 py-2" value="{{ old('term') }}" required></div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block mb-1">Start Date *</label><input type="date" name="start_date" class="w-full border rounded px-3 py-2" value="{{ old('start_date') }}" required></div>
            <div><label class="block mb-1">End Date *</label><input type="date" name="end_date" class="w-full border rounded px-3 py-2" value="{{ old('end_date') }}" required></div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block mb-1">Total Marks *</label><input type="number" name="total_marks" class="w-full border rounded px-3 py-2" value="{{ old('total_marks', 100) }}" required></div>
            <div><label class="block mb-1">Passing Marks *</label><input type="number" name="passing_marks" class="w-full border rounded px-3 py-2" value="{{ old('passing_marks', 50) }}" required></div>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Exam</button>
        <a href="{{ route('examination.exams.index') }}" class="ml-2 text-blue-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
