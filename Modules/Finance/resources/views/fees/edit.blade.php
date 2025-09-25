@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Fee</h1>
    
    @if ($errors->any())
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300">
            <ul class="mb-0 ps-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('finance.fees.update', $fee) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name', $fee->name) }}" class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" required>
            @error('name')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1">Amount</label>
            <input type="number" name="amount" step="0.01" value="{{ old('amount', $fee->amount) }}" class="w-full border rounded px-3 py-2 @error('amount') border-red-500 @enderror" required>
            @error('amount')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror">{{ old('description', $fee->description) }}</textarea>
            @error('description')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1">Category</label>
            <select name="fee_category_id" class="w-full border rounded px-3 py-2 @error('fee_category_id') border-red-500 @enderror">
                <option value="">-- Select Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @if(old('fee_category_id', $fee->fee_category_id) == $category->id) selected @endif>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('fee_category_id')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1">Type</label>
            <select name="fee_type_id" class="w-full border rounded px-3 py-2 @error('fee_type_id') border-red-500 @enderror">
                <option value="">-- Select Type --</option>
                @foreach($types as $type)
                    <option value="{{ $type->id }}" @if(old('fee_type_id', $fee->fee_type_id) == $type->id) selected @endif>{{ $type->name }}</option>
                @endforeach
            </select>
            @error('fee_type_id')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        <a href="{{ route('finance.fees.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection 