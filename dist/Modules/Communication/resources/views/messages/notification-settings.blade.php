@extends('communication::layouts.master')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Notification Settings</h3>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Configure how you want to receive notifications for different categories.</p>
                    
                    <form action="{{ route('communication.notifications.settings.update') }}" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Category</th>
                                        <th>Email</th>
                                        <th>SMS</th>
                                        <th>Push Notification</th>
                                        <th>In-App</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>
                                                <strong>{{ ucfirst($category) }}</strong>
                                                <br><small class="text-muted">
                                                    @switch($category)
                                                        @case('academic')
                                                            Exam schedules, grades, assignments
                                                            @break
                                                        @case('finance')
                                                            Fee reminders, payment confirmations
                                                            @break
                                                        @case('events')
                                                            School events, meetings, activities
                                                            @break
                                                        @case('system')
                                                            System updates, maintenance alerts
                                                            @break
                                                        @case('communication')
                                                            Messages, announcements, group updates
                                                            @break
                                                    @endswitch
                                                </small>
                                            </td>
                                            @foreach($channels as $channel)
                                                @php
                                                    $pref = $settings[$channel][$category] ?? null;
                                                @endphp
                                                <td class="text-center">
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="preferences[{{ $channel }}][{{ $category }}][enabled]" 
                                                               value="1" 
                                                               @if($pref && $pref->enabled) checked @endif>
                                                    </div>
                                                    @if($channel === 'push' || $channel === 'in_app')
                                                        <div class="mt-2">
                                                            <small class="text-muted">Sound</small>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="preferences[{{ $channel }}][{{ $category }}][sound]" 
                                                                       value="1" 
                                                                       @if($pref && $pref->sound) checked @endif>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted">Vibration</small>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="preferences[{{ $channel }}][{{ $category }}][vibration]" 
                                                                       value="1" 
                                                                       @if($pref && $pref->vibration) checked @endif>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Help Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Notification Channels</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <h6><i class="fas fa-envelope text-primary me-2"></i>Email</h6>
                            <small class="text-muted">Receive notifications via email for important updates.</small>
                        </div>
                        <div class="col-md-3">
                            <h6><i class="fas fa-sms text-success me-2"></i>SMS</h6>
                            <small class="text-muted">Get urgent notifications via text message.</small>
                        </div>
                        <div class="col-md-3">
                            <h6><i class="fas fa-mobile-alt text-info me-2"></i>Push</h6>
                            <small class="text-muted">Real-time notifications on your device.</small>
                        </div>
                        <div class="col-md-3">
                            <h6><i class="fas fa-bell text-warning me-2"></i>In-App</h6>
                            <small class="text-muted">Notifications within the application.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 