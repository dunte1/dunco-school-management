@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">New Receipt</h1>

    <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded p-4 mb-4">
        Receipts are generated from recorded payments. Open a payment and use its receipt action.
    </div>

    <a href="{{ route('finance.payments.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Go to Payments</a>
</div>
@endsection
