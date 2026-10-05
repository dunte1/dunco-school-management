@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">{{ isset($schedule) ? 'Edit' : 'New' }} Notification Schedule</h1>
    <form method="POST" action="{{ isset($schedule) ? route('admin.notifications.schedules.update', $schedule) : route('admin.notifications.schedules.store') }}">
        @csrf
        @if(isset($schedule))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label class="form-label">Template</label>
            <select name="template_id" class="form-control" required>
                <option value="">Select template</option>
                @foreach($templates as $id => $name)
                    <option value="{{ $id }}" {{ old('template_id', $schedule->template_id ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            @error('template_id')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Channel</label>
            <select name="channel" class="form-control" required>
                @foreach(['email'=>'Email','sms'=>'SMS','whatsapp'=>'WhatsApp'] as $key=>$label)
                    <option value="{{ $key }}" {{ old('channel', $schedule->channel ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('channel')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Audience (JSON)</label>
            <textarea name="audience" class="form-control" rows="3" placeholder='{"role":"parent"}'>{{ old('audience', isset($schedule) && is_array($schedule->audience ?? null) ? json_encode($schedule->audience) : ($schedule->audience ?? '')) }}</textarea>
            @error('audience')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Cron Expression</label>
            <input type="text" name="cron" class="form-control" value="{{ old('cron', $schedule->cron ?? '* * * * *') }}" required>
            @error('cron')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" {{ old('is_active', $schedule->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Active</label>
        </div>

        <div class="d-flex gap-2">
            <button class="btn btn-success">Save</button>
            <a href="{{ route('admin.notifications.schedules.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection


