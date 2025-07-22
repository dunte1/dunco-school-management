@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Bank Accounts</h1>
    <a href="{{ route('finance.banks.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4 inline-block">Add Bank Account</a>
    <a href="{{ route('finance.banks.transfer') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block ml-2">Record Transfer</a>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Account Number</th>
                <th class="px-4 py-2">Bank Name</th>
                <th class="px-4 py-2">Balance</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
            <tr>
                <td class="border px-4 py-2">{{ $account->name }}</td>
                <td class="border px-4 py-2">{{ $account->account_number }}</td>
                <td class="border px-4 py-2">{{ $account->bank_name }}</td>
                <td class="border px-4 py-2">{{ number_format($account->balance, 2) }}</td>
                <td class="border px-4 py-2">
                    <a href="{{ route('finance.banks.show', $account) }}" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 