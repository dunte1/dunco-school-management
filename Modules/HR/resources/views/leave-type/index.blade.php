@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Leave Types</h4>
                    <a href="{{ route('hr.leave-type.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Leave Type
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Default Days</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaveTypes as $leaveType)
                                    <tr>
                                        <td>{{ $leaveType->name }}</td>
                                        <td>{{ $leaveType->description ?? 'N/A' }}</td>
                                        <td>{{ $leaveType->default_days }}</td>
                                        <td>
                                            <span class="badge badge-{{ $leaveType->is_active ? 'success' : 'secondary' }}">
                                                {{ $leaveType->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('hr.leave-type.show', $leaveType->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('hr.leave-type.edit', $leaveType->id) }}" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('hr.leave-type.toggle-status', $leaveType->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-{{ $leaveType->is_active ? 'warning' : 'success' }}"
                                                            onclick="return confirm('Are you sure you want to {{ $leaveType->is_active ? 'deactivate' : 'activate' }} this leave type?')">
                                                        <i class="fas fa-{{ $leaveType->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('hr.leave-type.destroy', $leaveType->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this leave type?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No leave types found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $leaveTypes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
