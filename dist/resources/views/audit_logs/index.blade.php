@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Audit Logs</h2>
    <form method="GET" class="row g-2 mb-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Action</label>
            <select name="action" class="form-select">
                <option value="">All</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}" @if(request('action')==$action) selected @endif>{{ $action }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">User</label>
            <select name="user_id" class="form-select">
                <option value="">All</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @if(request('user_id')==$user->id) selected @endif>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Date</label>
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at }}</td>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->description }}</td>
                    <td>
                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#logModal{{ $log->id }}">View</button>
                        <!-- Modal -->
                        <div class="modal fade" id="logModal{{ $log->id }}" tabindex="-1" aria-labelledby="logModalLabel{{ $log->id }}" aria-hidden="true">
                          <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="logModalLabel{{ $log->id }}">Audit Log Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                <strong>Old Values:</strong>
                                <pre>{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                                <strong>New Values:</strong>
                                <pre>{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                              </div>
                            </div>
                          </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">No audit logs found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $logs->links() }}</div>
</div>
@endsection 