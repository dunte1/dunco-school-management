@extends('finance::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Online Payment #{{ $id ?? '' }}</h1>

    <p class="text-gray-600">Online payment details are unavailable until a gateway is configured.</p>

    <a href="{{ route('finance.online-payments.index') }}" class="text-blue-600 hover:underline">&larr; Back</a>
</div>
@endsection
