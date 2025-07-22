@extends('academic::layouts.app')

@section('content')
<div class="container">
    <h1>Notification Templates</h1>
    <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary mb-3">Create New Template</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Event</th>
                <th>Channel</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($templates as $tpl)
                <tr>
                    <td>{{ $tpl->event }}</td>
                    <td>{{ $tpl->channel }}</td>
                    <td>{{ $tpl->subject }}</td>
                    <td>
                        @if($tpl->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.notifications.edit', $tpl->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('admin.notifications.toggle', $tpl->id) }}" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-secondary">{{ $tpl->is_active ? 'Deactivate' : 'Activate' }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 