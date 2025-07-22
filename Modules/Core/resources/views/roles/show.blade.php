<x-core::layouts.master>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Role Details</h1>
            <div>
                <a href="{{ route('core.roles.edit', $role->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Role
                </a>
                <a href="{{ route('core.roles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Roles
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Role Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Name:</strong></div>
                            <div class="col-md-9">{{ $role->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Display Name:</strong></div>
                            <div class="col-md-9">{{ $role->display_name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Description:</strong></div>
                            <div class="col-md-9">{{ $role->description ?? 'No description' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Created:</strong></div>
                            <div class="col-md-9">{{ $role->created_at ? $role->created_at->format('M d, Y H:i') : 'Unknown' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Last Updated:</strong></div>
                            <div class="col-md-9">{{ $role->updated_at ? $role->updated_at->format('M d, Y H:i') : 'Unknown' }}</div>
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
                                <h4 class="text-primary">{{ $role->users ? $role->users->count() : 0 }}</h4>
                                <small class="text-muted">Users</small>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success">{{ $role->permissions ? $role->permissions->count() : 0 }}</h4>
                                <small class="text-muted">Permissions</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($role->permissions && $role->permissions->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Permissions ({{ $role->permissions->count() }})</h5>
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
                                    @foreach($role->permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->name }}</td>
                                        <td>{{ $permission->display_name ?? $permission->name }}</td>
                                        <td>{{ $permission->description ?? 'No description' }}</td>
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