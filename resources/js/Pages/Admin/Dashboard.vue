<template>
  <AppLayout>
    <div class="admin-dashboard">
      <div class="page-header">
        <h1>Admin Dashboard</h1>
        <p>Welcome back! Here's an overview of your school system</p>
      </div>
      
      <div class="dashboard-overview">
        <div class="stats-cards">
          <div class="stat-card">
            <div class="stat-icon schools">
              <i class="fas fa-school"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ schools.length }}</div>
              <div class="stat-label">Schools</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon users">
              <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ totalUsers }}</div>
              <div class="stat-label">Total Users</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon students">
              <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ totalStudents }}</div>
              <div class="stat-label">Students</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon teachers">
              <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ totalTeachers }}</div>
              <div class="stat-label">Teachers</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon finance">
              <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">${{ totalRevenue.toLocaleString() }}</div>
              <div class="stat-label">Monthly Revenue</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon attendance">
              <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ avgAttendance }}%</div>
              <div class="stat-label">Avg. Attendance</div>
            </div>
          </div>
        </div>
        
        <div class="charts-section">
          <div class="chart-card">
            <h3>User Distribution</h3>
            <div class="chart-container">
              <canvas ref="userChart"></canvas>
            </div>
          </div>
          
          <div class="chart-card">
            <h3>Revenue Trend</h3>
            <div class="chart-container">
              <canvas ref="revenueChart"></canvas>
            </div>
          </div>
        </div>
      </div>
      
      <div class="dashboard-sections">
        <div class="section">
          <h2>Recent Activities</h2>
          <div class="activities-list">
            <div 
              v-for="activity in recentActivities" 
              :key="activity.id"
              class="activity-item"
            >
              <div class="activity-icon" :class="activity.type">
                <i :class="activity.icon"></i>
              </div>
              <div class="activity-details">
                <h4>{{ activity.title }}</h4>
                <p>{{ activity.description }}</p>
                <span class="activity-time">{{ formatTime(activity.time) }}</span>
              </div>
            </div>
          </div>
        </div>
        
        <div class="section">
          <h2>Quick Actions</h2>
          <div class="quick-actions-grid">
            <div class="action-card" @click="goToSchools">
              <i class="fas fa-school"></i>
              <span>Schools</span>
            </div>
            <div class="action-card" @click="goToUsers">
              <i class="fas fa-users"></i>
              <span>Users</span>
            </div>
            <div class="action-card" @click="goToFinance">
              <i class="fas fa-money-bill-wave"></i>
              <span>Finance</span>
            </div>
            <div class="action-card" @click="goToExams">
              <i class="fas fa-file-alt"></i>
              <span>Exams</span>
            </div>
            <div class="action-card" @click="goToReports">
              <i class="fas fa-chart-bar"></i>
              <span>Reports</span>
            </div>
            <div class="action-card" @click="goToSettings">
              <i class="fas fa-cog"></i>
              <span>Settings</span>
            </div>
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
const userChart = ref(null);
const revenueChart = ref(null);
const userChartInstance = ref(null);
const revenueChartInstance = ref(null);

// Mock data - in a real app, this would come from API
const schools = ref([
  { id: 1, name: 'Greenwood High School', students: 1200, teachers: 45 },
  { id: 2, name: 'Riverside Elementary', students: 800, teachers: 30 },
  { id: 3, name: 'Mountainview Academy', students: 650, teachers: 25 }
]);

const recentActivities = ref([
  {
    id: 1,
    type: 'user',
    icon: 'fas fa-user-plus',
    title: 'New Student Registered',
    description: 'John Smith registered at Greenwood High School',
    time: '2025-09-15T10:30:00'
  },
  {
    id: 2,
    type: 'finance',
    icon: 'fas fa-money-bill-wave',
    title: 'Payment Received',
    description: '$1,500 payment received from Parent ID 12345',
    time: '2025-09-14T14:15:00'
  },
  {
    id: 3,
    type: 'exam',
    icon: 'fas fa-file-alt',
    title: 'Exam Scheduled',
    description: 'Mathematics midterm scheduled for Grade 10',
    time: '2025-09-14T09:45:00'
  }
]);

// Computed properties
const totalUsers = computed(() => {
  return schools.value.reduce((total, school) => total + school.students + school.teachers, 0);
});

const totalStudents = computed(() => {
  return schools.value.reduce((total, school) => total + school.students, 0);
});

const totalTeachers = computed(() => {
  return schools.value.reduce((total, school) => total + school.teachers, 0);
});

const totalRevenue = computed(() => {
  // In a real app, this would come from API
  return 45000;
});

const avgAttendance = computed(() => {
  // In a real app, this would come from API
  return 92;
});

// Methods
const formatTime = (timeString) => {
  const date = new Date(timeString);
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const goToSchools = () => {
  router.visit('/admin/schools');
};

const goToUsers = () => {
  router.visit('/admin/users');
};

const goToFinance = () => {
  router.visit('/admin/finance');
};

const goToExams = () => {
  router.visit('/admin/exams');
};

const goToReports = () => {
  router.visit('/admin/reports');
};

const goToSettings = () => {
  router.visit('/admin/settings');
};

const initCharts = () => {
  // Destroy existing charts if they exist
  if (userChartInstance.value) {
    userChartInstance.value.destroy();
  }
  
  if (revenueChartInstance.value) {
    revenueChartInstance.value.destroy();
  }
  
  // Initialize User Distribution Chart
  const userCtx = userChart.value.getContext('2d');
  userChartInstance.value = new Chart(userCtx, {
    type: 'doughnut',
    data: {
      labels: ['Students', 'Teachers', 'Parents', 'Admins'],
      datasets: [{
        data: [totalStudents.value, totalTeachers.value, 1200, 25],
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
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom'
        }
      }
    }
  });
  
  // Initialize Revenue Trend Chart
  const revenueCtx = revenueChart.value.getContext('2d');
  revenueChartInstance.value = new Chart(revenueCtx, {
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
      },
      plugins: {
        legend: {
          display: false
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
.admin-dashboard {
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

.dashboard-overview {
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

.stat-icon.schools {
  background: #dbeafe;
  color: #3b82f6;
}

.stat-icon.users {
  background: #dcfce7;
  color: #22c55e;
}

.stat-icon.students {
  background: #ffedd5;
  color: #f97316;
}

.stat-icon.teachers {
  background: #fce7f3;
  color: #ec4899;
}

.stat-icon.finance {
  background: #f0f9ff;
  color: #0ea5e9;
}

.stat-icon.attendance {
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

.dashboard-sections {
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

.activities-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.activity-item {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  padding: 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.activity-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
}

.activity-icon.user {
  background: #dbeafe;
  color: #3b82f6;
}

.activity-icon.finance {
  background: #f0f9ff;
  color: #0ea5e9;
}

.activity-icon.exam {
  background: #f0fdf4;
  color: #16a34a;
}

.activity-details {
  flex: 1;
}

.activity-details h4 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #1f2937;
}

.activity-details p {
  color: #6b7280;
  margin-bottom: 10px;
}

.activity-time {
  font-size: 0.85rem;
  color: #9ca3af;
}

.quick-actions-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
}

.action-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  cursor: pointer;
  transition: all 0.2s ease;
}

.action-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
}

.action-card i {
  font-size: 1.5rem;
  margin-bottom: 10px;
  color: #3b82f6;
}

.action-card span {
  font-weight: 500;
  color: #1f2937;
}

/* Dark mode support */
.dark .admin-dashboard {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .stat-value,
.dark .chart-card h3,
.dark .section h2,
.dark .activity-details h4,
.dark .action-card span {
  color: #f9fafb;
}

.dark .page-header p,
.dark .stat-label,
.dark .activity-details p,
.activity-time {
  color: #d1d5db;
}

.dark .stat-card,
.dark .chart-card,
.dark .activity-item,
.dark .action-card {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .admin-dashboard {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .dashboard-sections {
    grid-template-columns: 1fr;
  }
  
  .charts-section {
    grid-template-columns: 1fr;
  }
  
  .quick-actions-grid {
    grid-template-columns: 1fr;
  }
}
</style>