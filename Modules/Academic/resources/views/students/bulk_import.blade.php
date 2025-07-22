@extends('academic::layouts.app')

@section('content')
<div class="container">
    <h1>Bulk Student Enrollment</h1>
    <form method="POST" action="{{ route('academic.students.handle_bulk_import') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="excel_file" class="form-label">Excel File (.xlsx, .csv)</label>
            <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx,.csv" required>
        </div>
        <div class="mb-3">
            <label for="documents_zip" class="form-label">Documents ZIP (optional)</label>
            <input type="file" class="form-control" id="documents_zip" name="documents_zip" accept=".zip">
            <small class="form-text text-muted">Name files as <code>STUDENTID_documenttype.ext</code> (e.g., <code>S001_birth_certificate.pdf</code>).</small>
        </div>
        <button type="submit" class="btn btn-primary">Import Students</button>
        <a href="{{ route('academic.students.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
    <div class="mt-3">
        <p>
            Download sample templates:
            <a href="{{ asset('storage/bulk_import_sample.xlsx') }}" class="btn btn-outline-info btn-sm ms-2">Excel (.xlsx)</a>
            <a href="{{ asset('storage/bulk_import_sample.csv') }}" class="btn btn-outline-info btn-sm ms-2">CSV (.csv)</a>
        </p>
    </div>
</div>
@endsection 