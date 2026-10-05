@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Edit Schedule</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('communication.schedules.show', $schedule) }}" class="btn btn-outline-primary">
            <i class="fas fa-eye me-2"></i>View Schedule
        </a>
        <a href="{{ route('communication.schedules.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Schedules
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('communication.schedules.update', $schedule) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Schedule Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $schedule->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="type" class="form-label">Communication Type *</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="email" {{ old('type', $schedule->type) == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="sms" {{ old('type', $schedule->type) == 'sms' ? 'selected' : '' }}>SMS</option>
                            <option value="notification" {{ old('type', $schedule->type) == 'notification' ? 'selected' : '' }}>Notification</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description', $schedule->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="frequency" class="form-label">Frequency *</label>
                        <select class="form-select @error('frequency') is-invalid @enderror" id="frequency" name="frequency" required>
                            <option value="">Select Frequency</option>
                            <option value="once" {{ old('frequency', $schedule->frequency) == 'once' ? 'selected' : '' }}>Once</option>
                            <option value="daily" {{ old('frequency', $schedule->frequency) == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ old('frequency', $schedule->frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ old('frequency', $schedule->frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="yearly" {{ old('frequency', $schedule->frequency) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                        @error('frequency')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="time" class="form-label">Time *</label>
                        <input type="time" class="form-control @error('time') is-invalid @enderror" 
                               id="time" name="time" value="{{ old('time', $schedule->time->format('H:i')) }}" required>
                        @error('time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date *</label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                               id="start_date" name="start_date" value="{{ old('start_date', $schedule->start_date->format('Y-m-d')) }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                               id="end_date" name="end_date" value="{{ old('end_date', $schedule->end_date ? $schedule->end_date->format('Y-m-d') : '') }}">
                        <div class="form-text">Leave empty for no end date</div>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3" id="days-of-week-section" style="display: none;">
                <label class="form-label">Days of Week</label>
                <div class="row">
                    @php
                        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                        $selectedDays = old('days_of_week', $schedule->days_of_week ?? []);
                    @endphp
                    @foreach($days as $index => $day)
                        <div class="col-md-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days_of_week[]" 
                                       value="{{ $index }}" id="day_{{ $index }}" 
                                       {{ in_array($index, $selectedDays) ? 'checked' : '' }}>
                                <label class="form-check-label" for="day_{{ $index }}">
                                    {{ $day }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="mb-3">
                <label for="template_id" class="form-label">Template</label>
                <select class="form-select @error('template_id') is-invalid @enderror" id="template_id" name="template_id">
                    <option value="">Select Template (Optional)</option>
                    @foreach($templates as $template)
                        <option value="{{ $template->id }}" {{ old('template_id', $schedule->template_id) == $template->id ? 'selected' : '' }}>
                            {{ $template->name }} ({{ ucfirst($template->type) }})
                        </option>
                    @endforeach
                </select>
                <div class="form-text">Select a template to use for this scheduled communication</div>
                @error('template_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                           {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">
                        Active Schedule
                    </label>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Update Schedule
                </button>
                <a href="{{ route('communication.schedules.show', $schedule) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Show/hide days of week based on frequency
    document.getElementById('frequency').addEventListener('change', function() {
        const daysSection = document.getElementById('days-of-week-section');
        if (this.value === 'weekly') {
            daysSection.style.display = 'block';
        } else {
            daysSection.style.display = 'none';
        }
    });
    
    // Trigger on page load
    document.addEventListener('DOMContentLoaded', function() {
        const frequencySelect = document.getElementById('frequency');
        if (frequencySelect.value === 'weekly') {
            document.getElementById('days-of-week-section').style.display = 'block';
        }
    });
</script>
@endpush
@endsection
