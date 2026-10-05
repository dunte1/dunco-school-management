<template>
  <AppLayout>
    <div class="parent-dashboard">
      <div class="page-header">
        <h1>Parent Dashboard</h1>
        <p>Welcome! Here's an overview of your child's progress</p>
      </div>
      
      <div class="dashboard-overview">
        <div class="child-selector">
          <h3>Select Child:</h3>
          <select v-model="selectedChild" @change="loadChildData">
            <option v-for="child in children" :key="child.id" :value="child.id">
              {{ child.name }}
            </option>
          </select>
        </div>
        
        <div class="stats-cards">
          <div class="stat-card">
            <div class="stat-icon attendance">
              <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ childAttendanceRate }}%</div>
              <div class="stat-label">Attendance Rate</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon grades">
              <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ childAverageGrade }}</div>
              <div class="stat-label">Average Grade</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon assignments">
              <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ pendingAssignments }}</div>
              <div class="stat-label">Pending Assignments</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon behavior">
              <i class="fas fa-heart"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ behaviorScore }}/10</div>
              <div class="stat-label">Behavior Score</div>
            </div>
          </div>
        </div>
        
        <div class="child-info-section">
          <div class="child-profile">
            <div class="avatar">
              <i class="fas fa-user"></i>
            </div>
            <div class="child-details">
              <h2>{{ currentChild.name }}</h2>
              <p>Grade {{ currentChild.grade }} | {{ currentChild.age }} years old</p>
              <p>Class: {{ currentChild.className }}</p>
            </div>
          </div>
          
          <div class="quick-actions">
            <button class="btn-primary" @click="goToAttendance">
              <i class="fas fa-clipboard-list"></i>
              View Attendance
            </button>
            <button class="btn-primary" @click="goToAcademicReports">
              <i class="fas fa-chart-bar"></i>
              Academic Reports
            </button>
            <button class="btn-primary" @click="goToFinance">
              <i class="fas fa-money-bill-wave"></i>
              Fee Management
            </button>
            <button class="btn-primary" @click="sendMessage">
              <i class="fas fa-envelope"></i>
              Message Teacher
            </button>
          </div>
        </div>
      </div>
      
      <div class="dashboard-sections">
        <div class="section">
          <h2>Recent Notifications</h2>
          <div class="notifications-list">
            <div 
              v-for="notification in recentNotifications" 
              :key="notification.id"
              class="notification-item"
            >
              <div class="notification-icon" :class="notification.type">
                <i :class="notification.icon"></i>
              </div>
              <div class="notification-details">
                <h4>{{ notification.title }}</h4>
                <p>{{ notification.message }}</p>
                <span class="notification-time">{{ formatTime(notification.time) }}</span>
              </div>
            </div>
            
            <div v-if="recentNotifications.length === 0" class="no-notifications">
              <i class="fas fa-bell-slash"></i>
              <p>No recent notifications</p>
            </div>
          </div>
        </div>
        
        <div class="section">
          <h2>Upcoming Events</h2>
          <div class="events-list">
            <div 
              v-for="event in upcomingEvents" 
              :key="event.id"
              class="event-item"
            >
              <div class="event-date">
                <div class="event-day">{{ formatDate(event.date, 'day') }}</div>
                <div class="event-month">{{ formatDate(event.date, 'month') }}</div>
              </div>
              <div class="event-details">
                <h4>{{ event.title }}</h4>
                <p>{{ event.description }}</p>
                <span class="event-time">{{ event.time }}</span>
              </div>
            </div>
            
            <div v-if="upcomingEvents.length === 0" class="no-events">
              <i class="fas fa-calendar-alt"></i>
              <p>No upcoming events</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Reactive data
const selectedChild = ref(1);
const children = ref([
  { id: 1, name: 'John Doe', age: 15, grade: '10', className: 'Grade 10A' },
  { id: 2, name: 'Jane Doe', age: 13, grade: '8', className: 'Grade 8B' }
]);

const currentChild = computed(() => {
  return children.value.find(child => child.id === selectedChild.value) || children.value[0];
});

// Mock data - in a real app, this would come from API
const childData = ref({
  attendanceRate: 92,
  averageGrade: 'B+',
  pendingAssignments: 3,
  behaviorScore: 8,
  notifications: [
    {
      id: 1,
      type: 'assignment',
      icon: 'fas fa-tasks',
      title: 'New Assignment Posted',
      message: 'Mathematics assignment "Algebra Basics" has been posted',
      time: '2025-09-15T10:30:00'
    },
    {
      id: 2,
      type: 'attendance',
      icon: 'fas fa-clipboard-list',
      title: 'Attendance Alert',
      message: 'John was marked absent today',
      time: '2025-09-14T14:15:00'
    },
    {
      id: 3,
      type: 'announcement',
      icon: 'fas fa-bullhorn',
      title: 'School Event',
      message: 'Parent-teacher meeting scheduled for next week',
      time: '2025-09-14T09:45:00'
    }
  ],
  events: [
    {
      id: 1,
      title: 'Parent-Teacher Meeting',
      description: 'Quarterly parent-teacher meeting',
      date: '2025-09-20',
      time: '14:00 - 16:00'
    },
    {
      id: 2,
      title: 'Science Fair',
      description: 'Annual school science fair',
      date: '2025-09-25',
      time: '09:00 - 17:00'
    }
  ]
});

// Computed properties
const childAttendanceRate = computed(() => childData.value.attendanceRate);
const childAverageGrade = computed(() => childData.value.averageGrade);
const pendingAssignments = computed(() => childData.value.pendingAssignments);
const behaviorScore = computed(() => childData.value.behaviorScore);
const recentNotifications = computed(() => childData.value.notifications);
const upcomingEvents = computed(() => childData.value.events);

// Methods
const loadChildData = () => {
  // In a real app, this would fetch data from the API for the selected child
  console.log(`Loading data for child ID: ${selectedChild.value}`);
};

const goToAttendance = () => {
  router.visit(`/parent/attendance?child=${selectedChild.value}`);
};

const goToAcademicReports = () => {
  router.visit(`/parent/reports?child=${selectedChild.value}`);
};

const goToFinance = () => {
  router.visit(`/parent/finance?child=${selectedChild.value}`);
};

const sendMessage = () => {
  router.visit('/messages');
};

const formatTime = (timeString) => {
  const date = new Date(timeString);
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

const formatDate = (dateString, format) => {
  const date = new Date(dateString);
  
  if (format === 'day') {
    return date.getDate();
  } else if (format === 'month') {
    return date.toLocaleDateString('en-US', { month: 'short' });
  }
  
  return date.toLocaleDateString();
};

// Lifecycle
onMounted(() => {
  // In a real app, we would fetch data from the API here
});
</script>

<style scoped>
.parent-dashboard {
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

.child-selector {
  display: flex;
  align-items: center;
  gap: 15px;
  background: white;
  padding: 15px 20px;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.child-selector h3 {
  margin: 0;
  color: #1f2937;
}

.child-selector select {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 1rem;
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

.stat-icon.attendance {
  background: #dbeafe;
  color: #3b82f6;
}

.stat-icon.grades {
  background: #dcfce7;
  color: #22c55e;
}

.stat-icon.assignments {
  background: #ffedd5;
  color: #f97316;
}

.stat-icon.behavior {
  background: #fbcfe8;
  color: #ec4899;
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

.child-info-section {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 20px;
  background: white;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.child-profile {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 15px;
  font-size: 2.5rem;
  color: #9ca3af;
}

.child-details h2 {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 10px;
  color: #1f2937;
}

.child-details p {
  color: #6b7280;
  margin-bottom: 5px;
}

.quick-actions {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 15px;
}

.quick-actions button {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 15px;
  gap: 8px;
  height: 100%;
}

.quick-actions button i {
  font-size: 1.2rem;
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

.notifications-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.notification-item {
  display: flex;
  align-items: flex-start;
  gap: 15px;
  padding: 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.notification-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
}

.notification-icon.assignment {
  background: #ffedd5;
  color: #f97316;
}

.notification-icon.attendance {
  background: #dbeafe;
  color: #3b82f6;
}

.notification-icon.announcement {
  background: #e0e7ff;
  color: #6366f1;
}

.notification-details {
  flex: 1;
}

.notification-details h4 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #1f2937;
}

.notification-details p {
  color: #6b7280;
  margin-bottom: 10px;
}

.notification-time {
  font-size: 0.85rem;
  color: #9ca3af;
}

.no-notifications {
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.no-notifications i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
}

.events-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.event-item {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.event-date {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-width: 60px;
}

.event-day {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
}

.event-month {
  font-size: 0.9rem;
  color: #6b7280;
}

.event-details {
  flex: 1;
}

.event-details h4 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #1f2937;
}

.event-details p {
  color: #6b7280;
  margin-bottom: 10px;
}

.event-time {
  font-size: 0.85rem;
  color: #9ca3af;
}

.no-events {
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.no-events i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
}

/* Dark mode support */
.dark .parent-dashboard {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .stat-value,
.dark .child-details h2,
.dark .notification-details h4,
.dark .event-details h4 {
  color: #f9fafb;
}

.dark .page-header p,
.dark .stat-label,
.dark .child-details p,
.dark .notification-details p,
.dark .event-details p,
.notification-time,
.event-time {
  color: #d1d5db;
}

.dark .stat-card,
.dark .child-info-section,
.dark .notification-item,
.dark .event-item {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .child-selector {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .child-selector select {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
}

.dark .avatar {
  background: #374151;
  color: #9ca3af;
}

.dark .no-notifications,
.dark .no-events {
  background: #1f2937;
  color: #9ca3af;
}

.dark .no-notifications i,
.dark .no-events i {
  color: #4b5563;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .parent-dashboard {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .dashboard-sections {
    grid-template-columns: 1fr;
  }
  
  .child-info-section {
    grid-template-columns: 1fr;
    gap: 20px;
  }
  
  .quick-actions {
    grid-template-columns: 1fr 1fr;
  }
  
  .notification-item,
  .event-item {
    padding: 15px;
  }
}
</style>