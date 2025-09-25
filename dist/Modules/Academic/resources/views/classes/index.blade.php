@extends('academic::components.layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chalkboard-teacher text-primary"></i>
            Academic Classes
        </h1>
        <a href="{{ route('academic.classes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Class
        </a>
    </div>

    <!-- Search and Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('academic.classes.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Class name, code, or description">
                </div>
                <div class="col-md-2">
                    <label for="academic_year" class="form-label">Academic Year</label>
                    <select class="form-select" id="academic_year" name="academic_year">
                        <option value="">All Years</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="teacher_id" class="form-label">Teacher</label>
                    <select class="form-select" id="teacher_id" name="teacher_id">
                        <option value="">All Teachers</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Search
                    </button>
                    <a href="{{ route('academic.classes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Classes Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Classes List</h6>
        </div>
        <div class="card-body">
            @if($classes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Code</th>
                                <th>Teacher</th>
                                <th>Students</th>
                                <th>Academic Year</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classes as $class)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-chalkboard-teacher text-white"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $class->name }}</strong>
                                            @if($class->description)
                                                <br><small class="text-muted">{{ Str::limit($class->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td><code>{{ $class->code }}</code></td>
                                <td>
                                    @if($class->teacher)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            {{ $class->teacher->name }}
                                        </div>
                                    @else
                                        <span class="text-muted">Not Assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">{{ $class->students_count ?? 0 }}/{{ $class->capacity }}</span>
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            @php
                                                $percentage = $class->capacity > 0 ? (($class->students_count ?? 0) / $class->capacity) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar {{ $percentage >= 90 ? 'bg-danger' : ($percentage >= 75 ? 'bg-warning' : 'bg-success') }}" 
                                                 style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $class->academic_year }}</td>
                                <td>
                                    @if($class->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('academic.classes.show', $class->id) }}" 
                                           class="btn btn-info btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('academic.classes.edit', $class->id) }}" 
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('academic.classes.toggle-status', $class->id) }}" 
                                              class="d-inline" onsubmit="return confirm('Are you sure you want to change the status?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-{{ $class->is_active ? 'secondary' : 'success' }} btn-sm" 
                                                    title="{{ $class->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas fa-{{ $class->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('academic.classes.destroy', $class->id) }}" 
                                              class="d-inline" onsubmit="return confirm('Are you sure you want to delete this class?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $classes->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-chalkboard-teacher fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-500">No classes found</h5>
                    <p class="text-gray-400">Create your first class to get started.</p>
                    <a href="{{ route('academic.classes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create First Class
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-submit form when filters change
    document.querySelectorAll('#academic_year, #teacher_id, #status').forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
</script>
@endpush
@endsection 