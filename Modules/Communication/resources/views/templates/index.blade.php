@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Message Templates</h1>
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="email" {{ request('type') == 'email' ? 'selected' : '' }}>Email</option>
                <option value="sms" {{ request('type') == 'sms' ? 'selected' : '' }}>SMS</option>
                <option value="notification" {{ request('type') == 'notification' ? 'selected' : '' }}>Notification</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search templates...">
            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
        </form>
        <a href="{{ route('communication.templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Template
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($templates->count())
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($templates as $template)
                    <tr>
                        <td>
                            <strong>{{ $template->name }}</strong>
                            <br>
                            <small class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($template->body), 50) }}</small>
                        </td>
                        <td>{{ $template->subject }}</td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($template->type) }}</span>
                        </td>
                        <td>
                            <span class="badge {{ $template->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $template->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $template->creator->name ?? 'Unknown' }}</td>
                        <td>{{ $template->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('communication.templates.show', $template) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('communication.templates.edit', $template) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('communication.templates.toggle-status', $template) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-toggle-{{ $template->is_active ? 'off' : 'on' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('communication.templates.destroy', $template) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this template?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
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
    {{ $templates->links() }}
@else
    <div class="text-center py-5">
        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
        <h4>No templates found</h4>
        <p class="text-muted">Create your first message template to get started.</p>
        <a href="{{ route('communication.templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Create Template
        </a>
    </div>
@endif
@endsection
