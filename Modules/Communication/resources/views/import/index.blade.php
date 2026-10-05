@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Import Data</h1>
    <a href="{{ route('communication.dashboard') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('errors'))
    <div class="alert alert-danger">
        <h6>Import Errors:</h6>
        <ul class="mb-0">
            @foreach(session('errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-address-book me-2"></i>Import Contacts
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Import contacts from a CSV file. The file should include columns for name, email, phone, organization, position, category, and notes.</p>
                
                <div class="mb-3">
                    <a href="{{ route('communication.import.template', 'contacts') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-2"></i>Download Template
                    </a>
                </div>
                
                <form method="POST" action="{{ route('communication.import.contacts') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="contacts_file" class="form-label">CSV File</label>
                        <input type="file" class="form-control" id="contacts_file" name="file" accept=".csv,.txt" required>
                        <div class="form-text">Upload a CSV file with contact information.</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="contacts_update_existing" name="update_existing" value="1">
                            <label class="form-check-label" for="contacts_update_existing">
                                Update existing contacts (by email)
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Import Contacts
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Import Groups
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Import groups from a CSV file. The file should include columns for name, description, and type.</p>
                
                <div class="mb-3">
                    <a href="{{ route('communication.import.template', 'groups') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-2"></i>Download Template
                    </a>
                </div>
                
                <form method="POST" action="{{ route('communication.import.groups') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="groups_file" class="form-label">CSV File</label>
                        <input type="file" class="form-control" id="groups_file" name="file" accept=".csv,.txt" required>
                        <div class="form-text">Upload a CSV file with group information.</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="groups_update_existing" name="update_existing" value="1">
                            <label class="form-check-label" for="groups_update_existing">
                                Update existing groups (by name)
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Import Groups
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>Import Templates
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">Import message templates from a CSV file. The file should include columns for name, subject, content, and type.</p>
                
                <div class="mb-3">
                    <a href="{{ route('communication.import.template', 'templates') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-2"></i>Download Template
                    </a>
                </div>
                
                <form method="POST" action="{{ route('communication.import.templates') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="templates_file" class="form-label">CSV File</label>
                        <input type="file" class="form-control" id="templates_file" name="file" accept=".csv,.txt" required>
                        <div class="form-text">Upload a CSV file with template information.</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="templates_update_existing" name="update_existing" value="1">
                            <label class="form-check-label" for="templates_update_existing">
                                Update existing templates (by name)
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Import Templates
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Import Guidelines
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>CSV File Format:</h6>
                        <ul>
                            <li>First row should contain column headers</li>
                            <li>Use commas to separate values</li>
                            <li>Enclose text in quotes if it contains commas</li>
                            <li>Maximum file size: 2MB</li>
                            <li>Supported formats: CSV, TXT</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Required Fields:</h6>
                        <ul>
                            <li><strong>Contacts:</strong> name, category (student/parent/teacher/staff/admin/external)</li>
                            <li><strong>Groups:</strong> name, type (general/academic/staff/students/parents)</li>
                            <li><strong>Templates:</strong> name, subject, content, type (email/sms/notification)</li>
                        </ul>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <h6><i class="fas fa-lightbulb me-2"></i>Tips:</h6>
                    <ul class="mb-0">
                        <li>Download the template files to see the correct format</li>
                        <li>Test with a small file first</li>
                        <li>Check for duplicate entries before importing</li>
                        <li>Backup your data before large imports</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
