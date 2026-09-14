@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Exam Types</h1>
        <a href="{{ route('examination.exam-types.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Exam Type</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 rounded p-3 mb-4">{{ session('success') }}</div>
    @endif

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Code</th>
                <th class="px-4 py-2 text-left">Online</th>
                <th class="px-4 py-2 text-left">Active</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($types as $type)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $type->name }}</td>
                <td class="px-4 py-2">{{ $type->code }}</td>
                <td class="px-4 py-2">{{ $type->is_online ? 'Yes' : 'No' }}</td>
                <td class="px-4 py-2">{{ $type->is_active ? 'Yes' : 'No' }}</td>
                <td class="px-4 py-2 text-right space-x-2">
                    <a href="{{ route('examination.exam-types.show', $type) }}" class="text-blue-600 hover:underline">View</a>
                    <a href="{{ route('examination.exam-types.edit', $type) }}" class="text-yellow-600 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('examination.exam-types.destroy', $type) }}" class="inline" onsubmit="return confirm('Delete this exam type?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">No exam types yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
