@extends('layouts.app')

@section('title', 'Notification Templates')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Notification Templates</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTemplateModal">
            <i class="fas fa-plus me-1"></i> New Template
        </button>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">All</option>
                        <option value="email" {{ request('type') == 'email' ? 'selected' : '' }}>Email</option>
                        <option value="sms" {{ request('type') == 'sms' ? 'selected' : '' }}>SMS</option>
                        <option value="push" {{ request('type') == 'push' ? 'selected' : '' }}>Push</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter me-1"></i> Filter</button>
                    <a href="{{ route('notification.templates') }}" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Type</th>
                        <th>Channel</th>
                        <th>Subject</th>
                        <th>Variables</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $tpl)
                    <tr>
                        <td>{{ $tpl->id }}</td>
                        <td><strong>{{ $tpl->name }}</strong></td>
                        <td><code>{{ $tpl->slug }}</code></td>
                        <td><span class="badge bg-secondary">{{ ucfirst($tpl->type) }}</span></td>
                        <td><span class="badge bg-info">{{ ucfirst($tpl->channel) }}</span></td>
                        <td>{{ Str::limit($tpl->subject, 25) }}</td>
                        <td>
                            @if($tpl->variables && count($tpl->variables))
                                @foreach($tpl->variables as $var)
                                    <span class="badge bg-light text-dark">{{ $var }}</span>
                                @endforeach
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $tpl->is_active ? 'success' : 'secondary' }}">
                                {{ $tpl->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No templates found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $templates->links() }}
        </div>
    </div>

    <div class="modal fade" id="createTemplateModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('notification.templates.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create Notification Template</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Template Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Slug <span class="text-danger">*</span></label>
                                <input type="text" name="slug" class="form-control" required placeholder="e.g. welcome-email">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" required>
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                    <option value="push">Push</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Channel <span class="text-danger">*</span></label>
                                <select name="channel" class="form-select" required>
                                    <option value="email">Email</option>
                                    <option value="sms">SMS</option>
                                    <option value="both">Both</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="e.g. Welcome, {name}!">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Body <span class="text-danger">*</span></label>
                            <textarea name="body" class="form-control" rows="5" required placeholder="Use {variable_name} for dynamic content"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Variables (comma-separated)</label>
                            <input type="text" name="variables" class="form-control" placeholder="e.g. name, course, date">
                            <small class="text-muted">Define variables used in the template. They will be replaced when sending.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Template</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
