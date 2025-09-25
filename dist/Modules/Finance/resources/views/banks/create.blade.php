@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-lg">
    <h1 class="text-2xl font-bold mb-4">Add Bank Account</h1>
    <form action="{{ route('finance.banks.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Account Number</label>
            <input type="text" name="account_number" class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Bank Name</label>
            <input type="text" name="bank_name" class="w-full border rounded px-3 py-2">
        </div>
        <div class="mb-4">
            <label class="block mb-1">Balance</label>
            <input type="number" name="balance" step="0.01" class="w-full border rounded px-3 py-2" required>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
        <a href="{{ route('finance.banks.index') }}" class="ml-2 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection 