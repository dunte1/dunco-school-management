@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Examination Settings</h1>

    <form method="POST" action="{{ url()->current() }}" class="bg-white rounded shadow p-4 space-y-4">
        @csrf

        <label class="flex items-center">
            <input type="hidden" name="allow_online_exams" value="0">
            <input type="checkbox" name="allow_online_exams" value="1" class="mr-2" checked> Allow online exams
        </label>
        <label class="flex items-center">
            <input type="hidden" name="enable_proctoring" value="0">
            <input type="checkbox" name="enable_proctoring" value="1" class="mr-2"> Enable proctoring by default
        </label>
        <label class="flex items-center">
            <input type="hidden" name="show_results_immediately" value="0">
            <input type="checkbox" name="show_results_immediately" value="1" class="mr-2"> Show results immediately
        </label>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
    </form>
</div>
@endsection
