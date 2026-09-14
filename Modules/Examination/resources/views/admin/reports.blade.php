@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Examination Reports</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('examination.results.index') }}" class="bg-blue-50 rounded-lg p-6 shadow-sm hover:shadow">Results Report</a>
        <a href="{{ route('examination.results.analytics') }}" class="bg-green-50 rounded-lg p-6 shadow-sm hover:shadow">Results Analytics</a>
        <a href="{{ route('examination.proctoring.analytics') }}" class="bg-yellow-50 rounded-lg p-6 shadow-sm hover:shadow">Proctoring Analytics</a>
    </div>
</div>
@endsection
