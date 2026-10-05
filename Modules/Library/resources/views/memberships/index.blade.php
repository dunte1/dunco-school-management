@extends('layouts.app')

@section('title', 'Library Memberships')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Library Memberships</h4>
                    <a href="{{ route('library.memberships.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Membership
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('library.memberships.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Search members..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="member_id" class="form-control">
                                    <option value="">All Members</option>
                                    @foreach($members as $member)
                                        <option value="{{ $member->id }}" {{ request('member_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="student" {{ request('type') == 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="faculty" {{ request('type') == 'faculty' ? 'selected' : '' }}>Faculty</option>
                                    <option value="staff" {{ request('type') == 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="external" {{ request('type') == 'external' ? 'selected' : '' }}>External</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary me-2">Filter</button>
                                <a href="{{ route('library.memberships.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>

                    <!-- Memberships Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Member</th>
                                    <th>Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Fee</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($memberships as $membership)
                                <tr>
                                    <td>{{ $membership->member->name ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($membership->type) }}</td>
                                    <td>{{ $membership->start_date ? \Carbon\Carbon::parse($membership->start_date)->format('M d, Y') : 'N/A' }}</td>
                                    <td>{{ $membership->end_date ? \Carbon\Carbon::parse($membership->end_date)->format('M d, Y') : 'N/A' }}</td>
                                    <td>${{ number_format($membership->fee, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $membership->status == 'active' ? 'success' : ($membership->status == 'expired' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($membership->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('library.memberships.show', $membership->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('library.memberships.edit', $membership->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('library.memberships.destroy', $membership->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this membership?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No library memberships found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $memberships->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
