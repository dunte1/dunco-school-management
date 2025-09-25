@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Fees</h1>
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('finance.fees.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Fee</a>
        <a href="{{ route('finance.fee-types.index') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Manage Fee Types</a>
        <a href="{{ route('finance.fee-categories.index') }}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">Manage Fee Categories</a>
    </div>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Category</th>
                <th class="px-4 py-2">Type</th>
                <th class="px-4 py-2">Amount</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fees as $fee)
            <tr>
                <td class="border px-4 py-2">{{ $fee->name }}</td>
                <td class="border px-4 py-2">{{ $fee->category->name ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $fee->type->name ?? '-' }}</td>
                <td class="border px-4 py-2">{{ number_format($fee->amount, 2) }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('finance.fees.edit', $fee) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('finance.fees.destroy', $fee) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2" onclick="return confirm('Delete this fee?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 