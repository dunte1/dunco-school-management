@extends('layouts.app')

@section('title', 'Staff Contracts')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Staff Contracts</h4>
                    <a href="{{ route('hr.staff.contracts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Contract
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('hr.staff.contracts.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search staff..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="staff_id" class="form-control">
                                    <option value="">All Staff</option>
                                    @foreach($staff as $member)
                                        <option value="{{ $member->id }}" {{ request('staff_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->first_name }} {{ $member->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-secondary me-2">Filter</button>
                                <a href="{{ route('hr.staff.contracts.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>

                    <!-- Contracts Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Staff</th>
                                    <th>Contract Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Salary</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contracts as $contract)
                                <tr>
                                    <td>{{ $contract->staff->first_name ?? '' }} {{ $contract->staff->last_name ?? '' }}</td>
                                    <td>{{ $contract->contract_type }}</td>
                                    <td>{{ $contract->start_date ? \Carbon\Carbon::parse($contract->start_date)->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $contract->end_date ? \Carbon\Carbon::parse($contract->end_date)->format('M d, Y') : 'N/A' }}</td>
                                    <td>${{ number_format($contract->salary, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $contract->status == 'active' ? 'success' : ($contract->status == 'expired' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($contract->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('hr.staff.contracts.show', $contract->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('hr.staff.contracts.edit', $contract->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('hr.staff.contracts.destroy', $contract->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this contract?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No staff contracts found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $contracts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
