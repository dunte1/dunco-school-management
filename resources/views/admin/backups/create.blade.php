@extends('layouts.app')

@section('title', 'Create Backup')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex align-items-center gap-2">
            <i class="fas fa-plus-circle"></i>
            <strong>Create Backup</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.backups.store') }}" method="post" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <option value="full">Full</option>
                        <option value="incremental">Incremental</option>
                        <option value="differential">Differential</option>
                        <option value="custom">Custom (select modules)</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Modules (for custom backups)</label>
                    <input type="text" name="modules[]" class="form-control" placeholder="e.g., finance,examination" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">Disk</label>
                    <input type="text" name="disk" class="form-control" placeholder="local, s3, ftp, sftp" />
                </div>
                <div class="col-12 form-check mt-2">
                    <input class="form-check-input" type="checkbox" value="1" id="encrypt" name="encrypt">
                    <label class="form-check-label" for="encrypt">Encrypt (AES-256)</label>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-play me-1"></i> Start Backup
                    </button>
                    <a href="{{ route('admin.backups.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


