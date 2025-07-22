@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Edit Tax Rule</h1>
    <form action="{{ route('finance.taxes.update', $tax) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ $tax->name }}" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Rate (%)</label>
            <input type="number" name="rate" step="0.01" class="w-full border rounded px-3 py-2" value="{{ $tax->rate }}" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Type</label>
            <select name="type" class="w-full border rounded px-3 py-2" required>
                <option value="percentage" @if($tax->type == 'percentage') selected @endif>Percentage</option>
                <option value="fixed" @if($tax->type == 'fixed') selected @endif>Fixed</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2">{{ $tax->description }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Active</label>
            <input type="checkbox" name="active" value="1" @if($tax->active) checked @endif>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        <a href="{{ route('finance.taxes.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection 