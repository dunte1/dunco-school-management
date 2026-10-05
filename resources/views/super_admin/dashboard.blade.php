@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Section -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3 p-3 bg-white rounded shadow-sm">
        <div class="fw-bold fs-5">🌐 MULTI-SCHOOL PLATFORM</div>
        <div class="d-flex gap-4 small text-muted">
            <div>🔔 System Alerts <span class="badge bg-danger">23</span></div>
            <div>👤 Super Admin</div>
        </div>
        <form action="{{ url('/super-admin') }}" method="GET" class="ms-auto d-flex align-items-center gap-2 mt-2 mt-md-0">
            <label class="small text-muted">Growth:</label>
            <select name="growth_months" class="form-select form-select-sm" style="width:auto">
                @foreach([6,12,18,24] as $gm)
                    <option value="{{ $gm }}" @selected(request('growth_months',12)==$gm)>{{ $gm }} mo</option>
                @endforeach
            </select>
            <label class="small text-muted">Revenue:</label>
            <select name="revenue_months" class="form-select form-select-sm" style="width:auto">
                @foreach([3,4,6,12] as $rm)
                    <option value="{{ $rm }}" @selected(request('revenue_months',4)==$rm)>{{ $rm }} mo</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-primary" type="submit">Apply</button>
        </form>

    <!-- System Monitoring Details -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span>System Monitoring</span>
                    <a href="/admin/monitoring" class="btn btn-sm btn-outline-secondary">Open Monitoring</a>
                </div>
                <div class="card-body row g-3">
                    <div class="col-12 mb-2">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="/admin/monitoring" class="btn btn-sm btn-outline-secondary"><i class="fas fa-tachometer-alt me-1"></i> Monitoring</a>
                            <a href="/admin/health" class="btn btn-sm btn-outline-success"><i class="fas fa-heartbeat me-1"></i> Health</a>
                            <a href="/admin/logs" class="btn btn-sm btn-outline-dark"><i class="fas fa-file-alt me-1"></i> Logs</a>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="small text-muted">Cache Probe</div>
                        <div class="fs-5">
                            @if(!empty($cacheProbeOk) && $cacheProbeOk)
                                <span class="text-success">OK</span>
                            @else
                                <span class="text-danger">Issue</span>
                            @endif
                        </div>
                        <div class="text-muted small">at {{ isset($cacheProbeAt) ? $cacheProbeAt->format('Y-m-d H:i') : '—' }}</div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="small text-muted">Queue Backlog</div>
                        <div class="fs-5">{{ $queueBacklog ?? 0 }}</div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="small text-muted">Failed Jobs (24h)</div>
                        <div class="fs-5">{{ $failedJobsRecent ?? 0 }}</div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="small text-muted">Server Load (approx)</div>
                        <div class="fs-5">{{ $serverLoad ?? 0 }}%</div>
                    </div>
                    <div class="col-12">
                        <div class="small text-muted mb-2">Recent Failures</div>
                        @if(!empty($failedJobsList) && count($failedJobsList))
                            <div class="table-responsive">
                                <table class="table table-sm align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Failed At</th>
                                            <th>Exception</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($failedJobsList as $fj)
                                            <tr>
                                                <td>#{{ $fj->id }}</td>
                                                <td>{{ \Carbon\Carbon::parse($fj->failed_at)->format('Y-m-d H:i') }}</td>
                                                <td class="text-truncate" style="max-width: 600px;">{{ \Illuminate\Support\Str::limit($fj->exception, 200) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-muted">No recent failures.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="w-100 mt-2 d-flex gap-4 small">
            <div>Platform Status: <span class="text-success">● Online</span></div>
            <div>📊 Server Load: {{ $serverLoad }}%</div>
            <div>🕒 Last Sync: {{ $lastSyncMinutes }} min</div>
        </div>
    </div>

    <!-- Top KPI Cards (6) -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6 col-lg-2">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Total Schools</div>
                    <div class="display-6">{{ $totalSchools }}</div>
                    <div class="small text-success">📈 Growing</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-2">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Active Schools</div>
                    <div class="display-6">{{ $activeSchools }}</div>
                    <div class="small text-warning">⚠️ Review</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-2">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Total Users</div>
                    <div class="display-6">{{ number_format($totalUsers) }}</div>
                    <div class="small text-info">📊 Steady</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-2">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Platform Revenue</div>
                    <div class="display-6">${{ number_format($platformRevenue) }}</div>
                    <div class="small text-success">💰 Up</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-2">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">System Health</div>
                    <div class="display-6">{{ $systemHealth }}%</div>
                    <div class="small text-success">✅ Stable</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-2">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Active Sessions</div>
                    <div class="display-6">{{ number_format($activeSessions) }}</div>
                    <div class="small text-primary">🔄 Live</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Critical Alerts Row (4) -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-3">
            <div class="card h-100 shadow-sm border-danger">
                <div class="card-body">
                    <div class="text-muted small">Pending Approvals</div>
                    <div class="display-6">{{ $critical['approvals'] }}</div>
                    <a href="/hr/leave?status=pending" class="btn btn-sm btn-outline-danger mt-2">Review Now</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <div class="card h-100 shadow-sm border-warning">
                <div class="card-body">
                    <div class="text-muted small">Support Tickets</div>
                    <div class="display-6">{{ $critical['tickets'] }}</div>
                    <a href="/admin/helpdesk" class="btn btn-sm btn-outline-warning mt-2">Handle Now</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <div class="card h-100 shadow-sm border-info">
                <div class="card-body">
                    <div class="text-muted small">Failed Payments</div>
                    <div class="display-6">{{ $critical['failed_payments'] }}</div>
                    <a href="/finance/payments" class="btn btn-sm btn-outline-info mt-2">Investigate</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <div class="card h-100 shadow-sm border-secondary">
                <div class="card-body">
                    <div class="text-muted small">License Expiring</div>
                    <div class="display-6">{{ $critical['licenses_expiring'] }}</div>
                    <a href="/admin/licenses?filter=expiring_30d" class="btn btn-sm btn-outline-secondary mt-2">Renew Now</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section (2 Charts) -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-xl-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white">School Registration & Growth Trends</div>
                <div class="card-body">
                    <canvas id="growthChart" height="140"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white">Platform Revenue Analytics</div>
                <div class="card-body">
                    <canvas id="revenueChart" height="140"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Panels (3 Columns) -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white">Recent School Activities</div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="fw-semibold">🏫 Phoenix Academy</div>
                        <div class="text-muted small">📊 +45 students • 💰 $8,450 revenue • ✅ Subscription OK</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-semibold">🏫 Tech Institute</div>
                        <div class="text-muted small">⚠️ Payment overdue • 👥 187 active users</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-semibold">🏫 Green Valley</div>
                        <div class="text-muted small">🎉 Just launched • 👨‍🎓 12 students • 💡 Setup: 85%</div>
                    </div>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All Schools…</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white">System Monitoring</div>
                <div class="card-body small">
                    <div class="mb-2">🖥️ Server Status</div>
                    <ul class="list-unstyled ms-3">
                        <li>• CPU: 68% ✅</li>
                        <li>• Memory: 74% ✅</li>
                        <li>• Disk: 45% ✅</li>
                    </ul>
                    <div class="mb-2">💾 Backup Status — ✅ Completed 2h ago (99.8% success)</div>
                    <div class="mb-2">🔒 Security — ✅ No threats • 🔐 SSL Valid • 🛡️ Firewall Active</div>
                    <div class="mb-2">📡 API — ✅ All endpoints OK (99.7% uptime)</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-white">Quick Platform Actions</div>
                <div class="card-body d-grid gap-2">
                    <button class="btn btn-primary">🏫 Add New School</button>
                    <button class="btn btn-outline-primary">👥 Create Super Admin</button>
                    <a href="/admin/reports/global" class="btn btn-outline-success">💰 Generate Revenue Report</a>
                    <a href="/communication/compose" class="btn btn-outline-secondary">📧 Send Platform Announcement</a>
                    <a href="/admin/maintenance" class="btn btn-outline-warning">🔧 Schedule Maintenance</a>
                    <a href="/admin/exports" class="btn btn-outline-info">📊 Export Platform Data</a>
                    <a href="/admin/monitoring" class="btn btn-outline-dark">🔒 Security Audit</a>
                    <a href="/admin/integrations" class="btn btn-outline-dark">📞 Support Dashboard</a>
                    <a href="/admin/settings" class="btn btn-outline-dark">⚙️ System Configuration</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Feature Tables -->
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span>Recent Schools Overview</span>
                    <div class="d-flex gap-2">
                        <a href="/admin/reports/global" class="btn btn-sm btn-outline-primary">View All</a>
                        <a href="#" class="btn btn-sm btn-primary">Add School</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>School Name</th>
                                    <th>Students</th>
                                    <th>Staff</th>
                                    <th>Subscription</th>
                                    <th>Revenue</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSchools as $s)
                                    <tr>
                                        <td>{{ $s['name'] }}</td>
                                        <td>{{ number_format($s['students']) }}</td>
                                        <td>{{ number_format($s['staff']) }}</td>
                                        <td>{{ ucfirst($s['status'] ?? 'unknown') }}</td>
                                        <td>—</td>
                                        <td>
                                            @php($st = $s['status'] ?? '')
                                            @if($st === 'active') 🟢 Active
                                            @elseif($st === 'pending') 🟡 Pending
                                            @elseif($st === 'suspended') 🔴 Suspended
                                            @else 🔵 Unknown @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-muted text-center">No schools found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span>Critical Support Issues</span>
                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                        <a href="#" class="btn btn-sm btn-primary">New Ticket</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>School</th>
                                    <th>Issue Type</th>
                                    <th>Priority</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#2024-001</td>
                                    <td>Phoenix HS</td>
                                    <td>Payment</td>
                                    <td>🔥 Critical</td>
                                    <td>Payment gateway failure</td>
                                </tr>
                                <tr>
                                    <td>#2024-002</td>
                                    <td>Tech Acad</td>
                                    <td>System</td>
                                    <td>⚠️ High</td>
                                    <td>SMS service not working</td>
                                </tr>
                                <tr>
                                    <td>#2024-003</td>
                                    <td>All</td>
                                    <td>Platform</td>
                                    <td>🔥 Critical</td>
                                    <td>Slow loading dashboards</td>
                                </tr>
                                <tr>
                                    <td>#2024-004</td>
                                    <td>Elite Prep</td>
                                    <td>Data</td>
                                    <td>📊 Medium</td>
                                    <td>Report export issues</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span>Revenue Tracking <span class="badge bg-light text-dark">MoM: {{ $revenue['mom'] ?? 0 }}%</span></span>
                    <div class="d-flex gap-2">
                        <a href="/admin/reports/global" class="btn btn-sm btn-outline-primary">Details</a>
                        <a href="/admin/exports" class="btn btn-sm btn-primary">Export Data</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Month</th>
                                    @foreach(($revenue['categories'] ?? []) as $cat)
                                        <th>{{ $cat === '—' ? 'Other' : ucfirst($cat) }}</th>
                                    @endforeach
                                    <th>Total</th>
                                    <th>Growth</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($revenue['monthly_split'] ?? [] as $row)
                                    <tr>
                                        <td>{{ $row['label'] }}</td>
                                        @foreach(($revenue['categories'] ?? []) as $cat)
                                            @php($val = $row['categories'][$cat] ?? 0)
                                            <td>${{ number_format($val, 2) }}</td>
                                        @endforeach
                                        <td>${{ number_format($row['total'], 2) }}</td>
                                        <td>
                                            @if(($revenue['mom'] ?? 0) > 0)
                                                <span class="text-success">+{{ $revenue['mom'] }}% ↗️</span>
                                            @elseif(($revenue['mom'] ?? 0) < 0)
                                                <span class="text-danger">{{ $revenue['mom'] }}% ↘️</span>
                                            @else
                                                <span class="text-muted">0% →</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-muted text-center">No revenue data available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function(){
    const growthCtx = document.getElementById('growthChart');
    if (growthCtx) {
        const labels = {!! json_encode($growth['labels'] ?? []) !!};
        const newSchools = {!! json_encode($growth['new_schools'] ?? []) !!};
        const activeRate = {!! json_encode($growth['active_rate'] ?? []) !!};
        const retention = {!! json_encode($growth['retention'] ?? []) !!};
        const datasets = [];
        if (Array.isArray(newSchools) && newSchools.length) {
            datasets.push({ label: 'New Schools', data: newSchools, borderColor: '#0d6efd', tension: 0.3 });
        }
        if (Array.isArray(activeRate) && activeRate.length) {
            datasets.push({ label: 'Active Rate %', data: activeRate, borderColor: '#198754', tension: 0.3, yAxisID: 'y1' });
        }
        if (Array.isArray(retention) && retention.length) {
            datasets.push({ label: 'Retention %', data: retention, borderColor: '#6f42c1', tension: 0.3, yAxisID: 'y1' });
        }
        new Chart(growthCtx, {
            type: 'line',
            data: { labels, datasets },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true },
                    y1: { position: 'right', min: 80, max: 100, grid: { drawOnChartArea: false } }
                }
            }
        });
    }

    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const breakdown = {!! json_encode($revenue['breakdown']) !!};
        const labels = breakdown.map(x => x.label);
        const data = breakdown.map(x => x.value);
        new Chart(revenueCtx, {
            type: 'pie',
            data: {
                labels,
                datasets: [{ data, backgroundColor: ['#0d6efd','#20c997','#fd7e14'] }]
            },
            options: { responsive: true }
        });
    }
})();
</script>
@endpush
