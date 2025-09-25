@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Manage Users for Role: {{ $role->name }}</h1>
    <form action="{{ route('finance.roles.assignUser', $role) }}" method="POST" class="mb-6 bg-white p-4 rounded shadow">
        @csrf
        <div class="flex items-center space-x-4">
            <select name="user_id" class="border rounded px-3 py-2 text-black" required>
                <option value="">Select User</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Assign</button>
        </div>
    </form>
    <h2 class="text-xl font-semibold mb-2">Assigned Users</h2>
    <ul class="bg-white rounded shadow divide-y">
        @forelse($assigned as $user)
            <li class="flex items-center justify-between px-4 py-2">
                <span>{{ $user->name }} ({{ $user->email }})</span>
                <form action="{{ route('finance.roles.removeUser', [$role, $user->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Remove this user?')">Remove</button>
                </form>
            </li>
        @empty
            <li class="px-4 py-2 text-gray-500">No users assigned.</li>
        @endforelse
    </ul>
    <a href="{{ route('finance.roles.index') }}" class="mt-4 inline-block text-blue-200 hover:underline">&larr; Back to Roles</a>
</div>
@endsection 