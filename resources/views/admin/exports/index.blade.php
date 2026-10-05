@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-download me-2"></i>Data Exports</h3>
        <button class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Export
        </button>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Export History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Filename</th>
                            <th>Status</th>
                            <th>Records Exported</th>
                            <th>File Size</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exportHistory as $export)
                        <tr>
                            <td>{{ $export['id'] }}</td>
                            <td><strong>{{ $export['type'] }}</strong></td>
                            <td>{{ $export['filename'] }}</td>
                            <td>
                                <span class="badge bg-{{ $export['status'] === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($export['status']) }}
                                </span>
                            </td>
                            <td>{{ number_format($export['records_exported']) }}</td>
                            <td>{{ $export['file_size'] }}</td>
                            <td>{{ $export['user'] }}</td>
                            <td>{{ $export['created_at']->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary">Download</button>
                                    <button class="btn btn-sm btn-outline-secondary">View Details</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
