<x-core::layouts.master>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Permission Details</h1>
            <div>
                <a href="{{ route('core.permissions.edit', $permission->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Permission
                </a>
                <a href="{{ route('core.permissions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Permissions
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Permission Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Name:</strong></div>
                            <div class="col-md-9">{{ $permission->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Display Name:</strong></div>
                            <div class="col-md-9">{{ $permission->display_name ?? $permission->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Description:</strong></div>
                            <div class="col-md-9">{{ $permission->description ?? 'No description' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Module:</strong></div>
                            <div class="col-md-9">{{ $permission->module ?? 'Core' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Created:</strong></div>
                            <div class="col-md-9">{{ $permission->created_at ? $permission->created_at->format('M d, Y H:i') : 'Unknown' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Last Updated:</strong></div>
                            <div class="col-md-9">{{ $permission->updated_at ? $permission->updated_at->format('M d, Y H:i') : 'Unknown' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <h4 class="text-primary">{{ $permission->roles ? $permission->roles->count() : 0 }}</h4>
                                <small class="text-muted">Roles</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success">{{ $permission->users ? $permission->users->count() : 0 }}</h4>
                                <small class="text-muted">Users</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($permission->roles && $permission->roles->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Assigned Roles ({{ $permission->roles->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Display Name</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permission->roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->display_name ?? $role->name }}</td>
                                        <td>{{ $role->description ?? 'No description' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-core::layouts.master> 