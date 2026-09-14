@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Proctoring Settings</h1>

    <form method="POST" action="{{ url()->current() }}" class="bg-white rounded shadow p-4 space-y-4">
        @csrf

        <label class="flex items-center">
            <input type="hidden" name="tab_switch_detection" value="0">
            <input type="checkbox" name="tab_switch_detection" value="1" class="mr-2" checked> Detect tab switches
        </label>
        <label class="flex items-center">
            <input type="hidden" name="webcam_required" value="0">
            <input type="checkbox" name="webcam_required" value="1" class="mr-2"> Require webcam
        </label>
        <label class="flex items-center">
            <input type="hidden" name="face_detection" value="0">
            <input type="checkbox" name="face_detection" value="1" class="mr-2"> Face detection
        </label>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
    </form>
</div>
@endsection
