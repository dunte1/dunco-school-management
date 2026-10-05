@extends('layouts.app')

@section('title', 'Staff Documents')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Staff Documents</h4>
                    <a href="{{ route('hr.staff.documents.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>New Document
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('hr.staff.documents.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="staff_id" class="form-label">Staff Member</label>
                                <select name="staff_id" id="staff_id" class="form-select">
                                    <option value="">All Staff</option>
                                    @foreach($staff as $staffMember)
                                        <option value="{{ $staffMember->id }}" {{ request('staff_id') == $staffMember->id ? 'selected' : '' }}>
                                            {{ $staffMember->first_name }} {{ $staffMember->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="type" class="form-label">Document Type</label>
                                <select name="type" id="type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="id_card" {{ request('type') == 'id_card' ? 'selected' : '' }}>ID Card</option>
                                    <option value="certificate" {{ request('type') == 'certificate' ? 'selected' : '' }}>Certificate</option>
                                    <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" name="search" id="search" class="form-control" 
                                       value="{{ request('search') }}" placeholder="Search by staff name or ID...">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-outline-primary me-2">Filter</button>
                                <a href="{{ route('hr.staff.documents.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </div>
                    </form>

                    <!-- Documents Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Staff Member</th>
                                    <th>Document Type</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Upload Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $document)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    {{ substr($document->staff->first_name, 0, 1) }}{{ substr($document->staff->last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $document->staff->first_name }} {{ $document->staff->last_name }}</div>
                                                    <small class="text-muted">{{ $document->staff->staff_id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $document->type == 'contract' ? 'success' : ($document->type == 'id_card' ? 'info' : ($document->type == 'certificate' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $document->type)) }}
                                            </span>
                                        </td>
                                        <td>{{ $document->title }}</td>
                                        <td>{{ Str::limit($document->description, 50) }}</td>
                                        <td>{{ $document->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('hr.staff.documents.show', $document->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.staff.documents.edit', $document->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('hr.staff.documents.destroy', $document->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Are you sure you want to delete this document?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No staff documents found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($documents->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $documents->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
