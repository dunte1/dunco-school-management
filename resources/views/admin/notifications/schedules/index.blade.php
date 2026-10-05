@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Notification Schedules</h1>
        <a href="{{ route('admin.notifications.schedules.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> New Schedule</a>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Template</th>
                        <th>Channel</th>
                        <th>Cron</th>
                        <th>Active</th>
                        <th>Last Run</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $s)
                        <tr>
                            <td>{{ $s->id }}</td>
                            <td>{{ $s->template->name ?? '-' }}</td>
                            <td>{{ strtoupper($s->channel) }}</td>
                            <td><code>{{ $s->cron }}</code></td>
                            <td>
                                <span class="badge bg-{{ $s->is_active ? 'success' : 'secondary' }}">{{ $s->is_active ? 'Yes' : 'No' }}</span>
                            </td>
                            <td>{{ $s->last_run_at ? $s->last_run_at->diffForHumans() : '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.notifications.schedules.edit', $s) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('admin.notifications.schedules.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this schedule?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-muted p-3">No schedules found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $schedules->links() }}
        </div>
    </div>
</div>
@endsection


