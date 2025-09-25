@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Tax Rules</h1>
    <a href="{{ route('finance.taxes.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4 inline-block">Add Tax Rule</a>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Rate</th>
                <th class="px-4 py-2">Type</th>
                <th class="px-4 py-2">Active</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($taxes as $tax)
            <tr>
                <td class="border px-4 py-2">{{ $tax->name }}</td>
                <td class="border px-4 py-2">{{ $tax->rate }}{{ $tax->type == 'percentage' ? '%' : '' }}</td>
                <td class="border px-4 py-2">{{ ucfirst($tax->type) }}</td>
                <td class="border px-4 py-2">{{ $tax->active ? 'Yes' : 'No' }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('finance.taxes.edit', $tax) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('finance.taxes.destroy', $tax) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2" onclick="return confirm('Delete this tax rule?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 