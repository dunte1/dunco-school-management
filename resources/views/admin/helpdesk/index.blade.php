@extends('layouts.app')
@section('title','Help Desk')
@section('content')
<div class="container-fluid py-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Help Desk</h4>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.helpdesk.tickets.index', ['status' => 'open']) }}" class="btn btn-outline-secondary btn-sm">Open</a>
      <a href="{{ route('admin.helpdesk.tickets.index', ['status' => 'pending']) }}" class="btn btn-outline-warning btn-sm">Pending</a>
      <a href="{{ route('admin.helpdesk.tickets.index', ['status' => 'in_progress']) }}" class="btn btn-outline-info btn-sm">In Progress</a>
      <a href="{{ route('admin.helpdesk.tickets.create') }}" class="btn btn-primary btn-sm">New Ticket</a>
    </div>
  </div>
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Subject</th>
              <th>Status</th>
              <th>Priority</th>
              <th>School</th>
              <th>Created</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse(($tickets ?? []) as $t)
              <tr>
                <td>#{{ $t->id }}</td>
                <td>{{ $t->subject }}</td>
                <td>{{ ucfirst($t->status) }}</td>
                <td>{{ ucfirst($t->priority) }}</td>
                <td>{{ $t->school_id ?? '—' }}</td>
                <td>{{ $t->created_at?->format('Y-m-d H:i') }}</td>
                <td class="text-end">
                  <a href="{{ route('admin.helpdesk.tickets.edit', $t) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="text-center text-muted py-4">No tickets found</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
