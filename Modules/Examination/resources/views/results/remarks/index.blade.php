@extends('layouts.app')

@section('title','Exam Remarks')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">Exam Remarks</h1>
  <form method="get" class="mb-3">
    <select name="status" class="form-select" style="max-width:220px" onchange="this.form.submit()">
      <option value="">All Statuses</option>
      @foreach(['requested','reviewing','resolved'] as $s)
        <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
  </form>
  <div class="card">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Exam</th>
            <th>Student</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($remarks as $remark)
          <tr>
            <td>{{ $remark->id }}</td>
            <td>#{{ $remark->exam_id }}</td>
            <td>#{{ $remark->student_id }}</td>
            <td><span class="badge bg-secondary">{{ ucfirst($remark->status) }}</span></td>
            <td>
              <form method="post" action="{{ route('examination.remarks.update', $remark->id) }}" class="d-inline">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="reviewing">
                <button class="btn btn-sm btn-outline-primary">Mark Reviewing</button>
              </form>
              <form method="post" action="{{ route('examination.remarks.update', $remark->id) }}" class="d-inline ms-1">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="resolved">
                <button class="btn btn-sm btn-outline-success">Resolve</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      {{ $remarks->withQueryString()->links() }}
    </div>
  </div>
</div>
@endsection


