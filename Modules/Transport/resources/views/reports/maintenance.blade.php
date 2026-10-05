@extends('layouts.app')

@section('title', 'Maintenance Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Maintenance Reports</h3>
                    <div class="card-tools">
                        <a href="{{ route('transport.reports.export', ['type' => 'maintenance']) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Vehicle</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Cost</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($maintenance as $item)
                                <tr>
                                    <td>{{ $item->vehicle->vehicle_number ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($item->type) }}</td>
                                    <td>{{ Str::limit($item->description, 50) }}</td>
                                    <td>KSh {{ number_format($item->cost, 2) }}</td>
                                    <td>{{ $item->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $item->status === 'completed' ? 'success' : ($item->status === 'in_progress' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('transport.maintenance.show', $item->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No maintenance records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $maintenance->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
