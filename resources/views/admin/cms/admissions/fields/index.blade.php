@extends('layouts.app')
@section('title', 'Admission Fields')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-list-ul me-2 text-primary"></i>Admission Form Fields</h1>
            <p class="text-muted mb-0">Manage sections, fields, and document types for the admission form</p>
        </div>
        <a href="{{ route('admin.cms.admissions.fields.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Section
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            @forelse($sections as $section)
            <div class="card shadow-sm mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-layer-group me-2"></i>{{ $section->name }}</h5>
                    <div>
                        <span class="badge {{ $section->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $section->is_active ? 'Active' : 'Inactive' }}</span>
                        <form action="{{ route('admin.cms.admissions.fields.destroy', $section) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this section and all its fields?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @if($section->description)<p class="text-muted mb-3">{{ $section->description }}</p>@endif
                    @forelse($section->fields as $field)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <strong>{{ $field->name }}</strong>
                            <span class="badge bg-light text-dark ms-2">{{ $field->type }}</span>
                            @if($field->is_required)<span class="badge bg-danger ms-1">Required</span>@endif
                            @if($field->placeholder)<br><small class="text-muted">Placeholder: {{ $field->placeholder }}</small>@endif
                        </div>
                        <form action="{{ route('admin.cms.admissions.fields.destroy-field', $field) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this field?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm"><i class="fas fa-times"></i></button>
                        </form>
                    </div>
                    @empty
                    <p class="text-muted mb-0">No fields in this section yet.</p>
                    @endforelse
                    <form action="{{ route('admin.cms.admissions.fields.store-field', $section) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-3"><input type="text" name="name" class="form-control form-control-sm" placeholder="Field name" required></div>
                            <div class="col-md-2"><select name="type" class="form-select form-select-sm"><option>text</option><option>textarea</option><option>number</option><option>email</option><option>phone</option><option>date</option><option>select</option><option>file</option></select></div>
                            <div class="col-md-2"><input type="text" name="placeholder" class="form-control form-control-sm" placeholder="Placeholder"></div>
                            <div class="col-md-2"><div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="is_required" value="1"><label class="form-check-label">Required</label></div></div>
                            <div class="col-md-2"><button class="btn btn-success btn-sm w-100"><i class="fas fa-plus"></i> Add</button></div>
                        </div>
                    </form>
                </div>
            </div>
            @empty
            <div class="alert alert-info">No admission sections created yet. Click "Add Section" to get started.</div>
            @endforelse
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header"><h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Required Documents</h5></div>
                <div class="card-body">
                    @forelse($documentTypes as $doc)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div><strong>{{ $doc->name }}</strong>@if($doc->is_required)<span class="badge bg-danger ms-1">Required</span>@endif<br><small class="text-muted">Max: {{ $doc->max_size_kb }}KB</small></div>
                        <form action="{{ route('admin.cms.admissions.documents.destroy', $doc) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm"><i class="fas fa-times"></i></button>
                        </form>
                    </div>
                    @empty
                    <p class="text-muted">No document types configured.</p>
                    @endforelse
                    <form action="{{ route('admin.cms.admissions.documents.store') }}" method="POST" class="mt-3">
                        @csrf
                        <div class="mb-2"><input type="text" name="name" class="form-control form-control-sm" placeholder="Document name" required></div>
                        <div class="row g-2 mb-2">
                            <div class="col-6"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_required" value="1"><label class="form-check-label">Required</label></div></div>
                            <div class="col-6"><input type="number" name="max_size_kb" class="form-control form-control-sm" placeholder="Max KB" value="5120"></div>
                        </div>
                        <button class="btn btn-success btn-sm w-100"><i class="fas fa-plus"></i> Add Document Type</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
