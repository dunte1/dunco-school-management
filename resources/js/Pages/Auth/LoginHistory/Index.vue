<template>
  <AppLayout>
    <div class="login-history">
      <div class="page-header">
        <h1>Login History</h1>
        <p>View your recent login activity and security events</p>
      </div>

      <div class="history-content">
        <!-- Filters -->
        <div class="filters-card">
          <div class="filters-header">
            <h2>Filters</h2>
            <button class="btn-secondary" @click="clearFilters">
              <i class="fas fa-times"></i>
              Clear Filters
            </button>
          </div>

          <div class="filters-body">
            <div class="filter-group">
              <label>Date From:</label>
              <input
                type="date"
                v-model="filters.date_from"
                class="form-control"
              >
            </div>

            <div class="filter-group">
              <label>Date To:</label>
              <input
                type="date"
                v-model="filters.date_to"
                class="form-control"
              >
            </div>

            <div class="filter-group">
              <label>Action:</label>
              <select v-model="filters.action" class="form-control">
                <option value="">All Actions</option>
                <option value="login">Login</option>
                <option value="logout">Logout</option>
                <option value="failed_login">Failed Login</option>
                <option value="password_changed">Password Changed</option>
                <option value="2fa_enabled">2FA Enabled</option>
                <option value="2fa_disabled">2FA Disabled</option>
              </select>
            </div>

            <div class="filter-actions">
              <button class="btn-primary" @click="applyFilters">
                <i class="fas fa-search"></i>
                Apply Filters
              </button>
              <button class="btn-secondary" @click="exportHistory">
                <i class="fas fa-download"></i>
                Export CSV
              </button>
            </div>
          </div>
        </div>

        <!-- History Table -->
        <div class="history-card">
          <div class="card-header">
            <h2>Recent Activity</h2>
            <div class="total-count">
              {{ pagination.total }} total entries
            </div>
          </div>

          <div class="card-body">
            <div v-if="loading" class="loading-state">
              <i class="fas fa-spinner fa-spin"></i>
              Loading history...
            </div>

            <div v-else-if="loginHistory.length === 0" class="empty-state">
              <i class="fas fa-history"></i>
              <h3>No login history found</h3>
              <p>Your login activity will appear here once you start using the system.</p>
            </div>

            <div v-else class="history-table-container">
              <table class="history-table">
                <thead>
                  <tr>
                    <th>Date & Time</th>
                    <th>Action</th>
                    <th>IP Address</th>
                    <th>Device</th>
                    <th>Location</th>
                    <th>Description</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="entry in loginHistory" :key="entry.id">
                    <td>{{ entry.created_at }}</td>
                    <td>
                      <span class="action-badge" :class="getActionClass(entry.action)">
                        <i :class="getActionIcon(entry.action)"></i>
                        {{ formatAction(entry.action) }}
                      </span>
                    </td>
                    <td>
                      <code>{{ entry.ip_address }}</code>
                    </td>
                    <td>{{ entry.user_agent }}</td>
                    <td>{{ entry.location }}</td>
                    <td>{{ entry.description || '-' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div v-if="pagination.last_page > 1" class="pagination-container">
              <div class="pagination-info">
                Showing {{ ((pagination.current_page - 1) * pagination.per_page) + 1 }} to
                {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }}
                of {{ pagination.total }} entries
              </div>

              <div class="pagination-controls">
                <button
                  class="btn-secondary"
                  :disabled="pagination.current_page <= 1"
                  @click="changePage(pagination.current_page - 1)"
                >
                  <i class="fas fa-chevron-left"></i>
                  Previous
                </button>

                <div class="page-numbers">
                  <button
                    v-for="page in pageNumbers"
                    :key="page"
                    class="btn-page"
                    :class="{ 'active': page === pagination.current_page }"
                    @click="changePage(page)"
                  >
                    {{ page }}
                  </button>
                </div>

                <button
                  class="btn-secondary"
                  :disabled="pagination.current_page >= pagination.last_page"
                  @click="changePage(pagination.current_page + 1)"
                >
                  Next
                  <i class="fas fa-chevron-right"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Security Summary -->
        <div class="summary-card">
          <div class="card-header">
            <h2>Security Summary</h2>
            <i class="fas fa-chart-bar"></i>
          </div>

          <div class="card-body">
            <div class="summary-stats">
              <div class="stat-item">
                <div class="stat-value">{{ stats.total_logins }}</div>
                <div class="stat-label">Total Logins</div>
              </div>
              <div class="stat-item">
                <div class="stat-value">{{ stats.failed_attempts }}</div>
                <div class="stat-label">Failed Attempts</div>
              </div>
              <div class="stat-item">
                <div class="stat-value">{{ stats.unique_devices }}</div>
                <div class="stat-label">Devices Used</div>
              </div>
              <div class="stat-item">
                <div class="stat-value">{{ stats.last_login }}</div>
                <div class="stat-label">Last Login</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Reactive data
const loginHistory = ref([]);
const pagination = ref({
  current_page: 1,
  last_page: 1,
  per_page: 20,
  total: 0
});

const filters = ref({
  date_from: '',
  date_to: '',
  action: ''
});

const loading = ref(false);
const stats = ref({
  total_logins: 0,
  failed_attempts: 0,
  unique_devices: 0,
  last_login: 'Never'
});

// Computed properties
const pageNumbers = computed(() => {
  const pages = [];
  const current = pagination.value.current_page;
  const last = pagination.value.last_page;

  if (last <= 7) {
    for (let i = 1; i <= last; i++) {
      pages.push(i);
    }
  } else {
    if (current <= 4) {
      pages.push(1, 2, 3, 4, 5, '...', last);
    } else if (current >= last - 3) {
      pages.push(1, '...', last - 4, last - 3, last - 2, last - 1, last);
    } else {
      pages.push(1, '...', current - 1, current, current + 1, '...', last);
    }
  }

  return pages;
});

// Lifecycle
onMounted(() => {
  loadHistory();
  loadStats();
});

// Methods
const loadHistory = async (page = 1) => {
  loading.value = true;

  try {
    const params = new URLSearchParams({
      page: page.toString(),
      ...filters.value
    });

    const response = await fetch(`/security/login-history/data?${params}`);
    const data = await response.json();

    loginHistory.value = data.login_history;
    pagination.value = data.pagination;
  } catch (error) {
    console.error('Failed to load login history:', error);
  } finally {
    loading.value = false;
  }
};

const loadStats = async () => {
  try {
    // In a real implementation, you might have a separate endpoint for stats
    // For now, we'll calculate basic stats from the loaded data
    const response = await fetch('/security/login-history/data');
    const data = await response.json();

    const entries = data.login_history || [];
    stats.value = {
      total_logins: entries.filter(e => e.is_successful).length,
      failed_attempts: entries.filter(e => !e.is_successful).length,
      unique_devices: new Set(entries.map(e => e.user_agent)).size,
      last_login: entries.length > 0 ? entries[0].created_at : 'Never'
    };
  } catch (error) {
    console.error('Failed to load stats:', error);
  }
};

const applyFilters = () => {
  loadHistory(1);
  loadStats();
};

const clearFilters = () => {
  filters.value = {
    date_from: '',
    date_to: '',
    action: ''
  };
  applyFilters();
};

const changePage = (page) => {
  if (page !== '...' && page >= 1 && page <= pagination.value.last_page) {
    loadHistory(page);
  }
};

const exportHistory = async () => {
  try {
    const params = new URLSearchParams(filters.value);
    window.open(`/security/login-history/export?${params}`, '_blank');
  } catch (error) {
    console.error('Failed to export history:', error);
  }
};

// Helper functions
const getActionClass = (action) => {
  if (action.includes('login') && !action.includes('failed')) {
    return 'success';
  }
  if (action.includes('failed') || action.includes('error')) {
    return 'danger';
  }
  if (action.includes('logout')) {
    return 'warning';
  }
  if (action.includes('password') || action.includes('2fa')) {
    return 'info';
  }
  return 'secondary';
};

const getActionIcon = (action) => {
  if (action.includes('login')) {
    return 'fas fa-sign-in-alt';
  }
  if (action.includes('logout')) {
    return 'fas fa-sign-out-alt';
  }
  if (action.includes('password')) {
    return 'fas fa-key';
  }
  if (action.includes('2fa')) {
    return 'fas fa-mobile-alt';
  }
  return 'fas fa-info-circle';
};

const formatAction = (action) => {
  return action.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
};
</script>

<style scoped>
.login-history {
  max-width: 1400px;
  margin: 0 auto;
  padding: 20px;
}

.page-header {
  margin-bottom: 30px;
  text-align: center;
}

.page-header h1 {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 10px;
  color: #1f2937;
}

.page-header p {
  font-size: 1.1rem;
  color: #6b7280;
}

.history-content {
  display: flex;
  flex-direction: column;
  gap: 25px;
}

.filters-card, .history-card, .summary-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  overflow: hidden;
}

.card-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-header h2 {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0;
}

.card-header i {
  font-size: 1.5rem;
  opacity: 0.8;
}

.card-body {
  padding: 25px;
}

.filters-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.filters-body {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 20px;
}

.filter-group {
  display: flex;
  flex-direction: column;
}

.filter-group label {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 5px;
}

.form-control {
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 0.95rem;
}

.form-control:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.filter-actions {
  display: flex;
  gap: 10px;
  align-items: end;
}

.btn-primary, .btn-secondary {
  padding: 10px 16px;
  border: none;
  border-radius: 6px;
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-primary {
  background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
  color: white;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px rgba(34, 197, 94, 0.3);
}

.btn-secondary {
  background: #f3f4f6;
  color: #1f2937;
  border: 1px solid #d1d5db;
}

.btn-secondary:hover {
  background: #e5e7eb;
}

.total-count {
  font-size: 0.9rem;
  opacity: 0.8;
}

.loading-state, .empty-state {
  text-align: center;
  padding: 40px;
  color: #6b7280;
}

.loading-state i {
  font-size: 2rem;
  margin-bottom: 15px;
}

.empty-state i {
  font-size: 3rem;
  margin-bottom: 15px;
  opacity: 0.5;
}

.empty-state h3 {
  margin-bottom: 10px;
  color: #1f2937;
}

.history-table-container {
  overflow-x: auto;
}

.history-table {
  width: 100%;
  border-collapse: collapse;
}

.history-table th {
  background: #f8fafc;
  padding: 15px;
  text-align: left;
  font-weight: 600;
  color: #1f2937;
  border-bottom: 1px solid #e5e7eb;
}

.history-table td {
  padding: 15px;
  border-bottom: 1px solid #f3f4f6;
  color: #4b5563;
}

.history-table tr:hover {
  background: #f8fafc;
}

.action-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 600;
}

.action-badge.success {
  background: #dcfce7;
  color: #166534;
}

.action-badge.danger {
  background: #fee2e2;
  color: #b91c1c;
}

.action-badge.warning {
  background: #fef3c7;
  color: #92400e;
}

.action-badge.info {
  background: #dbeafe;
  color: #1e40af;
}

.action-badge.secondary {
  background: #f3f4f6;
  color: #374151;
}

.pagination-container {
  margin-top: 25px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.pagination-info {
  color: #6b7280;
  font-size: 0.9rem;
}

.pagination-controls {
  display: flex;
  align-items: center;
  gap: 10px;
}

.btn-page {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  background: white;
  color: #1f2937;
  border-radius: 4px;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-page:hover {
  background: #f3f4f6;
}

.btn-page.active {
  background: #667eea;
  color: white;
  border-color: #667eea;
}

.summary-stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 20px;
}

.stat-item {
  text-align: center;
  padding: 15px;
  background: #f8fafc;
  border-radius: 8px;
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
  color: #667eea;
  margin-bottom: 5px;
}

.stat-label {
  color: #6b7280;
  font-size: 0.9rem;
  font-weight: 600;
}

/* Dark mode support */
.dark .login-history {
  color: #f9fafb;
}

.dark .page-header h1 {
  color: #f9fafb;
}

.dark .filters-card, .dark .history-card, .dark .summary-card {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .history-table th {
  background: #111827;
  color: #f9fafb;
  border-color: #374151;
}

.dark .history-table td {
  color: #d1d5db;
  border-color: #374151;
}

.dark .history-table tr:hover {
  background: #111827;
}

.dark .form-control {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
}

.dark .btn-secondary {
  background: #374151;
  color: #f9fafb;
  border-color: #4b5563;
}

.dark .btn-secondary:hover {
  background: #4b5563;
}

.dark .stat-item {
  background: #111827;
}

.dark .stat-value {
  color: #a5b4fc;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .login-history {
    padding: 15px;
  }

  .page-header h1 {
    font-size: 1.75rem;
  }

  .filters-body {
    grid-template-columns: 1fr;
  }

  .filter-actions {
    flex-direction: column;
    align-items: stretch;
  }

  .pagination-container {
    flex-direction: column;
    gap: 15px;
    align-items: stretch;
  }

  .summary-stats {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
