@extends('layouts.app')

@section('title', 'System Backups')

@section('content')
<div class="container-fluid">
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0 d-flex align-items-center gap-2">
            <i class="fas fa-database"></i>
            <span>Backups</span>
        </h3>
        <a href="{{ route('admin.backups.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="fas fa-plus-circle"></i>
            <span>Create Backup</span>
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width:320px;">Path</th>
                            <th class="text-nowrap">Size</th>
                            <th class="text-nowrap">Modified</th>
                            <th class="text-center" style="width:180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($backups as $b)
                            <tr>
                                <td class="small text-break">{{ $b['path'] }}</td>
                                <td class="text-nowrap">{{ number_format($b['size']/1024, 2) }} KB</td>
                                <td class="text-nowrap">{{ \Carbon\Carbon::createFromTimestamp($b['modified'])->toDateTimeString() }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.backups.download', ['path' => $b['path']]) }}" class="btn btn-sm btn-outline-secondary me-2" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form action="{{ route('admin.backups.destroy') }}" method="post" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="path" value="{{ $b['path'] }}" />
                                        <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Delete this backup?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No backups found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


