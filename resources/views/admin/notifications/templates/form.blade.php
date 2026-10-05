@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($template) ? 'Edit' : 'Create' }} Template</h1>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ isset($template) ? route('admin.notifications.templates.update', $template) : route('admin.notifications.templates.store') }}">
        @csrf
        @if(isset($template))
            @method('PUT')
        @endif
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $template->name ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Channel</label>
            <select name="channel" class="form-select" required>
                @foreach(['email','sms','whatsapp'] as $ch)
                    <option value="{{ $ch }}" @selected(old('channel', $template->channel ?? '') === $ch)>{{ strtoupper($ch) }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Subject (email/whatsapp)</label>
            <input type="text" name="subject" class="form-control" value="{{ old('subject', $template->subject ?? '') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Body</label>
            <textarea name="body" rows="8" class="form-control" required>{{ old('body', $template->body ?? '') }}</textarea>
            <small class="text-muted">Use variables with {{ '{{variable}}' }}. Example: {{ '{{student.name}}' }}, {{ '{{invoice.amount_due}}' }}</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Variables (JSON array)</label>
            <input type="text" name="variables" class="form-control" value='{{ old('variables', isset($template) && is_array($template->variables ?? null) ? json_encode($template->variables) : "[]") }}'>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $template->is_active ?? true))>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('admin.notifications.templates.index') }}" class="btn btn-link">Cancel</a>
    </form>

    @if(isset($template))
    <hr class="my-4"/>
    <h2>Test Send</h2>
    <form method="POST" action="{{ route('admin.notifications.templates.test', $template) }}" class="mt-3">
        @csrf
        <div class="mb-3">
            <label class="form-label">Recipient (email or phone)</label>
            <input type="text" name="recipient" class="form-control" placeholder="user@example.com or +2547..." required>
        </div>
        <div class="mb-3">
            <label class="form-label">Payload (JSON)</label>
            <textarea name="payload" rows="4" class="form-control" placeholder='{"student":{"name":"Jane"},"invoice":{"amount_due":1000}}'></textarea>
            <small class="text-muted">Optional. If provided, must be valid JSON. Variables are resolved via dot paths.</small>
        </div>
        <button class="btn btn-secondary">Queue Test</button>
    </form>
    
    @endif
</div>
@endsection

@extends('layouts.app')
@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">{{ isset($template) ? 'Edit' : 'New' }} Template</h1>
  <form method="POST" action="{{ isset($template) ? route('admin.notifications.templates.update',$template) : route('admin.notifications.templates.store') }}" class="card p-3">
    @csrf
    @if(isset($template)) @method('PUT') @endif
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $template->name ?? '') }}" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Channel</label>
        <select name="channel" class="form-select" required>
          @foreach(['email','sms','whatsapp'] as $c)
            <option value="{{ $c }}" @selected(old('channel', $template->channel ?? '') === $c)>{{ strtoupper($c) }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Active</label>
        <select name="is_active" class="form-select">
          <option value="1" @selected((int)old('is_active', (int)($template->is_active ?? 1))===1)>Yes</option>
          <option value="0" @selected((int)old('is_active', (int)($template->is_active ?? 1))===0)>No</option>
        </select>
      </div>
      <div class="col-12">
        <label class="form-label">Subject (Email/WhatsApp)</label>
        <input type="text" name="subject" class="form-control" value="{{ old('subject', $template->subject ?? '') }}">
      </div>
      <div class="col-12">
        <label class="form-label">Body</label>
        <textarea name="body" rows="8" class="form-control" required>{{ old('body', $template->body ?? '') }}</textarea>
        <small class="text-muted">Use variables like {{ '{' }}{{ '{student.name}' }}{{ '}' }}, {{ '{' }}{{ '{invoice.amount_due}' }}{{ '}' }}.</small>
      </div>
      <div class="col-12">
        <label class="form-label">Variables (JSON)</label>
        <textarea name="variables" rows="3" class="form-control" placeholder='{"student.id": "int", "invoice.id":"int"}'>{{ old('variables', isset($template)? json_encode($template->variables) : '') }}</textarea>
      </div>
    </div>
    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-primary">Save</button>
      <a href="{{ route('admin.notifications.templates.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
  </form>
</div>
@endsection


