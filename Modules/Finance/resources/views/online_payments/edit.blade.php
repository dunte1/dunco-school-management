@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Online Payment</h1>

    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 rounded p-4 mb-4">
        Online payment editing is not available until a gateway is configured.
    </div>

    <a href="{{ route('finance.online-payments.index') }}" class="text-blue-600 hover:underline">&larr; Back</a>
</div>
@endsection
