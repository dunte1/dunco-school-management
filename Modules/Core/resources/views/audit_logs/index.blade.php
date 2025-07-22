<x-core::layouts.master>
    <div class="container d-flex justify-content-center align-items-start" style="min-height: 80vh;">
        <div class="w-100" style="max-width: 1100px;">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm">
                    <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Audit Logs</li>
                </ol>
            </nav>
            <div class="card shadow rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="mb-0"><i class="fas fa-history"></i> Audit Logs</h1>
                        <button class="btn btn-success" onclick="exportToCSV()">
                            <i class="fas fa-file-csv"></i> Export CSV
                        </button>
                    </div>
                    <div class="row mb-3 g-2">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="userFilter" placeholder="Filter by User ID...">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="actionFilter" placeholder="Filter by Action...">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="dateFilter">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-secondary w-100" onclick="clearFilters()">Clear Filters</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle mb-0" id="auditLogTable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Entity</th>
                                    <th>Created At</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auditLogs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>{{ $log->user_id }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>#</td>
                                    <td>{{ $log->created_at }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewLogModal" onclick="showLogDetails({{ $log->id }}, '{{ $log->user_id }}', '{{ $log->action }}', '{{ $log->description }}', '{{ $log->created_at }}', @json($log->old_values), @json($log->new_values))" title="View" data-bs-placement="top" data-bs-title="View">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $auditLogs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Log Modal -->
    <div class="modal fade" id="viewLogModal" tabindex="-1" aria-labelledby="viewLogModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewLogModalLabel">Audit Log Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <ul class="list-group">
                <li class="list-group-item"><strong>ID:</strong> <span id="logId"></span></li>
                <li class="list-group-item"><strong>User ID:</strong> <span id="logUser"></span></li>
                <li class="list-group-item"><strong>Action:</strong> <span id="logAction"></span></li>
                <li class="list-group-item"><strong>Description:</strong> <span id="logDescription"></span></li>
                <li class="list-group-item"><strong>Created At:</strong> <span id="logCreatedAt"></span></li>
                <li class="list-group-item"><strong>Old Values:</strong> <pre id="logOldValues" class="mb-0"></pre></li>
                <li class="list-group-item"><strong>New Values:</strong> <pre id="logNewValues" class="mb-0"></pre></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <style>
    .table td, .table th {
        vertical-align: middle;
    }
    .table-hover tbody tr:hover {
        background-color: #f1f3f6;
        transition: background 0.2s;
    }
    .breadcrumb {
        background: #fff;
        font-size: 1rem;
    }
    .card {
        border-radius: 1.5rem;
    }
    </style>
    <script>
        // Filter logic
        const userFilter = document.getElementById('userFilter');
        const actionFilter = document.getElementById('actionFilter');
        const dateFilter = document.getElementById('dateFilter');
        const table = document.getElementById('auditLogTable');
        userFilter.addEventListener('keyup', filterTable);
        actionFilter.addEventListener('keyup', filterTable);
        dateFilter.addEventListener('change', filterTable);

        function filterTable() {
            let userVal = userFilter.value.toLowerCase();
            let actionVal = actionFilter.value.toLowerCase();
            let dateVal = dateFilter.value;
            let rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                let user = row.children[1].textContent.toLowerCase();
                let action = row.children[2].textContent.toLowerCase();
                let date = row.children[4].textContent.substring(0, 10);
                let show = true;
                if (userVal && !user.includes(userVal)) show = false;
                if (actionVal && !action.includes(actionVal)) show = false;
                if (dateVal && date !== dateVal) show = false;
                row.style.display = show ? '' : 'none';
            });
        }
        function clearFilters() {
            userFilter.value = '';
            actionFilter.value = '';
            dateFilter.value = '';
            filterTable();
        }

        // View log details modal
        function showLogDetails(id, user, action, description, createdAt, oldValues, newValues) {
            document.getElementById('logId').textContent = id;
            document.getElementById('logUser').textContent = user;
            document.getElementById('logAction').textContent = action;
            document.getElementById('logDescription').textContent = description;
            document.getElementById('logCreatedAt').textContent = createdAt;
            document.getElementById('logOldValues').textContent = JSON.stringify(oldValues, null, 2);
            document.getElementById('logNewValues').textContent = JSON.stringify(newValues, null, 2);
        }
        window.showLogDetails = showLogDetails;

        // Export to CSV
        function exportToCSV() {
            let csv = 'ID,User,Action,Entity,Created At,Description\n';
            let rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                if (row.style.display === 'none') return;
                let cols = Array.from(row.children).map(td => '"' + td.textContent.replace(/"/g, '""') + '"');
                csv += cols.slice(0, 5).join(',') + ',"' + row.querySelector('button[data-bs-toggle]').getAttribute('onclick').replace(/'/g, '"') + '"\n';
            });
            let blob = new Blob([csv], { type: 'text/csv' });
            let link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'audit_logs.csv';
            link.click();
        }
        window.exportToCSV = exportToCSV;

        // Enable Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // AJAX polling for real-time updates
        function fetchAuditLogs() {
            const params = new URLSearchParams();
            if (userFilter.value) params.append('user_id', userFilter.value);
            if (actionFilter.value) params.append('action', actionFilter.value);
            if (dateFilter.value) params.append('date', dateFilter.value);
            fetch('/core/audit-logs/fetch?' + params.toString())
                .then(response => response.json())
                .then(data => {
                    const tbody = table.querySelector('tbody');
                    tbody.innerHTML = '';
                    data.data.forEach(log => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>${log.id}</td>
                            <td>${log.user_id ?? ''}</td>
                            <td>${log.action}</td>
                            <td>#</td>
                            <td>${log.created_at}</td>
                            <td>
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewLogModal"
                                    onclick="showLogDetails(${log.id}, '${log.user_id ?? ''}', '${log.action}', '${log.description ?? ''}', '${log.created_at}', ${JSON.stringify(log.old_values)}, ${JSON.stringify(log.new_values)})" title="View" data-bs-placement="top" data-bs-title="View">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                });
        }
        setInterval(fetchAuditLogs, 5000); // Poll every 5 seconds
        // Optionally, fetch immediately on page load
        fetchAuditLogs();
    </script>
</x-core::layouts.master> 