@extends('examination::layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Rankings {{ isset($examModel) && $examModel ? '— '.$examModel->name : '' }}</h1>

    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Student</th>
                <th class="px-4 py-2 text-left">Percentage</th>
                <th class="px-4 py-2 text-left">Grade</th>
            </tr>
        </thead>
        <tbody>
        @forelse($results as $i => $result)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $i + 1 }}</td>
                <td class="px-4 py-2">{{ $result->student->name ?? '—' }}</td>
                <td class="px-4 py-2">{{ $result->percentage }}%</td>
                <td class="px-4 py-2">{{ $result->grade ?? '—' }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">No results to rank.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
