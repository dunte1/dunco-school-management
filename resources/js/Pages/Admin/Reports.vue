<template>
  <AppLayout>
    <div class="admin-reports">
      <div class="page-header">
        <h1>System Reports</h1>
        <p>Comprehensive reports and analytics for your school system</p>
      </div>
      
      <div class="reports-controls">
        <div class="filter-controls">
          <select v-model="selectedSchool">
            <option value="">All Schools</option>
            <option v-for="school in schools" :key="school.id" :value="school.id">
              {{ school.name }}
            </option>
          </select>
          
          <select v-model="reportType">
            <option value="attendance">Attendance Report</option>
            <option value="finance">Financial Report</option>
            <option value="academic">Academic Performance</option>
            <option value="user">User Activity</option>
          </select>
          
          <select v-model="dateRange">
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="quarterly">Quarterly</option>
            <option value="yearly">Yearly</option>
          </select>
        </div>
        
        <button class="btn-primary" @click="generateReport">
          <i class="fas fa-file-export"></i>
          Generate Report
        </button>
      </div>
      
      <div class="reports-overview">
        <div class="report-cards">
          <div class="report-card">
            <div class="report-icon">
              <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="report-info">
              <div class="report-value">{{ attendanceRate }}%</div>
              <div class="report-label">Avg. Attendance</div>
            </div>
          </div>
          
          <div class="report-card">
            <div class="report-icon">
              <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="report-info">
              <div class="report-value">${{ totalRevenue.toLocaleString() }}</div>
              <div class="report-label">Total Revenue</div>
            </div>
          </div>
          
          <div class="report-card">
            <div class="report-icon">
              <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="report-info">
              <div class="report-value">{{ avgGrade }}</div>
              <div class="report-label">Avg. Grade</div>
            </div>
          </div>
          
          <div class="report-card">
            <div class="report-icon">
              <i class="fas fa-users"></i>
            </div>
            <div class="report-info">
              <div class="report-value">{{ activeUsers }}</div>
              <div class="report-label">Active Users</div>
            </div>
          </div>
        </div>
        
        <div class="charts-section">
          <div class="chart-card">
            <h3>{{ reportTitles[reportType] }}</h3>
            <div class="chart-container">
              <canvas ref="mainChart"></canvas>
            </div>
          </div>
          
          <div class="chart-card">
            <h3>Trends</h3>
            <div class="chart-container">
              <canvas ref="trendChart"></canvas>
            </div>
          </div>
        </div>
      </div>
      
      <div class="reports-sections">
        <div class="section">
          <h2>Recent Reports</h2>
          <div class="reports-table">
            <div class="table-header">
              <div class="header-cell">Report</div>
              <div class="header-cell">Type</div>
              <div class="header-cell">Date Generated</div>
              <div class="header-cell">Status</div>
              <div class="header-cell">Actions</div>
            </div>
            
            <div 
              v-for="report in recentReports" 
              :key="report.id"
              class="table-row"
            >
              <div class="table-cell">{{ report.name }}</div>
              <div class="table-cell">{{ report.type }}</div>
              <div class="table-cell">{{ formatDate(report.date) }}</div>
              <div class="table-cell">
                <span class="status-badge" :class="report.status">
                  {{ report.status }}
                </span>
              </div>
              <div class="table-cell">
                <div class="action-buttons">
                  <button class="btn-icon" @click="viewReport(report)" title="View">
                    <i class="fas fa-eye"></i>
                  </button>
                  <button class="btn-icon" @click="downloadReport(report)" title="Download">
                    <i class="fas fa-download"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="section">
          <h2>Scheduled Reports</h2>
          <div class="scheduled-reports">
            <div 
              v-for="scheduled in scheduledReports" 
              :key="scheduled.id"
              class="scheduled-report"
            >
              <div class="report-info">
                <h4>{{ scheduled.name }}</h4>
                <p>{{ scheduled.frequency }} report for {{ scheduled.recipients }} recipients</p>
              </div>
              <div class="report-actions">
                <button class="btn-secondary" @click="editSchedule(scheduled)">
                  <i class="fas fa-edit"></i>
                  Edit
                </button>
                <button class="btn-secondary" @click="deleteSchedule(scheduled)">
                  <i class="fas fa-trash"></i>
                  Delete
                </button>
              </div>
            </div>
            
            <button class="btn-primary add-schedule" @click="addSchedule">
              <i class="fas fa-plus"></i>
              Add Schedule
            </button>
          </div>
        </div>
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
const reportType = ref('attendance');
const dateRange = ref('monthly');
const mainChart = ref(null);
const trendChart = ref(null);
const mainChartInstance = ref(null);
const trendChartInstance = ref(null);

const reportTitles = {
  attendance: 'Attendance Rate',
  finance: 'Revenue Trend',
  academic: 'Grade Distribution',
  user: 'User Activity'
};

// Mock data - in a real app, this would come from API
const schools = ref([
  { id: 1, name: 'Greenwood High School' },
  { id: 2, name: 'Riverside Elementary' },
  { id: 3, name: 'Mountainview Academy' }
]);

const recentReports = ref([
  {
    id: 1,
    name: 'September Attendance Report',
    type: 'Attendance',
    date: '2025-09-15',
    status: 'completed'
  },
  {
    id: 2,
    name: 'Q3 Financial Report',
    type: 'Finance',
    date: '2025-09-10',
    status: 'completed'
  },
  {
    id: 3,
    name: 'Midterm Grades Report',
    type: 'Academic',
    date: '2025-09-05',
    status: 'completed'
  }
]);

const scheduledReports = ref([
  {
    id: 1,
    name: 'Monthly Attendance',
    frequency: 'Monthly',
    recipients: 5
  },
  {
    id: 2,
    name: 'Weekly Finance',
    frequency: 'Weekly',
    recipients: 3
  }
]);

// Computed properties
const attendanceRate = computed(() => 92);
const totalRevenue = computed(() => 45000);
const avgGrade = computed(() => 'B+');
const activeUsers = computed(() => 2150);

// Methods
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const generateReport = () => {
  // In a real app, this would generate a new report
  alert(`Generating ${reportTitles[reportType.value]} report`);
};

const viewReport = (report) => {
  // In a real app, this would show the report details
  alert(`Viewing ${report.name}`);
};

const downloadReport = (report) => {
  // In a real app, this would download the report
  alert(`Downloading ${report.name}`);
};

const editSchedule = (schedule) => {
  // In a real app, this would open the schedule editor
  alert(`Editing schedule for ${schedule.name}`);
};

const deleteSchedule = (schedule) => {
  if (confirm(`Are you sure you want to delete the schedule for ${schedule.name}?`)) {
    const index = scheduledReports.value.findIndex(s => s.id === schedule.id);
    if (index !== -1) {
      scheduledReports.value.splice(index, 1);
    }
  }
};

const addSchedule = () => {
  // In a real app, this would open the schedule creator
  alert('Adding new report schedule');
};

const initCharts = () => {
  // Destroy existing charts if they exist
  if (mainChartInstance.value) {
    mainChartInstance.value.destroy();
  }
  
  if (trendChartInstance.value) {
    trendChartInstance.value.destroy();
  }
  
  // Initialize Main Chart based on report type
  const mainCtx = mainChart.value.getContext('2d');
  
  switch (reportType.value) {
    case 'attendance':
      mainChartInstance.value = new Chart(mainCtx, {
        type: 'bar',
        data: {
          labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
          datasets: [{
            label: 'Attendance Rate',
            data: [95, 92, 90, 93, 91],
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
              beginAtZero: false,
              min: 80,
              max: 100,
              ticks: {
                callback: function(value) {
                  return value + '%';
                }
              }
            }
          }
        }
      });
      break;
      
    case 'finance':
      mainChartInstance.value = new Chart(mainCtx, {
        type: 'line',
        data: {
          labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
          datasets: [{
            label: 'Revenue',
            data: [42000, 43000, 41000, 44000, 45000, 46000, 44500, 45500, 45000],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 3,
            pointBackgroundColor: '#3b82f6',
            pointRadius: 5,
            pointHoverRadius: 7,
            fill: true,
            tension: 0.3
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: false,
              ticks: {
                callback: function(value) {
                  return '$' + value.toLocaleString();
                }
              }
            }
          }
        }
      });
      break;
      
    case 'academic':
      mainChartInstance.value = new Chart(mainCtx, {
        type: 'doughnut',
        data: {
          labels: ['A', 'B', 'C', 'D', 'F'],
          datasets: [{
            data: [15, 35, 30, 15, 5],
            backgroundColor: [
              '#10b981',
              '#3b82f6',
              '#f59e0b',
              '#f97316',
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
      break;
      
    case 'user':
      mainChartInstance.value = new Chart(mainCtx, {
        type: 'bar',
        data: {
          labels: ['Students', 'Teachers', 'Parents', 'Admins'],
          datasets: [{
            label: 'Active Users',
            data: [1200, 100, 950, 25],
            backgroundColor: [
              '#3b82f6',
              '#10b981',
              '#f59e0b',
              '#8b5cf6'
            ],
            borderWidth: 0
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false
        }
      });
      break;
  }
  
  // Initialize Trend Chart
  const trendCtx = trendChart.value.getContext('2d');
  trendChartInstance.value = new Chart(trendCtx, {
    type: 'line',
    data: {
      labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
      datasets: [{
        label: 'Current Trend',
        data: [85, 88, 92, 90],
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        borderWidth: 3,
        pointBackgroundColor: '#3b82f6',
        pointRadius: 5,
        pointHoverRadius: 7,
        fill: true,
        tension: 0.3
      }, {
        label: 'Previous Period',
        data: [80, 85, 88, 87],
        borderColor: '#9ca3af',
        borderWidth: 2,
        pointBackgroundColor: '#9ca3af',
        pointRadius: 3,
        pointHoverRadius: 5,
        borderDash: [5, 5],
        fill: false,
        tension: 0.3
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: false,
          min: 70,
          max: 100,
          ticks: {
            callback: function(value) {
              return value + '%';
            }
          }
        }
      }
    }
  });
};

// Watch for report type changes and reinitialize charts
const updateCharts = () => {
  nextTick(() => {
    initCharts();
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
.admin-reports {
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

.reports-controls {
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

.reports-overview {
  display: grid;
  grid-template-columns: 1fr;
  gap: 30px;
  margin-bottom: 30px;
}

.report-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
}

.report-card {
  display: flex;
  align-items: center;
  padding: 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.report-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 15px;
  font-size: 1.5rem;
  background: #dbeafe;
  color: #3b82f6;
}

.report-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
}

.report-label {
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

.reports-sections {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 30px;
}

.section h2 {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 20px;
  color: #1f2937;
}

.reports-table {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
}

.table-header {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
  background: #f9fafb;
  font-weight: 600;
  padding: 15px 20px;
}

.table-row {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
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

.status-badge.failed {
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

.scheduled-reports {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.scheduled-report {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.report-info h4 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #1f2937;
}

.report-info p {
  color: #6b7280;
}

.report-actions {
  display: flex;
  gap: 10px;
}

.report-actions button {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 15px;
}

.add-schedule {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 15px;
  width: 100%;
}

/* Dark mode support */
.dark .admin-reports {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .chart-card h3,
.report-value,
.report-info h4 {
  color: #f9fafb;
}

.dark .page-header p,
.report-label,
.report-info p {
  color: #d1d5db;
}

.dark .reports-controls,
.dark .report-card,
.dark .chart-card,
.dark .scheduled-report {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .reports-table {
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

/* Responsive adjustments */
@media (max-width: 768px) {
  .admin-reports {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .reports-controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .filter-controls {
    flex-direction: column;
  }
  
  .filter-controls select {
    width: 100%;
  }
  
  .reports-sections {
    grid-template-columns: 1fr;
  }
  
  .charts-section {
    grid-template-columns: 1fr;
  }
  
  .table-row {
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
    font-size: 0.9rem;
  }
  
  .scheduled-report {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .report-actions {
    width: 100%;
    justify-content: flex-end;
  }
}
</style>