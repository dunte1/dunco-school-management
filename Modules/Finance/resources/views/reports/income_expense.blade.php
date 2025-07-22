@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Income & Expense Statement</h1>
    <form method="GET" class="mb-4">
        <div class="flex gap-4">
            <div>
                <label class="block mb-1">Date From</label>
                <input type="date" name="from" class="border rounded px-3 py-2">
            </div>
            <div>
                <label class="block mb-1">Date To</label>
                <input type="date" name="to" class="border rounded px-3 py-2">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            </div>
        </div>
    </form>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Type</th>
                <th class="px-4 py-2">Amount</th>
            </tr>
        </thead>
        <tbody>
            <!-- Placeholder rows -->
            <tr>
                <td class="border px-4 py-2">Income</td>
                <td class="border px-4 py-2">5000.00</td>
            </tr>
            <tr>
                <td class="border px-4 py-2">Expense</td>
                <td class="border px-4 py-2">2000.00</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection 