@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Online Payments</h1>

    <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded p-4 mb-4">
        Online payment gateway integration is not configured yet. Configure provider
        credentials to enable online collections.
    </div>

    <a href="{{ route('finance.online-payments.mpesa') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">M-Pesa</a>
    <a href="{{ route('finance.fees.index') }}" class="ml-2 bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Back to Fees</a>
</div>
@endsection
