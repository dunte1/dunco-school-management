@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">Branding</h1>
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  <form method="POST" action="{{ route('admin.branding.update') }}" enctype="multipart/form-data" class="card p-3">
    @csrf
    @method('PUT')
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Display Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $branding->name ?? '') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Primary Color</label>
        <input type="text" name="primary_color" class="form-control" placeholder="#1a237e" value="{{ old('primary_color', $branding->primary_color ?? '') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Secondary Color</label>
        <input type="text" name="secondary_color" class="form-control" placeholder="#e91e63" value="{{ old('secondary_color', $branding->secondary_color ?? '') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">Text Color</label>
        <input type="text" name="text_color" class="form-control" placeholder="#111827" value="{{ old('text_color', $branding->text_color ?? '') }}">
      </div>
      <div class="col-md-4">
        <label class="form-label">Logo</label>
        <input type="file" name="logo" class="form-control">
        @if(!empty($branding?->logo_path))
          <img src="{{ asset('storage/'.$branding->logo_path) }}" class="img-thumbnail mt-2" style="max-height:80px">
        @endif
      </div>
      <div class="col-md-4">
        <label class="form-label">Auth Background</label>
        <input type="file" name="auth_bg" class="form-control">
        @if(!empty($branding?->auth_bg_path))
          <img src="{{ asset('storage/'.$branding->auth_bg_path) }}" class="img-thumbnail mt-2" style="max-height:80px">
        @endif
      </div>
      <div class="col-md-4">
        <label class="form-label">Hero Background</label>
        <input type="file" name="hero_bg" class="form-control">
        @if(!empty($branding?->hero_bg_path))
          <img src="{{ asset('storage/'.$branding->hero_bg_path) }}" class="img-thumbnail mt-2" style="max-height:80px">
        @endif
      </div>
    </div>
    <div class="mt-3">
      <button class="btn btn-primary">Save</button>
      <a href="{{ route('welcome') }}" class="btn btn-outline-secondary">Preview</a>
    </div>
  </form>
</div>
@endsection


