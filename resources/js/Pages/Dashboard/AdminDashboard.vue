<template>
  <AppLayout>
    <div class="admin-dashboard">
      <div class="dashboard-header">
        <h1>Welcome, {{ userName }}!</h1>
        <p>Admin Dashboard</p>
      </div>
      
      <div class="dashboard-content">
        <!-- Quick Stats -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon blue">
              <i class="fas fa-school"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ schoolsCount }}</div>
              <div class="stat-label">Schools</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon green">
              <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ usersCount }}</div>
              <div class="stat-label">Users</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon purple">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ reportsCount }}</div>
              <div class="stat-label">Reports</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon orange">
              <i class="fas fa-bell"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ notificationsCount }}</div>
              <div class="stat-label">Notifications</div>
            </div>
          </div>
        </div>
        
        <!-- System Overview -->
        <div class="system-section">
          <div class="section-header">
            <h2>System Overview</h2>
            <router-link to="/admin/system" class="view-all">View System Details</router-link>
          </div>
          
          <div class="system-grid">
            <div class="system-card">
              <div class="card-header">
                <h3>Performance</h3>
                <i class="fas fa-tachometer-alt"></i>
              </div>
              <div class="card-content">
                <div class="performance-metric">
                  <span class="label">CPU Usage</span>
                  <span class="value">{{ cpuUsage }}%</span>
                </div>
                <div class="performance-bar">
                  <div class="bar-fill" :style="{ width: cpuUsage + '%' }"></div>
                </div>
                
                <div class="performance-metric">
                  <span class="label">Memory Usage</span>
                  <span class="value">{{ memoryUsage }}%</span>
                </div>
                <div class="performance-bar">
                  <div class="bar-fill" :style="{ width: memoryUsage + '%' }"></div>
                </div>
                
                <div class="performance-metric">
                  <span class="label">Disk Usage</span>
                  <span class="value">{{ diskUsage }}%</span>
                </div>
                <div class="performance-bar">
                  <div class="bar-fill" :style="{ width: diskUsage + '%' }"></div>
                </div>
              </div>
            </div>
            
            <div class="system-card">
              <div class="card-header">
                <h3>Recent Activity</h3>
                <i class="fas fa-history"></i>
              </div>
              <div class="card-content">
                <div 
                  v-for="activity in recentActivity" 
                  :key="activity.id"
                  class="activity-item"
                >
                  <div class="activity-icon" :class="activity.type">
                    <i :class="activity.icon"></i>
                  </div>
                  <div class="activity-content">
                    <div class="activity-title">{{ activity.title }}</div>
                    <div class="activity-description">{{ activity.description }}</div>
                    <div class="activity-time">{{ formatTime(activity.time) }}</div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="system-card">
              <div class="card-header">
                <h3>Quick Actions</h3>
                <i class="fas fa-bolt"></i>
              </div>
              <div class="card-content">
                <div class="quick-actions">
                  <button class="action-btn">
                    <i class="fas fa-user-plus"></i>
                    <span>Add User</span>
                  </button>
                  <button class="action-btn">
                    <i class="fas fa-school"></i>
                    <span>Add School</span>
                  </button>
                  <button class="action-btn">
                    <i class="fas fa-bell"></i>
                    <span>Send Notification</span>
                  </button>
                  <button class="action-btn">
                    <i class="fas fa-file-export"></i>
                    <span>Generate Report</span>
                  </button>
                  <button class="action-btn">
                    <i class="fas fa-cog"></i>
                    <span>System Settings</span>
                  </button>
                  <button class="action-btn">
                    <i class="fas fa-database"></i>
                    <span>Backup Database</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Recent Notifications -->
        <div class="notifications-section">
          <div class="section-header">
            <h2>Recent Notifications</h2>
            <router-link to="/admin/notifications" class="view-all">View All Notifications</router-link>
          </div>
          
          <div class="notifications-list">
            <div 
              v-for="notification in recentNotifications" 
              :key="notification.id"
              class="notification-item"
              :class="{ 'unread': !notification.read }"
            >
              <div class="notification-icon" :class="notification.type">
                <i :class="notification.icon"></i>
              </div>
              <div class="notification-content">
                <div class="notification-title">{{ notification.title }}</div>
                <div class="notification-message">{{ notification.message }}</div>
                <div class="notification-time">{{ formatTime(notification.time) }}</div>
              </div>
              <div class="notification-actions">
                <button 
                  v-if="!notification.read"
                  class="mark-read"
                  @click="markAsRead(notification.id)"
                >
                  <i class="fas fa-check"></i>
                </button>
              </div>
            </div>
            
            <div v-if="recentNotifications.length === 0" class="no-notifications">
              No recent notifications
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
// Import our services
import { DashboardService, AdminService } from '../../Services';

const page = usePage();

// Get user data from page props
const userName = computed(() => page.props.auth?.user?.name || 'Admin');

// Reactive data
const schoolsCount = ref(0);
const usersCount = ref(0);
const reportsCount = ref(0);
const notificationsCount = ref(0);
const cpuUsage = ref(0);
const memoryUsage = ref(0);
const diskUsage = ref(0);
const recentActivity = ref([]);
const recentNotifications = ref([]);

// Fetch dashboard data
const fetchDashboardData = async () => {
  try {
    // Fetch dashboard data from API
    const dashboardData = await DashboardService.getDashboardData();
    
    // Update stats
    if (dashboardData.stats) {
      schoolsCount.value = dashboardData.stats.schools || 0;
      usersCount.value = dashboardData.stats.users || 0;
      reportsCount.value = dashboardData.stats.reports || 0;
      notificationsCount.value = dashboardData.stats.notifications || 0;
      
      // System metrics
      cpuUsage.value = dashboardData.stats.cpuUsage || 0;
      memoryUsage.value = dashboardData.stats.memoryUsage || 0;
      diskUsage.value = dashboardData.stats.diskUsage || 0;
    }
    
    // Fetch additional data
    await Promise.all([
      fetchRecentActivity(),
      fetchRecentNotifications()
    ]);
  } catch (error) {
    console.error('Error fetching dashboard data:', error);
  }
};

// Fetch recent activity
const fetchRecentActivity = async () => {
  try {
    const data = await AdminService.getAnalytics();
    recentActivity.value = data.activity || [];
  } catch (error) {
    console.error('Error fetching recent activity:', error);
  }
};

// Fetch recent notifications
const fetchRecentNotifications = async () => {
  try {
    const data = await AdminService.getNotifications();
    recentNotifications.value = data.notifications || [];
  } catch (error) {
    console.error('Error fetching notifications:', error);
  }
};

// Mark notification as read
const markAsRead = async (notificationId) => {
  try {
    await AdminService.markNotificationRead(notificationId);
    // Update local state
    const notification = recentNotifications.value.find(n => n.id === notificationId);
    if (notification) {
      notification.read = true;
    }
  } catch (error) {
    console.error('Error marking notification as read:', error);
  }
};

// Helper functions (keeping existing ones)
const formatTime = (time) => {
  return new Date(time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

// Fetch data when component mounts
onMounted(() => {
  fetchDashboardData();
});
</script>

<style scoped>
/* ... existing styles ... */
.admin-dashboard {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.dashboard-header {
  margin-bottom: 30px;
}

.dashboard-header h1 {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 5px;
  color: #1f2937;
}

.dashboard-header p {
  font-size: 1.1rem;
  color: #6b7280;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  display: flex;
  align-items: center;
  gap: 15px;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  color: white;
}

.stat-icon.blue {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-icon.green {
  background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
}

.stat-icon.purple {
  background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);
}

.stat-icon.orange {
  background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
}

.stat-icon.red {
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.stat-info {
  display: flex;
  flex-direction: column;
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

.system-section {
  margin-bottom: 30px;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.section-header h2 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
}

.view-all {
  color: #667eea;
  text-decoration: none;
  font-weight: 500;
}

.view-all:hover {
  text-decoration: underline;
}

.system-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.system-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  overflow: hidden;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #f3f4f6;
  background: #f9fafb;
}

.card-header h3 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1f2937;
}

.card-header i {
  font-size: 1.25rem;
  color: #6b7280;
}

.card-content {
  padding: 20px;
}

.performance-metric {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.performance-metric .label {
  font-size: 0.9rem;
  color: #6b7280;
}

.performance-metric .value {
  font-weight: 600;
  color: #1f2937;
}

.performance-bar {
  height: 8px;
  background: #f3f4f6;
  border-radius: 4px;
  margin-bottom: 20px;
  overflow: hidden;
}

.bar-fill {
  height: 100%;
  background: linear-gradient(90deg, #667eea, #764ba2);
  border-radius: 4px;
  transition: width 0.3s ease;
}

.activity-item {
  display: flex;
  gap: 15px;
  padding: 15px 0;
  border-bottom: 1px solid #f3f4f6;
}

.activity-item:last-child {
  border-bottom: none;
}

.activity-icon {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1rem;
  flex-shrink: 0;
}

.activity-icon.info {
  background: #667eea;
}

.activity-icon.warning {
  background: #f59e0b;
}

.activity-icon.success {
  background: #22c55e;
}

.activity-icon.error {
  background: #ef4444;
}

.activity-content {
  flex: 1;
}

.activity-title {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 5px;
}

.activity-description {
  font-size: 0.9rem;
  color: #6b7280;
  margin-bottom: 5px;
}

.activity-time {
  font-size: 0.8rem;
  color: #9ca3af;
}

.quick-actions {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
}

.action-btn {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 20px;
  border: 1px solid #f3f4f6;
  border-radius: 8px;
  background: white;
  cursor: pointer;
  transition: all 0.2s ease;
}

.action-btn:hover {
  border-color: #667eea;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  transform: translateY(-2px);
}

.action-btn i {
  font-size: 1.5rem;
  color: #667eea;
}

.action-btn span {
  font-size: 0.9rem;
  color: #1f2937;
  font-weight: 500;
}

.notifications-section {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  padding: 25px;
}

.notifications-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.notification-item {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 15px;
  border-radius: 8px;
  border: 1px solid #f3f4f6;
  transition: all 0.2s ease;
}

.notification-item:hover {
  border-color: #667eea;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.notification-item.unread {
  background: rgba(102, 126, 234, 0.05);
  border-color: #667eea;
}

.notification-icon {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1rem;
}

.notification-icon.info {
  background: #667eea;
}

.notification-icon.warning {
  background: #f59e0b;
}

.notification-icon.success {
  background: #22c55e;
}

.notification-icon.error {
  background: #ef4444;
}

.notification-content {
  flex: 1;
}

.notification-title {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 5px;
}

.notification-message {
  font-size: 0.9rem;
  color: #6b7280;
  margin-bottom: 5px;
}

.notification-time {
  font-size: 0.8rem;
  color: #9ca3af;
}

.notification-actions .mark-read {
  background: #22c55e;
  color: white;
  border: none;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.notification-actions .mark-read:hover {
  background: #16a34a;
  transform: scale(1.1);
}

.no-notifications {
  text-align: center;
  padding: 40px;
  color: #6b7280;
  font-style: italic;
}

@media (max-width: 768px) {
  .admin-dashboard {
    padding: 15px;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .system-grid {
    grid-template-columns: 1fr;
  }
  
  .quick-actions {
    grid-template-columns: 1fr;
  }
  
  .notification-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
  
  .notification-actions {
    align-self: flex-end;
  }
}
</style>