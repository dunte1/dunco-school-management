@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Notification Templates</h1>
        <a href="{{ route('admin.notifications.templates.create') }}" class="btn btn-primary">New Template</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Channel</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($templates as $template)
            <tr>
                <td>{{ $template->name }}</td>
                <td>{{ strtoupper($template->channel) }}</td>
                <td>{{ $template->is_active ? 'Yes' : 'No' }}</td>
                <td>
                    <a href="{{ route('admin.notifications.templates.edit', $template) }}" class="btn btn-sm btn-secondary">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $templates->links() }}
</div>
@endsection

@extends('layouts.app')
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Notification Templates</h1>
    <a href="{{ route('admin.notifications.templates.create') }}" class="btn btn-primary">New Template</a>
  </div>
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead><tr><th>Name</th><th>Channel</th><th>Active</th><th>Updated</th><th></th></tr></thead>
        <tbody>
          @forelse($templates as $t)
            <tr>
              <td>{{ $t->name }}</td>
              <td class="text-uppercase">{{ $t->channel }}</td>
              <td>{{ $t->is_active ? 'Yes' : 'No' }}</td>
              <td>{{ $t->updated_at->diffForHumans() }}</td>
              <td class="text-end"><a href="{{ route('admin.notifications.templates.edit',$t) }}" class="btn btn-sm btn-outline-secondary">Edit</a></td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center p-4">No templates yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $templates->links() }}</div>
  </div>
</div>
@endsection


