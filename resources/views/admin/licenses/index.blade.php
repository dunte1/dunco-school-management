@extends('layouts.app')
@section('title','Licenses')
@section('content')
<div class="container-fluid py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Licenses</h4>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.licenses.index', ['filter' => 'expiring_30d']) }}" class="btn btn-outline-warning btn-sm">Expiring in 30 days</a>
      <a href="{{ route('admin.licenses.create') }}" class="btn btn-primary btn-sm">New License</a>
    </div>
  </div>
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>School</th>
              <th>Plan</th>
              <th>Status</th>
              <th>Seats</th>
              <th>Starts</th>
              <th>Expires</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          @forelse($licenses as $license)
            <tr>
              <td>{{ $license->school_id ?? '—' }}</td>
              <td>{{ ucfirst($license->plan) }}</td>
              <td>{{ ucfirst($license->status) }}</td>
              <td>{{ $license->seats }}</td>
              <td>{{ optional($license->starts_at)->format('Y-m-d') }}</td>
              <td>{{ optional($license->expires_at)->format('Y-m-d') }}</td>
              <td class="text-end">
                <a href="{{ route('admin.licenses.edit', $license) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                <form action="{{ route('admin.licenses.destroy', $license) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete license?')">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No licenses found</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer">{{ $licenses->links() }}</div>
  </div>
</div>
@endsection
