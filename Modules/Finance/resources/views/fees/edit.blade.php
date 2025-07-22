@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Fee</h1>
    <form action="{{ route('finance.fees.update', $fee) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ $fee->name }}" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Amount</label>
            <input type="number" name="amount" step="0.01" class="w-full border rounded px-3 py-2" value="{{ $fee->amount }}" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2">{{ $fee->description }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Category</label>
            <select name="fee_category_id" class="w-full border rounded px-3 py-2">
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if($fee->fee_category_id == $category->id) selected @endif>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Type</label>
            <select name="fee_type_id" class="w-full border rounded px-3 py-2">
                <option value="">-- Select Type --</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" @if($fee->fee_type_id == $type->id) selected @endif>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        <a href="{{ route('finance.fees.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection 