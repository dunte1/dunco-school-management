<template>
  <AppLayout>
    <div class="parent-dashboard">
      <div class="dashboard-header">
        <h1>Welcome, {{ userName }}!</h1>
        <p>Parent Dashboard</p>
      </div>
      
      <div class="dashboard-content">
        <!-- Quick Stats -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon blue">
              <i class="fas fa-child"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ childrenCount }}</div>
              <div class="stat-label">Children</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon green">
              <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ averageGrade }}%</div>
              <div class="stat-label">Avg. Grade</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon purple">
              <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ attendanceRate }}%</div>
              <div class="stat-label">Attendance</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon red">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">${{ feesDue }}</div>
              <div class="stat-label">Fees Due</div>
            </div>
          </div>
        </div>
        
        <!-- Children Overview -->
        <div class="children-section">
          <div class="section-header">
            <h2>Children Overview</h2>
            <router-link to="/children" class="view-all">View All Children</router-link>
          </div>
          
          <div class="children-list">
            <div 
              v-for="child in children" 
              :key="child.id"
              class="child-item"
            >
              <div class="child-avatar">
                <img :src="child.avatar" :alt="child.name">
              </div>
              <div class="child-info">
                <div class="child-name">{{ child.name }}</div>
                <div class="child-grade">{{ child.grade }}</div>
                <div class="child-details">
                  <span class="attendance"><i class="fas fa-calendar-check"></i> {{ child.attendance }}%</span>
                  <span class="average"><i class="fas fa-chart-line"></i> {{ child.averageGrade }}%</span>
                </div>
              </div>
              <div class="child-actions">
                <router-link :to="`/child/${child.id}`" class="btn-outline">View Details</router-link>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Recent Notifications -->
        <div class="notifications-section">
          <div class="section-header">
            <h2>Recent Notifications</h2>
            <router-link to="/notifications" class="view-all">View All Notifications</router-link>
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
        
        <!-- Upcoming Events -->
        <div class="events-section">
          <div class="section-header">
            <h2>Upcoming Events</h2>
            <router-link to="/calendar" class="view-all">View Calendar</router-link>
          </div>
          
          <div class="events-list">
            <div 
              v-for="event in upcomingEvents" 
              :key="event.id"
              class="event-item"
            >
              <div class="event-date">
                <div class="day">{{ formatDateDay(event.date) }}</div>
                <div class="month">{{ formatDateMonth(event.date) }}</div>
              </div>
              <div class="event-info">
                <div class="event-name">{{ event.name }}</div>
                <div class="event-description">{{ event.description }}</div>
                <div class="event-time">{{ formatTime(event.time) }}</div>
              </div>
              <div class="event-actions">
                <button class="btn-outline">Add to Calendar</button>
              </div>
            </div>
            
            <div v-if="upcomingEvents.length === 0" class="no-events">
              No upcoming events
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
import { DashboardService, ParentService } from '../../Services';

const page = usePage();

// Get user data from page props
const userName = computed(() => page.props.auth?.user?.name || 'Parent');

// Reactive data
const childrenCount = ref(0);
const averageGrade = ref(0);
const attendanceRate = ref(0);
const feesDue = ref(0);
const children = ref([]);
const recentNotifications = ref([]);
const upcomingEvents = ref([]);

// Fetch dashboard data
const fetchDashboardData = async () => {
  try {
    // Fetch dashboard data from API
    const dashboardData = await DashboardService.getDashboardData();
    
    // Update stats
    if (dashboardData.stats) {
      childrenCount.value = dashboardData.stats.children || 0;
      averageGrade.value = dashboardData.stats.averageGrade || 0;
      attendanceRate.value = dashboardData.stats.attendance || 0;
      feesDue.value = dashboardData.stats.feesDue || 0;
    }
    
    // Fetch additional data
    await Promise.all([
      fetchChildren(),
      fetchRecentNotifications(),
      fetchUpcomingEvents()
    ]);
  } catch (error) {
    console.error('Error fetching dashboard data:', error);
  }
};

// Fetch children data
const fetchChildren = async () => {
  try {
    const data = await ParentService.getChildren();
    children.value = data.children || [];
  } catch (error) {
    console.error('Error fetching children:', error);
  }
};

// Fetch recent notifications
const fetchRecentNotifications = async () => {
  try {
    const data = await ParentService.getNotifications();
    recentNotifications.value = data.notifications || [];
  } catch (error) {
    console.error('Error fetching notifications:', error);
  }
};

// Fetch upcoming events
const fetchUpcomingEvents = async () => {
  try {
    const data = await ParentService.getAnnouncements();
    upcomingEvents.value = data.announcements || [];
  } catch (error) {
    console.error('Error fetching events:', error);
  }
};

// Mark notification as read
const markAsRead = async (notificationId) => {
  try {
    await ParentService.markNotificationRead(notificationId);
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

const formatDateDay = (date) => {
  return new Date(date).getDate();
};

const formatDateMonth = (date) => {
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  return months[new Date(date).getMonth()];
};

// Fetch data when component mounts
onMounted(() => {
  fetchDashboardData();
});
</script>

<style scoped>
/* ... existing styles ... */
.parent-dashboard {
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

.stat-icon.red {
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
}

.stat-icon.orange {
  background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
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

.children-section, .notifications-section, .events-section {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  padding: 25px;
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

.children-list {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.child-item {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 15px;
  border-radius: 8px;
  border: 1px solid #f3f4f6;
  transition: all 0.2s ease;
}

.child-item:hover {
  border-color: #667eea;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.child-avatar img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
}

.child-info {
  flex: 1;
}

.child-name {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 5px;
}

.child-grade {
  font-size: 0.9rem;
  color: #6b7280;
  margin-bottom: 10px;
}

.child-details {
  display: flex;
  gap: 15px;
  font-size: 0.85rem;
  color: #6b7280;
}

.child-details i {
  margin-right: 5px;
}

.child-actions .btn-outline {
  padding: 8px 16px;
  border: 1px solid #667eea;
  color: #667eea;
  background: transparent;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s ease;
  text-decoration: none;
}

.child-actions .btn-outline:hover {
  background: #667eea;
  color: white;
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

.events-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.event-item {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 15px;
  border-radius: 8px;
  border: 1px solid #f3f4f6;
  transition: all 0.2s ease;
}

.event-item:hover {
  border-color: #667eea;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.event-date {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 60px;
}

.event-date .day {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
}

.event-date .month {
  font-size: 0.9rem;
  color: #6b7280;
  text-transform: uppercase;
}

.event-info {
  flex: 1;
}

.event-name {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 5px;
}

.event-description {
  font-size: 0.9rem;
  color: #6b7280;
  margin-bottom: 5px;
}

.event-time {
  font-size: 0.85rem;
  color: #9ca3af;
}

.event-actions .btn-outline {
  padding: 8px 16px;
  border: 1px solid #667eea;
  color: #667eea;
  background: transparent;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s ease;
}

.event-actions .btn-outline:hover {
  background: #667eea;
  color: white;
}

.no-notifications, .no-events {
  text-align: center;
  padding: 40px;
  color: #6b7280;
  font-style: italic;
}

@media (max-width: 768px) {
  .parent-dashboard {
    padding: 15px;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .children-list {
    grid-template-columns: 1fr;
  }
  
  .child-item, .notification-item, .event-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
  
  .child-actions, .notification-actions, .event-actions {
    align-self: flex-end;
  }
}
</style>