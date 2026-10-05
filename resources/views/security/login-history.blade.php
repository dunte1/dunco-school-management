@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold text-primary mb-1">
                        <i class="fas fa-history me-2"></i>Login History
                    </h4>
                    <p class="text-muted mb-0">View your recent login activity and security events</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card stat-success">
                        <div class="stat-icon">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number" id="successful-logins">127</div>
                            <div class="stat-label">Successful Logins</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card stat-danger">
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number" id="failed-logins">3</div>
                            <div class="stat-label">Failed Attempts</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card stat-warning">
                        <div class="stat-icon">
                            <i class="fas fa-devices"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number" id="unique-devices">5</div>
                            <div class="stat-label">Unique Devices</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="stat-card stat-info">
                        <div class="stat-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number" id="unique-locations">2</div>
                            <div class="stat-label">Locations</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters Card -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filters</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="date_from" class="form-label">Date From</label>
                            <input type="date" id="date_from" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Date To</label>
                            <input type="date" id="date_to" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label for="action_filter" class="form-label">Action</label>
                            <select id="action_filter" class="form-select">
                                <option value="">All Actions</option>
                                <option value="login">Login</option>
                                <option value="logout">Logout</option>
                                <option value="failed_login">Failed Login</option>
                                <option value="password_changed">Password Changed</option>
                                <option value="2fa_enabled">2FA Enabled</option>
                                <option value="2fa_disabled">2FA Disabled</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button type="button" class="btn btn-primary" onclick="applyFilters()">
                                    <i class="fas fa-search me-1"></i>Apply
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="clearFilters()">
                                    <i class="fas fa-times me-1"></i>Clear
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Login History Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-list me-2"></i>Login Activity
                    </h6>
                    <button class="btn btn-outline-primary btn-sm" onclick="exportHistory()">
                        <i class="fas fa-download me-1"></i>Export
                    </button>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="loading-spinner">
                    <div class="spinner"></div>
                    <div class="mt-2">Loading login history...</div>
                </div>

                <!-- Table -->
                <div id="tableContainer" style="display: none;">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Action</th>
                                    <th>IP Address</th>
                                    <th>Device</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="historyTableBody">
                                <!-- Data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="emptyState" class="empty-state" style="display: none;">
                    <div class="empty-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h6>No Login History Found</h6>
                    <p class="text-muted">Your login activity will appear here once available.</p>
                </div>

                <!-- Pagination -->
                <div id="paginationContainer" class="card-footer" style="display: none;">
                    <nav>
                        <ul class="pagination pagination-sm mb-0 justify-content-center" id="pagination">
                            <!-- Pagination will be loaded here -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    background: white;
    border-radius: 8px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
    transition: transform 0.2s ease;
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    font-size: 2rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.stat-content {
    flex-grow: 1;
}

.stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 500;
}

.stat-success .stat-icon { color: #10b981; }
.stat-success .stat-number { color: #10b981; }

.stat-danger .stat-icon { color: #dc2626; }
.stat-danger .stat-number { color: #dc2626; }

.stat-warning .stat-icon { color: #f59e0b; }
.stat-warning .stat-number { color: #f59e0b; }

.stat-info .stat-icon { color: #3b82f6; }
.stat-info .stat-number { color: #3b82f6; }

.card {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.card-header {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: white;
    border-bottom: none;
    padding: 1rem 1.5rem;
    border-radius: 8px 8px 0 0;
}

.form-control, .form-select {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.btn {
    border-radius: 6px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border: none;
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
}

.btn-outline-secondary {
    border-color: #d1d5db;
    color: #6b7280;
}

.btn-outline-secondary:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
    color: #374151;
}

.btn-outline-primary {
    border-color: #2563eb;
    color: #2563eb;
}

.btn-outline-primary:hover {
    background: #2563eb;
    color: white;
}

.table th {
    background: #f8fafc;
    color: #374151;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.75rem;
    border: none;
}

.table td {
    padding: 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.table tbody tr:hover {
    background: #f8fafc;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-success {
    background: #dcfce7;
    color: #166534;
}

.badge-danger {
    background: #fee2e2;
    color: #991b1b;
}

.badge-info {
    background: #dbeafe;
    color: #1e40af;
}

.badge-secondary {
    background: #f1f5f9;
    color: #64748b;
}

.device-info {
    display: flex;
    align-items: center;
}

.device-icon {
    margin-right: 0.5rem;
    color: #6b7280;
}

.loading-spinner {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 3rem;
    color: #6b7280;
}

.spinner {
    width: 32px;
    height: 32px;
    border: 3px solid #e5e7eb;
    border-top: 3px solid #2563eb;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6b7280;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #d1d5db;
}

.pagination .page-link {
    border: 1px solid #d1d5db;
    color: #6b7280;
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 4px;
    margin: 0 2px;
}

.pagination .page-link:hover {
    background: #f3f4f6;
    color: #374151;
}

.pagination .page-item.active .page-link {
    background: #2563eb;
    border-color: #2563eb;
    color: white;
}

@media (max-width: 768px) {
    .d-flex.gap-2 {
        flex-direction: column;
    }

    .btn {
        width: 100%;
    }

    .table th,
    .table td {
        padding: 0.5rem;
        font-size: 0.8rem;
    }

    .stat-card {
        margin-bottom: 0.75rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let currentFilters = {};

    // Load initial data
    loadLoginHistory();

    function loadLoginHistory(page = 1) {
        const loadingState = document.getElementById('loadingState');
        const tableContainer = document.getElementById('tableContainer');
        const emptyState = document.getElementById('emptyState');
        const paginationContainer = document.getElementById('paginationContainer');

        // Show loading state
        loadingState.style.display = 'flex';
        tableContainer.style.display = 'none';
        emptyState.style.display = 'none';
        paginationContainer.style.display = 'none';

        const params = new URLSearchParams({
            page: page,
            per_page: 20,
            ...currentFilters
        });

        fetch(`/security/login-history/data?${params}`)
            .then(response => response.json())
            .then(data => {
                loadingState.style.display = 'none';

                if (data.login_history && data.login_history.data.length > 0) {
                    displayLoginHistory(data.login_history.data);
                    displayPagination(data.pagination);
                    tableContainer.style.display = 'block';
                    paginationContainer.style.display = 'block';
                } else {
                    emptyState.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error loading login history:', error);
                loadingState.style.display = 'none';
                emptyState.style.display = 'block';
            });
    }

    function displayLoginHistory(history) {
        const tbody = document.getElementById('historyTableBody');
        tbody.innerHTML = '';

        history.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.created_at}</td>
                <td>
                    <span class="badge ${getActionBadgeClass(item.action)}">
                        ${item.action}
                    </span>
                </td>
                <td>${item.ip_address || 'Unknown'}</td>
                <td>
                    <div class="device-info">
                        <i class="fas fa-${getDeviceIcon(item.user_agent)} device-icon"></i>
                        ${item.user_agent}
                    </div>
                </td>
                <td>${item.location || 'Unknown'}</td>
                <td>
                    <span class="badge ${item.is_successful ? 'badge-success' : 'badge-danger'}">
                        ${item.is_successful ? 'Success' : 'Failed'}
                    </span>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function displayPagination(pagination) {
        const paginationContainer = document.getElementById('pagination');
        paginationContainer.innerHTML = '';

        if (pagination.last_page <= 1) return;

        // Previous button
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${pagination.current_page === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `<a class="page-link" href="#">Previous</a>`;
        if (pagination.current_page > 1) {
            prevLi.addEventListener('click', (e) => {
                e.preventDefault();
                loadLoginHistory(pagination.current_page - 1);
            });
        }
        paginationContainer.appendChild(prevLi);

        // Page numbers
        for (let i = Math.max(1, pagination.current_page - 2);
             i <= Math.min(pagination.last_page, pagination.current_page + 2);
             i++) {
            const pageLi = document.createElement('li');
            pageLi.className = `page-item ${i === pagination.current_page ? 'active' : ''}`;
            pageLi.innerHTML = `<a class="page-link" href="#">${i}</a>`;

            if (i !== pagination.current_page) {
                pageLi.addEventListener('click', (e) => {
                    e.preventDefault();
                    loadLoginHistory(i);
                });
            }

            paginationContainer.appendChild(pageLi);
        }

        // Next button
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}`;
        nextLi.innerHTML = `<a class="page-link" href="#">Next</a>`;
        if (pagination.current_page < pagination.last_page) {
            nextLi.addEventListener('click', (e) => {
                e.preventDefault();
                loadLoginHistory(pagination.current_page + 1);
            });
        }
        paginationContainer.appendChild(nextLi);
    }

    function getActionBadgeClass(action) {
        const lowerAction = action.toLowerCase();
        if (lowerAction.includes('login') && !lowerAction.includes('failed')) return 'badge-success';
        if (lowerAction.includes('logout')) return 'badge-info';
        if (lowerAction.includes('failed')) return 'badge-danger';
        return 'badge-secondary';
    }

    function getDeviceIcon(userAgent) {
        if (!userAgent) return 'question';
        const lowerAgent = userAgent.toLowerCase();
        if (lowerAgent.includes('mobile') || lowerAgent.includes('android')) return 'mobile-alt';
        if (lowerAgent.includes('ipad')) return 'tablet-alt';
        if (lowerAgent.includes('iphone')) return 'mobile-alt';
        return 'desktop';
    }

    // Global functions
    window.applyFilters = function() {
        currentFilters = {
            date_from: document.getElementById('date_from').value,
            date_to: document.getElementById('date_to').value,
            action: document.getElementById('action_filter').value
        };

        // Remove empty filters
        Object.keys(currentFilters).forEach(key => {
            if (!currentFilters[key]) delete currentFilters[key];
        });

        loadLoginHistory(1);
    };

    window.clearFilters = function() {
        document.getElementById('date_from').value = '';
        document.getElementById('date_to').value = '';
        document.getElementById('action_filter').value = '';
        currentFilters = {};
        loadLoginHistory(1);
    };

    window.exportHistory = function() {
        const params = new URLSearchParams(currentFilters);
        window.open(`/security/login-history/export?${params}`, '_blank');
    };
});
</script>
@endsection
