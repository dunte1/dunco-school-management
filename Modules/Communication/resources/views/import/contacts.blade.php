@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Import Contacts</h2>
        <a href="{{ route('communication.import.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Import
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('communication.import.contacts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="file" class="form-label">Select CSV File</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                   id="file" name="file" accept=".csv,.txt" required>
                            <div class="form-text">Supported formats: CSV, TXT (Max: 2MB)</div>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="has_headers" name="has_headers" value="1" checked>
                                <label class="form-check-label" for="has_headers">
                                    First row contains headers
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="update_existing" name="update_existing" value="1">
                                <label class="form-check-label" for="update_existing">
                                    Update existing contacts
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i>Import Contacts
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">CSV Format</h5>
                </div>
                <div class="card-body">
                    <p class="small">Your CSV file should contain the following columns:</p>
                    <ul class="list-unstyled small">
                        <li><strong>name</strong> - Contact name</li>
                        <li><strong>email</strong> - Email address</li>
                        <li><strong>phone</strong> - Phone number</li>
                        <li><strong>type</strong> - Contact type (student, parent, staff)</li>
                        <li><strong>notes</strong> - Additional notes</li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Download Template</h5>
                </div>
                <div class="card-body">
                    <p class="small">Download a sample CSV template to get started:</p>
                    <a href="{{ route('communication.import.template', 'contacts') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-2"></i>Download Template
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
