@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Finance Roles</h1>
    <a href="{{ route('finance.roles.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4 inline-block">Add Role</a>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Description</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
            <tr>
                <td class="border px-4 py-2">{{ $role->name }}</td>
                <td class="border px-4 py-2">{{ $role->description }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('finance.roles.edit', $role) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('finance.roles.destroy', $role) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2" onclick="return confirm('Delete this role?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 