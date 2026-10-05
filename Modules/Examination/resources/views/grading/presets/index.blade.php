@extends('layouts.app')

@section('title','Grading Presets')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">Grading Presets</h1>
  <div class="row">
    <div class="col-md-6">
      <div class="card mb-3">
        <div class="card-header">Create Preset</div>
        <div class="card-body">
          <form method="post" action="{{ route('examination.grading.presets.store') }}">
            @csrf
            <div class="mb-2">
              <label class="form-label">Name</label>
              <input class="form-control" name="name" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Bands (JSON)</label>
              <textarea class="form-control" name="bands_json" rows="4" placeholder='[{"min":80,"max":100,"grade":"A"}]'></textarea>
            </div>
            <button class="btn btn-primary">Save</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">Existing</div>
        <ul class="list-group list-group-flush">
          @foreach($presets as $preset)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>{{ $preset->name }}</span>
            <form method="post" action="{{ route('examination.grading.presets.assign', $preset->id) }}" class="d-flex align-items-center">
              @csrf
              <input name="class_id" class="form-control form-control-sm me-2" placeholder="Class ID">
              <input name="exam_id" class="form-control form-control-sm me-2" placeholder="Exam ID">
              <button class="btn btn-sm btn-outline-secondary">Assign</button>
            </form>
          </li>
          @endforeach
        </ul>
        <div class="card-footer">{{ $presets->links() }}</div>
      </div>
    </div>
  </div>
</div>
@endsection


