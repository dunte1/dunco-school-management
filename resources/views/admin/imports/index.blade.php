@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-upload me-2"></i>Data Imports</h3>
        <button class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>New Import
        </button>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Import History</h5>
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
                            <th>Records Processed</th>
                            <th>Success Rate</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($importHistory as $import)
                        <tr>
                            <td>{{ $import['id'] }}</td>
                            <td><strong>{{ $import['type'] }}</strong></td>
                            <td>{{ $import['filename'] }}</td>
                            <td>
                                <span class="badge bg-{{ $import['status'] === 'completed' ? 'success' : 'warning' }}">
                                    {{ ucfirst($import['status']) }}
                                </span>
                            </td>
                            <td>{{ $import['records_processed'] }}</td>
                            <td>
                                @php
                                    $successRate = $import['records_processed'] > 0 ? 
                                        round(($import['records_successful'] / $import['records_processed']) * 100, 1) : 0;
                                @endphp
                                <div class="d-flex align-items-center">
                                    <div class="progress me-2" style="width: 60px; height: 6px;">
                                        <div class="progress-bar bg-{{ $successRate >= 90 ? 'success' : ($successRate >= 70 ? 'warning' : 'danger') }}" 
                                             style="width: {{ $successRate }}%"></div>
                                    </div>
                                    <span>{{ $successRate }}%</span>
                                </div>
                            </td>
                            <td>{{ $import['user'] }}</td>
                            <td>{{ $import['created_at']->format('M d, Y H:i') }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary">View Details</button>
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
