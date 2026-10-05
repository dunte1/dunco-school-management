<template>
  <AppLayout>
    <div class="teacher-dashboard">
      <div class="page-header">
        <h1>Teacher Dashboard</h1>
        <p>Welcome back! Here's an overview of your classes and students</p>
      </div>
      
      <div class="dashboard-overview">
        <div class="stats-cards">
          <div class="stat-card">
            <div class="stat-icon classes">
              <i class="fas fa-chalkboard"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ classes.length }}</div>
              <div class="stat-label">Classes</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon students">
              <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ totalStudents }}</div>
              <div class="stat-label">Students</div>
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
            <div class="stat-icon exams">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ upcomingExams.length }}</div>
              <div class="stat-label">Upcoming Exams</div>
            </div>
          </div>
        </div>
        
        <div class="schedule-section">
          <h2>Today's Schedule</h2>
          <div class="classes-list">
            <div 
              v-for="classItem in todayClasses" 
              :key="classItem.id"
              class="class-item"
            >
              <div class="class-time">
                <span class="start-time">{{ classItem.startTime }}</span>
                <span class="end-time">{{ classItem.endTime }}</span>
              </div>
              <div class="class-details">
                <h3>{{ classItem.subject }}</h3>
                <p>{{ classItem.className }} - {{ classItem.room }}</p>
              </div>
              <div class="class-actions">
                <button class="btn-primary" @click="takeAttendance(classItem)">
                  <i class="fas fa-clipboard-list"></i>
                  Take Attendance
                </button>
              </div>
            </div>
            
            <div v-if="todayClasses.length === 0" class="no-classes">
              <i class="fas fa-calendar-times"></i>
              <p>No classes scheduled for today</p>
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
            <div class="action-card" @click="goToAssignments">
              <i class="fas fa-tasks"></i>
              <span>Assignments</span>
            </div>
            <div class="action-card" @click="goToExams">
              <i class="fas fa-file-alt"></i>
              <span>Exams</span>
            </div>
            <div class="action-card" @click="goToAttendance">
              <i class="fas fa-clipboard-list"></i>
              <span>Attendance</span>
            </div>
            <div class="action-card" @click="goToAnalytics">
              <i class="fas fa-chart-bar"></i>
              <span>Analytics</span>
            </div>
            <div class="action-card" @click="goToMessages">
              <i class="fas fa-comments"></i>
              <span>Messages</span>
            </div>
            <div class="action-card" @click="goToProfile">
              <i class="fas fa-user"></i>
              <span>Profile</span>
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

// Mock data - in a real app, this would come from API
const classes = ref([
  { id: 1, name: 'Mathematics 101', students: 25 },
  { id: 2, name: 'Physics 201', students: 22 },
  { id: 3, name: 'Chemistry 101', students: 24 }
]);

const todayClasses = ref([
  {
    id: 1,
    subject: 'Mathematics 101',
    className: 'Grade 10A',
    room: 'Room 101',
    startTime: '09:00',
    endTime: '10:00'
  },
  {
    id: 2,
    subject: 'Physics 201',
    className: 'Grade 11B',
    room: 'Lab 2',
    startTime: '10:15',
    endTime: '11:15'
  },
  {
    id: 3,
    subject: 'Chemistry 101',
    className: 'Grade 9C',
    room: 'Lab 1',
    startTime: '14:00',
    endTime: '15:00'
  }
]);

const upcomingExams = ref([
  { id: 1, subject: 'Mathematics', date: '2025-09-20', className: 'Grade 10A' },
  { id: 2, subject: 'Physics', date: '2025-09-25', className: 'Grade 11B' }
]);

const recentActivities = ref([
  {
    id: 1,
    type: 'assignment',
    icon: 'fas fa-tasks',
    title: 'New Assignment Posted',
    description: 'Posted "Algebra Basics" assignment for Grade 10A',
    time: '2025-09-15T10:30:00'
  },
  {
    id: 2,
    type: 'exam',
    icon: 'fas fa-file-alt',
    title: 'Exam Results Published',
    description: 'Published results for Physics Midterm Exam',
    time: '2025-09-14T14:15:00'
  },
  {
    id: 3,
    type: 'attendance',
    icon: 'fas fa-clipboard-list',
    title: 'Attendance Recorded',
    description: 'Recorded attendance for Chemistry 101',
    time: '2025-09-14T09:45:00'
  }
]);

// Computed properties
const totalStudents = computed(() => {
  return classes.value.reduce((total, classItem) => total + classItem.students, 0);
});

const pendingAssignments = computed(() => {
  // In a real app, this would come from API
  return 3;
});

// Methods
const takeAttendance = (classItem) => {
  alert(`Taking attendance for ${classItem.subject} - ${classItem.className}`);
};

const goToAssignments = () => {
  router.visit('/teacher/assignments');
};

const goToExams = () => {
  router.visit('/teacher/exams');
};

const goToAttendance = () => {
  router.visit('/teacher/attendance');
};

const goToAnalytics = () => {
  router.visit('/teacher/analytics');
};

const goToMessages = () => {
  router.visit('/messages');
};

const goToProfile = () => {
  router.visit('/profile');
};

const formatTime = (timeString) => {
  const date = new Date(timeString);
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

// Lifecycle
onMounted(() => {
  // In a real app, we would fetch data from the API here
});
</script>

<style scoped>
.teacher-dashboard {
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

.stat-icon.classes {
  background: #dbeafe;
  color: #3b82f6;
}

.stat-icon.students {
  background: #dcfce7;
  color: #22c55e;
}

.stat-icon.assignments {
  background: #ffedd5;
  color: #f97316;
}

.stat-icon.exams {
  background: #fee2e2;
  color: #ef4444;
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

.schedule-section h2 {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 20px;
  color: #1f2937;
}

.classes-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.class-item {
  display: flex;
  align-items: center;
  padding: 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.class-time {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-right: 20px;
  min-width: 80px;
}

.start-time {
  font-weight: 700;
  font-size: 1.1rem;
  color: #1f2937;
}

.end-time {
  font-size: 0.9rem;
  color: #6b7280;
}

.class-details {
  flex: 1;
}

.class-details h3 {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #1f2937;
}

.class-details p {
  color: #6b7280;
}

.class-actions button {
  padding: 10px 15px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.no-classes {
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.no-classes i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
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

.activity-icon.assignment {
  background: #ffedd5;
  color: #f97316;
}

.activity-icon.exam {
  background: #fee2e2;
  color: #ef4444;
}

.activity-icon.attendance {
  background: #dbeafe;
  color: #3b82f6;
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
.dark .teacher-dashboard {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .stat-value,
.dark .class-details h3,
.dark .activity-details h4,
.dark .action-card span {
  color: #f9fafb;
}

.dark .page-header p,
.dark .stat-label,
.dark .end-time,
.dark .class-details p,
.dark .activity-details p,
.activity-time {
  color: #d1d5db;
}

.dark .stat-card,
.dark .class-item,
.dark .no-classes,
.dark .activity-item,
.dark .action-card {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .no-classes {
  color: #9ca3af;
}

.dark .no-classes i {
  color: #4b5563;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .teacher-dashboard {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .dashboard-sections {
    grid-template-columns: 1fr;
  }
  
  .class-item {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .class-time {
    flex-direction: row;
    align-items: center;
    gap: 10px;
    margin-right: 0;
    margin-bottom: 15px;
    min-width: auto;
  }
  
  .class-actions {
    width: 100%;
    margin-top: 15px;
  }
  
  .class-actions button {
    width: 100%;
    justify-content: center;
  }
  
  .quick-actions-grid {
    grid-template-columns: 1fr;
  }
}
</style>