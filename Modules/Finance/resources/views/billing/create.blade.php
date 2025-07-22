@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Create Invoice</h1>
    <form action="{{ route('finance.billing.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">Student</label>
            <select name="student_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Select Student --</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Fees</label>
            <select name="fees[]" class="w-full border rounded px-3 py-2" multiple required>
                @foreach($fees as $fee)
                    <option value="{{ $fee->id }}">{{ $fee->name }} ({{ number_format($fee->amount, 2) }})</option>
                @endforeach
            </select>
            <small class="text-gray-500">Hold Ctrl (Windows) or Command (Mac) to select multiple fees.</small>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Due Date</label>
            <input type="date" name="due_date" class="w-full border rounded px-3 py-2" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create</button>
        <a href="{{ route('finance.billing.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection 