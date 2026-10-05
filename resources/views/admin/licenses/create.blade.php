@extends('layouts.app')
@section('title','New License')
@section('content')
<div class="container-fluid py-3">
  <h4 class="mb-3">Create License</h4>
  <div class="card shadow-sm">
    <div class="card-body">
      <form action="{{ route('admin.licenses.store') }}" method="POST">
        @csrf
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">School ID</label>
            <input type="number" class="form-control" name="school_id" value="{{ old('school_id') }}">
          </div>
          <div class="col-md-4">
            <label class="form-label">Plan</label>
            <input type="text" class="form-control" name="plan" value="{{ old('plan','basic') }}" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" required>
              @foreach(['active','suspended','expired'] as $st)
                <option value="{{ $st }}" @selected(old('status','active')===$st)>{{ ucfirst($st) }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Seats</label>
            <input type="number" class="form-control" name="seats" value="{{ old('seats',0) }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">Starts At</label>
            <input type="date" class="form-control" name="starts_at" value="{{ old('starts_at') }}">
          </div>
          <div class="col-md-3">
            <label class="form-label">Expires At</label>
            <input type="date" class="form-control" name="expires_at" value="{{ old('expires_at') }}">
          </div>
          <div class="col-md-12">
            <label class="form-label">Features (JSON)</label>
            <textarea class="form-control" name="features" rows="4" placeholder='{"sso":true,"support":"priority"}'>{{ old('features') }}</textarea>
            <small class="text-muted">Optional JSON blob for feature toggles.</small>
          </div>
        </div>
        <div class="mt-3 d-flex gap-2">
          <button class="btn btn-primary" type="submit">Save</button>
          <a href="{{ route('admin.licenses.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
