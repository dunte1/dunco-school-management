@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">
        <i class="fas fa-chart-pie me-2"></i>
        Communication Analytics
    </h1>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary" onclick="exportAnalytics()">
            <i class="fas fa-download me-2"></i>Export
        </button>
        <button class="btn btn-primary" onclick="refreshAnalytics()">
            <i class="fas fa-sync-alt me-2"></i>Refresh
        </button>
    </div>
</div>

    <!-- Date Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="dateRangeForm" class="row g-3">
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="{{ $startDate->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="{{ $endDate->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block">
                                <i class="fas fa-filter me-2"></i>Apply Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ number_format($messageStats['total']) }}</h3>
                <small>Total Messages</small>
                <div class="mt-1">
                    <small class="text-white-50">Success: {{ $messageStats['success_rate'] }}%</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ number_format($contactStats['total']) }}</h3>
                <small>Total Contacts</small>
                <div class="mt-1">
                    <small class="text-white-50">Growth: {{ $contactStats['growth_rate'] }}%</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ number_format($groupStats['total']) }}</h3>
                <small>Total Groups</small>
                <div class="mt-1">
                    <small class="text-white-50">Active: {{ $groupStats['active'] }}</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ number_format($templateStats['total']) }}</h3>
                <small>Templates</small>
                <div class="mt-1">
                    <small class="text-white-50">Active: {{ $templateStats['active'] }}</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ number_format($announcementStats['total']) }}</h3>
                <small>Announcements</small>
                <div class="mt-1">
                    <small class="text-white-50">Published: {{ $announcementStats['published'] }}</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-secondary text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ number_format($scheduleStats['total']) }}</h3>
                <small>Schedules</small>
                <div class="mt-1">
                    <small class="text-white-50">Active: {{ $scheduleStats['active'] }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Daily Activity Chart -->
        <div class="col-xl-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Daily Activity
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyActivityChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Message Status Chart -->
        <div class="col-xl-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Message Status
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="messageStatusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="row">
        <!-- Top Performing Content -->
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy me-2"></i>Top Performing Content
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="topContentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="templates-tab" data-bs-toggle="tab" data-bs-target="#templates" type="button" role="tab">
                                Templates
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="groups-tab" data-bs-toggle="tab" data-bs-target="#groups" type="button" role="tab">
                                Groups
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="announcements-tab" data-bs-toggle="tab" data-bs-target="#announcements" type="button" role="tab">
                                Announcements
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="topContentTabContent">
                        <div class="tab-pane fade show active" id="templates" role="tabpanel">
                            @if($topContent['templates']->count() > 0)
                                @foreach($topContent['templates'] as $template)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>{{ $template->name }}</strong>
                                        <br><small class="text-muted">{{ $template->type }}</small>
                                    </div>
                                    <span class="badge bg-primary">{{ $template->created_at->format('M d, Y') }}</span>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">No template usage data available.</p>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="groups" role="tabpanel">
                            @if($topContent['groups']->count() > 0)
                                @foreach($topContent['groups'] as $group)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>{{ $group->name }}</strong>
                                        <br><small class="text-muted">{{ $group->type }}</small>
                                    </div>
                                    <span class="badge bg-success">{{ $group->members_count }} members</span>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">No group data available.</p>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="announcements" role="tabpanel">
                            @if($topContent['announcements']->count() > 0)
                                @foreach($topContent['announcements'] as $announcement)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <strong>{{ $announcement->title }}</strong>
                                        <br><small class="text-muted">{{ $announcement->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <span class="badge bg-{{ $announcement->is_published ? 'success' : 'warning' }}">
                                        {{ $announcement->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted">No announcement data available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Distribution Charts -->
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Distribution Analysis
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="distributionTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="contacts-dist-tab" data-bs-toggle="tab" data-bs-target="#contacts-dist" type="button" role="tab">
                                Contacts
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="groups-dist-tab" data-bs-toggle="tab" data-bs-target="#groups-dist" type="button" role="tab">
                                Groups
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="templates-dist-tab" data-bs-toggle="tab" data-bs-target="#templates-dist" type="button" role="tab">
                                Templates
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="distributionTabContent">
                        <div class="tab-pane fade show active" id="contacts-dist" role="tabpanel">
                            <canvas id="contactsDistributionChart" height="150"></canvas>
                        </div>
                        <div class="tab-pane fade" id="groups-dist" role="tabpanel">
                            <canvas id="groupsDistributionChart" height="150"></canvas>
                        </div>
                        <div class="tab-pane fade" id="templates-dist" role="tabpanel">
                            <canvas id="templatesDistributionChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily Activity Chart
    const dailyActivityCtx = document.getElementById('dailyActivityChart').getContext('2d');
    const dailyActivityData = @json($dailyActivity);
    
    const labels = Object.keys(dailyActivityData);
    const messagesData = labels.map(date => dailyActivityData[date].messages);
    const contactsData = labels.map(date => dailyActivityData[date].contacts);
    const groupsData = labels.map(date => dailyActivityData[date].groups);
    const announcementsData = labels.map(date => dailyActivityData[date].announcements);
    
    new Chart(dailyActivityCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Messages',
                    data: messagesData,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'Contacts',
                    data: contactsData,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'Groups',
                    data: groupsData,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                },
                {
                    label: 'Announcements',
                    data: announcementsData,
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Message Status Chart
    const messageStatusCtx = document.getElementById('messageStatusChart').getContext('2d');
    new Chart(messageStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Sent', 'Failed', 'Pending'],
            datasets: [{
                data: [
                    {{ $messageStats['sent'] }},
                    {{ $messageStats['failed'] }},
                    {{ $messageStats['pending'] }}
                ],
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(239, 68, 68)',
                    'rgb(245, 158, 11)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Distribution Charts
    const contactsDistCtx = document.getElementById('contactsDistributionChart').getContext('2d');
    const contactsByCategory = @json($contactStats['by_category']);
    
    new Chart(contactsDistCtx, {
        type: 'bar',
        data: {
            labels: contactsByCategory.map(item => item.category),
            datasets: [{
                label: 'Contacts',
                data: contactsByCategory.map(item => item.count),
                backgroundColor: 'rgba(59, 130, 246, 0.8)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const groupsDistCtx = document.getElementById('groupsDistributionChart').getContext('2d');
    const groupsByType = @json($groupStats['by_type']);
    
    new Chart(groupsDistCtx, {
        type: 'bar',
        data: {
            labels: groupsByType.map(item => item.type),
            datasets: [{
                label: 'Groups',
                data: groupsByType.map(item => item.count),
                backgroundColor: 'rgba(59, 130, 246, 0.8)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const templatesDistCtx = document.getElementById('templatesDistributionChart').getContext('2d');
    const templatesByType = @json($templateStats['by_type']);
    
    new Chart(templatesDistCtx, {
        type: 'bar',
        data: {
            labels: templatesByType.map(item => item.type),
            datasets: [{
                label: 'Templates',
                data: templatesByType.map(item => item.count),
                backgroundColor: 'rgba(245, 158, 11, 0.8)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});

function refreshAnalytics() {
    location.reload();
}

function exportAnalytics() {
    // Implementation for exporting analytics data
    alert('Export functionality will be implemented soon.');
}

// Handle date range form submission
document.getElementById('dateRangeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    
    if (startDate && endDate) {
        window.location.href = `{{ route('communication.analytics.index') }}?start_date=${startDate}&end_date=${endDate}`;
    }
});
</script>
@endpush
@endsection
