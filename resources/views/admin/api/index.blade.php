@extends('layouts.app')

@section('title','API & Webhooks')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">API Keys & Webhooks</h1>
  <div class="row">
    <div class="col-md-6">
      <div class="card mb-3">
        <div class="card-header">API Keys</div>
        <div class="card-body">
          <form method="post" action="{{ route('admin.api.keys.create') }}" class="mb-3">
            @csrf
            <input class="form-control mb-2" name="name" placeholder="Key name" required>
            <button class="btn btn-primary">Create Key</button>
          </form>
          <ul class="list-group">
            @foreach($keys as $k)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <div><strong>{{ $k->name }}</strong> <small class="text-muted">{{ $k->prefix }}.********</small></div>
                <div class="text-muted">Active: {{ $k->is_active ? 'Yes' : 'No' }} | Last used: {{ $k->last_used_at }}</div>
              </div>
              <form method="post" action="{{ route('admin.api.keys.revoke', $k->id) }}">
                @csrf
                @method('PUT')
                <button class="btn btn-sm btn-outline-danger">Revoke</button>
              </form>
            </li>
            @endforeach
          </ul>
          <div class="card-footer">{{ $keys->links() }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card mb-3">
        <div class="card-header">Webhooks</div>
        <div class="card-body">
          <form method="post" action="{{ route('admin.api.webhooks.create') }}" class="mb-3">
            @csrf
            <input class="form-control mb-2" name="name" placeholder="Webhook name" required>
            <input class="form-control mb-2" name="url" placeholder="https://example.com/webhook" required>
            <input class="form-control mb-2" name="secret" placeholder="Optional secret">
            <button class="btn btn-primary">Create Webhook</button>
          </form>
          <ul class="list-group">
            @foreach($endpoints as $e)
            <li class="list-group-item">
              <div><strong>{{ $e->name }}</strong> <small class="text-muted">{{ $e->url }}</small></div>
            </li>
            @endforeach
          </ul>
          <div class="card-footer">{{ $endpoints->links() }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


