<template>
  <AppLayout>
    <div class="admin-finance">
      <div class="page-header">
        <h1>Financial Management</h1>
        <p>Manage finances across all schools in your system</p>
      </div>
      
      <div class="finance-overview">
        <div class="stats-cards">
          <div class="stat-card">
            <div class="stat-icon revenue">
              <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">${{ totalRevenue.toLocaleString() }}</div>
              <div class="stat-label">Total Revenue (Monthly)</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon pending">
              <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">${{ pendingPayments.toLocaleString() }}</div>
              <div class="stat-label">Pending Payments</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon overdue">
              <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">${{ overduePayments.toLocaleString() }}</div>
              <div class="stat-label">Overdue Payments</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon schools">
              <i class="fas fa-school"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ schools.length }}</div>
              <div class="stat-label">Schools</div>
            </div>
          </div>
        </div>
        
        <div class="charts-section">
          <div class="chart-card">
            <h3>Revenue by School</h3>
            <div class="chart-container">
              <canvas ref="revenueChart"></canvas>
            </div>
          </div>
          
          <div class="chart-card">
            <h3>Payment Status Distribution</h3>
            <div class="chart-container">
              <canvas ref="paymentChart"></canvas>
            </div>
          </div>
        </div>
      </div>
      
      <div class="finance-controls">
        <div class="filter-controls">
          <select v-model="selectedSchool">
            <option value="">All Schools</option>
            <option v-for="school in schools" :key="school.id" :value="school.id">
              {{ school.name }}
            </option>
          </select>
          
          <select v-model="dateRange">
            <option value="monthly">This Month</option>
            <option value="quarterly">This Quarter</option>
            <option value="yearly">This Year</option>
          </select>
          
          <select v-model="paymentStatus">
            <option value="">All Payments</option>
            <option value="completed">Completed</option>
            <option value="pending">Pending</option>
            <option value="overdue">Overdue</option>
          </select>
        </div>
        
        <button class="btn-primary" @click="generateReport">
          <i class="fas fa-file-export"></i>
          Generate Report
        </button>
      </div>
      
      <div class="payments-table">
        <div class="table-header">
          <div class="header-cell">Payment ID</div>
          <div class="header-cell">Student</div>
          <div class="header-cell">School</div>
          <div class="header-cell">Amount</div>
          <div class="header-cell">Date</div>
          <div class="header-cell">Status</div>
          <div class="header-cell">Actions</div>
        </div>
        
        <div 
          v-for="payment in paginatedPayments" 
          :key="payment.id"
          class="table-row"
        >
          <div class="table-cell">{{ payment.id }}</div>
          <div class="table-cell">{{ payment.student }}</div>
          <div class="table-cell">{{ payment.school }}</div>
          <div class="table-cell">${{ payment.amount.toFixed(2) }}</div>
          <div class="table-cell">{{ formatDate(payment.date) }}</div>
          <div class="table-cell">
            <span class="status-badge" :class="payment.status">
              {{ payment.status }}
            </span>
          </div>
          <div class="table-cell">
            <div class="action-buttons">
              <button class="btn-icon" @click="viewPayment(payment)" title="View Details">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn-icon" @click="sendReminder(payment)" title="Send Reminder">
                <i class="fas fa-bell"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <div class="pagination">
        <button 
          class="pagination-btn" 
          :disabled="currentPage === 1"
          @click="currentPage--"
        >
          <i class="fas fa-chevron-left"></i>
        </button>
        
        <span class="pagination-info">
          Page {{ currentPage }} of {{ totalPages }}
        </span>
        
        <button 
          class="pagination-btn" 
          :disabled="currentPage === totalPages"
          @click="currentPage++"
        >
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
      
      <div v-if="filteredPayments.length === 0" class="no-payments">
        <i class="fas fa-money-bill-wave"></i>
        <p>No payments found</p>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Chart from 'chart.js/auto';

// Reactive data
const selectedSchool = ref('');
const dateRange = ref('monthly');
const paymentStatus = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const revenueChart = ref(null);
const paymentChart = ref(null);
const revenueChartInstance = ref(null);
const paymentChartInstance = ref(null);

// Mock data - in a real app, this would come from API
const schools = ref([
  { id: 1, name: 'Greenwood High School' },
  { id: 2, name: 'Riverside Elementary' },
  { id: 3, name: 'Mountainview Academy' }
]);

const payments = ref([
  {
    id: 'PAY-001',
    student: 'John Smith',
    school: 'Greenwood High School',
    amount: 1500.00,
    date: '2025-09-01',
    status: 'completed'
  },
  {
    id: 'PAY-002',
    student: 'Sarah Johnson',
    school: 'Greenwood High School',
    amount: 500.00,
    date: '2025-09-05',
    status: 'completed'
  },
  {
    id: 'PAY-003',
    student: 'Robert Wilson',
    school: 'Riverside Elementary',
    amount: 1250.00,
    date: '2025-09-10',
    status: 'pending'
  },
  {
    id: 'PAY-004',
    student: 'Emily Davis',
    school: 'Greenwood High School',
    amount: 750.00,
    date: '2025-08-15',
    status: 'overdue'
  },
  {
    id: 'PAY-005',
    student: 'Michael Brown',
    school: 'Mountainview Academy',
    amount: 2000.00,
    date: '2025-09-12',
    status: 'completed'
  },
  {
    id: 'PAY-006',
    student: 'Jennifer Lee',
    school: 'Riverside Elementary',
    amount: 1750.00,
    date: '2025-08-20',
    status: 'overdue'
  }
]);

// Computed properties
const totalRevenue = computed(() => {
  return payments.value
    .filter(payment => payment.status === 'completed')
    .reduce((total, payment) => total + payment.amount, 0);
});

const pendingPayments = computed(() => {
  return payments.value
    .filter(payment => payment.status === 'pending')
    .reduce((total, payment) => total + payment.amount, 0);
});

const overduePayments = computed(() => {
  return payments.value
    .filter(payment => payment.status === 'overdue')
    .reduce((total, payment) => total + payment.amount, 0);
});

const filteredPayments = computed(() => {
  return payments.value.filter(payment => {
    const schoolMatch = selectedSchool.value ? 
      schools.value.find(s => s.id === selectedSchool.value)?.name === payment.school : true;
    const statusMatch = paymentStatus.value ? payment.status === paymentStatus.value : true;
    return schoolMatch && statusMatch;
  });
});

const paginatedPayments = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredPayments.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(filteredPayments.value.length / itemsPerPage.value);
});

// Methods
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const viewPayment = (payment) => {
  // In a real app, this would show payment details
  alert(`Viewing details for payment ${payment.id}`);
};

const sendReminder = (payment) => {
  // In a real app, this would send a payment reminder
  alert(`Sending reminder for payment ${payment.id}`);
};

const generateReport = () => {
  // In a real app, this would generate and download a financial report
  alert('Generating financial report');
};

const initCharts = () => {
  // Destroy existing charts if they exist
  if (revenueChartInstance.value) {
    revenueChartInstance.value.destroy();
  }
  
  if (paymentChartInstance.value) {
    paymentChartInstance.value.destroy();
  }
  
  // Initialize Revenue by School Chart
  const revenueCtx = revenueChart.value.getContext('2d');
  revenueChartInstance.value = new Chart(revenueCtx, {
    type: 'bar',
    data: {
      labels: schools.value.map(school => school.name),
      datasets: [{
        label: 'Revenue',
        data: schools.value.map(school => {
          return payments.value
            .filter(p => p.school === school.name && p.status === 'completed')
            .reduce((total, p) => total + p.amount, 0);
        }),
        backgroundColor: '#3b82f6',
        borderColor: '#2563eb',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return '$' + value.toLocaleString();
            }
          }
        }
      }
    }
  });
  
  // Initialize Payment Status Distribution Chart
  const paymentCtx = paymentChart.value.getContext('2d');
  paymentChartInstance.value = new Chart(paymentCtx, {
    type: 'doughnut',
    data: {
      labels: ['Completed', 'Pending', 'Overdue'],
      datasets: [{
        data: [
          payments.value.filter(p => p.status === 'completed').length,
          payments.value.filter(p => p.status === 'pending').length,
          payments.value.filter(p => p.status === 'overdue').length
        ],
        backgroundColor: [
          '#10b981',
          '#f59e0b',
          '#ef4444'
        ],
        borderWidth: 0
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
};

// Lifecycle
onMounted(() => {
  // In a real app, we would fetch data from the API here
  nextTick(() => {
    initCharts();
  });
});
</script>

<style scoped>
.admin-finance {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.page-header {
  margin-bottom: 30px;
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

.finance-overview {
  display: grid;
  grid-template-columns: 1fr;
  gap: 30px;
  margin-bottom: 30px;
}

.stats-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
}

.stat-card {
  display: flex;
  align-items: center;
  padding: 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 15px;
  font-size: 1.5rem;
}

.stat-icon.revenue {
  background: #dbeafe;
  color: #3b82f6;
}

.stat-icon.pending {
  background: #ffedd5;
  color: #f97316;
}

.stat-icon.overdue {
  background: #fee2e2;
  color: #ef4444;
}

.stat-icon.schools {
  background: #f0fdf4;
  color: #16a34a;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
}

.stat-label {
  font-size: 0.9rem;
  color: #6b7280;
}

.charts-section {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.chart-card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.chart-card h3 {
  margin-bottom: 20px;
  color: #1f2937;
}

.chart-container {
  height: 300px;
  position: relative;
}

.finance-controls {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  flex-wrap: wrap;
  gap: 15px;
  background: white;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.filter-controls {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
}

.filter-controls select {
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
}

.payments-table {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 30px;
}

.table-header {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
  background: #f9fafb;
  font-weight: 600;
  padding: 15px 20px;
}

.table-row {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
  padding: 15px 20px;
  border-bottom: 1px solid #e5e7eb;
}

.table-row:last-child {
  border-bottom: none;
}

.table-row:hover {
  background: #f9fafb;
}

.header-cell,
.table-cell {
  padding: 0 5px;
  display: flex;
  align-items: center;
}

.status-badge {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.status-badge.completed {
  background: #dcfce7;
  color: #166534;
}

.status-badge.pending {
  background: #ffedd5;
  color: #9a3412;
}

.status-badge.overdue {
  background: #fee2e2;
  color: #991b1b;
}

.action-buttons {
  display: flex;
  gap: 10px;
}

.btn-icon {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: none;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-icon:hover {
  background: #f3f4f6;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 15px;
  margin-bottom: 30px;
}

.pagination-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: white;
  border: 1px solid #d1d5db;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.pagination-btn:hover:not(:disabled) {
  background: #f3f4f6;
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-info {
  font-weight: 500;
  color: #1f2937;
}

.no-payments {
  text-align: center;
  padding: 60px 20px;
  color: #6b7280;
}

.no-payments i {
  font-size: 4rem;
  margin-bottom: 20px;
  color: #d1d5db;
}

/* Dark mode support */
.dark .admin-finance {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .chart-card h3,
.stat-value {
  color: #f9fafb;
}

.dark .page-header p,
.stat-label {
  color: #d1d5db;
}

.dark .stat-card,
.dark .chart-card,
.dark .finance-controls {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .payments-table {
  border-color: #374151;
}

.dark .table-header {
  background: #111827;
}

.dark .table-row {
  border-bottom-color: #374151;
}

.dark .table-row:hover {
  background: #111827;
}

.dark .filter-controls select {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
}

.dark .btn-icon:hover {
  background: #374151;
}

.dark .pagination-btn {
  background: #1f2937;
  border-color: #374151;
}

.dark .pagination-btn:hover:not(:disabled) {
  background: #374151;
}

.dark .pagination-info {
  color: #f9fafb;
}

.dark .no-payments {
  color: #9ca3af;
}

.dark .no-payments i {
  color: #4b5563;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .admin-finance {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .finance-controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .filter-controls {
    flex-direction: column;
  }
  
  .filter-controls select {
    width: 100%;
  }
  
  .charts-section {
    grid-template-columns: 1fr;
  }
  
  .table-row {
    grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
    font-size: 0.9rem;
  }
  
  .stat-value {
    font-size: 1.2rem;
  }
}
</style>