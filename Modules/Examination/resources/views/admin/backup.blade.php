@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Examination Backup</h1>

    <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded p-4 mb-4">
        Database backups are managed by the platform backup configuration. Use the system
        backup command or the admin backup settings to schedule exports.
    </div>

    <a href="{{ route('examination.admin.dashboard') }}" class="text-blue-600 hover:underline">&larr; Back</a>
</div>
@endsection
