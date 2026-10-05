<template>
  <AppLayout>
    <div class="student-dashboard">
      <div class="dashboard-header">
        <h1>Welcome, {{ userName }}!</h1>
        <p>Student Dashboard</p>
      </div>
      
      <div class="dashboard-content">
        <!-- Quick Stats -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon blue">
              <i class="fas fa-book"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ coursesCount }}</div>
              <div class="stat-label">Courses</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon green">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ assignmentsCount }}</div>
              <div class="stat-label">Assignments</div>
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
        
        <!-- Today's Schedule -->
        <div class="schedule-section">
          <div class="section-header">
            <h2>Today's Schedule</h2>
            <router-link to="/timetable" class="view-all">View Full Timetable</router-link>
          </div>
          
          <div class="schedule-list">
            <div 
              v-for="classItem in todaySchedule" 
              :key="classItem.id"
              class="schedule-item"
              :class="{ 'current': isCurrentClass(classItem) }"
            >
              <div class="time">
                <div class="start-time">{{ formatTime(classItem.startTime) }}</div>
                <div class="end-time">{{ formatTime(classItem.endTime) }}</div>
              </div>
              <div class="class-info">
                <div class="subject">{{ classItem.subject }}</div>
                <div class="details">
                  <span class="room"><i class="fas fa-door-open"></i> {{ classItem.room }}</span>
                  <span class="teacher"><i class="fas fa-chalkboard-teacher"></i> {{ classItem.teacher }}</span>
                </div>
              </div>
              <div class="status">
                <span v-if="isCurrentClass(classItem)" class="live">Live</span>
                <span v-else-if="isPastClass(classItem)" class="completed">Completed</span>
                <span v-else class="upcoming">Upcoming</span>
              </div>
            </div>
            
            <div v-if="todaySchedule.length === 0" class="no-schedule">
              No classes scheduled for today
            </div>
          </div>
        </div>
        
        <!-- Upcoming Exams -->
        <div class="exams-section">
          <div class="section-header">
            <h2>Upcoming Exams</h2>
            <router-link to="/exams" class="view-all">View All Exams</router-link>
          </div>
          
          <div class="exams-list">
            <div 
              v-for="exam in upcomingExams" 
              :key="exam.id"
              class="exam-item"
            >
              <div class="exam-date">
                <div class="day">{{ formatDateDay(exam.date) }}</div>
                <div class="month">{{ formatDateMonth(exam.date) }}</div>
              </div>
              <div class="exam-info">
                <div class="exam-name">{{ exam.name }}</div>
                <div class="exam-subject">{{ exam.subject }}</div>
                <div class="exam-time">{{ formatTime(exam.startTime) }} - {{ formatTime(exam.endTime) }}</div>
              </div>
              <div class="exam-actions">
                <button class="btn-outline">View Details</button>
              </div>
            </div>
            
            <div v-if="upcomingExams.length === 0" class="no-exams">
              No upcoming exams
            </div>
          </div>
        </div>
        
        <!-- Recent Assignments -->
        <div class="assignments-section">
          <div class="section-header">
            <h2>Recent Assignments</h2>
            <router-link to="/assignments" class="view-all">View All Assignments</router-link>
          </div>
          
          <div class="assignments-list">
            <div 
              v-for="assignment in recentAssignments" 
              :key="assignment.id"
              class="assignment-item"
              :class="{ 'overdue': isOverdue(assignment.dueDate) }"
            >
              <div class="assignment-icon">
                <i class="fas fa-file-alt"></i>
              </div>
              <div class="assignment-info">
                <div class="assignment-name">{{ assignment.name }}</div>
                <div class="assignment-subject">{{ assignment.subject }}</div>
                <div class="assignment-due">
                  <i class="fas fa-calendar"></i> Due: {{ formatDate(assignment.dueDate) }}
                </div>
              </div>
              <div class="assignment-status">
                <span 
                  class="status" 
                  :class="assignment.status"
                >
                  {{ formatStatus(assignment.status) }}
                </span>
              </div>
            </div>
            
            <div v-if="recentAssignments.length === 0" class="no-assignments">
              No recent assignments
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
import { DashboardService, StudentService } from '../../Services';

const page = usePage();

// Get user data from page props
const userName = computed(() => page.props.auth?.user?.name || 'Student');

// Reactive data
const coursesCount = ref(0);
const assignmentsCount = ref(0);
const attendanceRate = ref(0);
const feesDue = ref(0);
const todaySchedule = ref([]);
const upcomingExams = ref([]);
const recentAssignments = ref([]);

// Fetch dashboard data
const fetchDashboardData = async () => {
  try {
    // Fetch dashboard data from API
    const dashboardData = await DashboardService.getDashboardData();
    
    // Update stats
    if (dashboardData.stats) {
      coursesCount.value = dashboardData.stats.courses || 0;
      assignmentsCount.value = dashboardData.stats.assignments || 0;
      attendanceRate.value = dashboardData.stats.attendance || 0;
      feesDue.value = dashboardData.stats.feesDue || 0;
    }
    
    // Fetch additional data
    await Promise.all([
      fetchTodaySchedule(),
      fetchUpcomingExams(),
      fetchRecentAssignments()
    ]);
  } catch (error) {
    console.error('Error fetching dashboard data:', error);
  }
};

// Fetch today's schedule
const fetchTodaySchedule = async () => {
  try {
    const data = await StudentService.getTodayTimetable();
    todaySchedule.value = data.schedule || [];
  } catch (error) {
    console.error('Error fetching today schedule:', error);
  }
};

// Fetch upcoming exams
const fetchUpcomingExams = async () => {
  try {
    const data = await StudentService.getUpcomingExams();
    upcomingExams.value = data.exams || [];
  } catch (error) {
    console.error('Error fetching upcoming exams:', error);
  }
};

// Fetch recent assignments
const fetchRecentAssignments = async () => {
  try {
    const data = await StudentService.getAssignments();
    recentAssignments.value = data.assignments || [];
  } catch (error) {
    console.error('Error fetching recent assignments:', error);
  }
};

// Helper functions (keeping existing ones)
const isCurrentClass = (classItem) => {
  // Implementation would compare current time with class times
  return false;
};

const isPastClass = (classItem) => {
  // Implementation would check if class time has passed
  return false;
};

const formatTime = (time) => {
  return time;
};

const formatDateDay = (date) => {
  return new Date(date).getDate();
};

const formatDateMonth = (date) => {
  const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
  return months[new Date(date).getMonth()];
};

const isOverdue = (dueDate) => {
  return new Date(dueDate) < new Date();
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};

const formatStatus = (status) => {
  const statusMap = {
    'pending': 'Pending',
    'submitted': 'Submitted',
    'graded': 'Graded',
    'overdue': 'Overdue'
  };
  return statusMap[status] || status;
};

// Fetch data when component mounts
onMounted(() => {
  fetchDashboardData();
});
</script>

<style scoped>
/* ... existing styles ... */
.student-dashboard {
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

.schedule-section, .exams-section, .assignments-section {
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

.schedule-list, .exams-list, .assignments-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.schedule-item {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 15px;
  border-radius: 8px;
  border: 1px solid #f3f4f6;
  transition: all 0.2s ease;
}

.schedule-item:hover {
  border-color: #667eea;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.schedule-item.current {
  border-color: #22c55e;
  background: rgba(34, 197, 94, 0.05);
}

.time {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 80px;
}

.start-time {
  font-weight: 600;
  color: #1f2937;
}

.end-time {
  font-size: 0.85rem;
  color: #6b7280;
}

.class-info {
  flex: 1;
}

.subject {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 5px;
}

.details {
  display: flex;
  gap: 15px;
  font-size: 0.9rem;
  color: #6b7280;
}

.details i {
  margin-right: 5px;
}

.status .live {
  background: #22c55e;
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 500;
}

.status .completed {
  background: #9ca3af;
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 500;
}

.status .upcoming {
  background: #667eea;
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 0.8rem;
  font-weight: 500;
}

.exam-item {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 15px;
  border-radius: 8px;
  border: 1px solid #f3f4f6;
  transition: all 0.2s ease;
}

.exam-item:hover {
  border-color: #667eea;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.exam-date {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 60px;
}

.exam-date .day {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
}

.exam-date .month {
  font-size: 0.9rem;
  color: #6b7280;
  text-transform: uppercase;
}

.exam-info {
  flex: 1;
}

.exam-name {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 5px;
}

.exam-subject {
  font-size: 0.9rem;
  color: #6b7280;
  margin-bottom: 5px;
}

.exam-time {
  font-size: 0.85rem;
  color: #9ca3af;
}

.exam-actions .btn-outline {
  padding: 8px 16px;
  border: 1px solid #667eea;
  color: #667eea;
  background: transparent;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s ease;
}

.exam-actions .btn-outline:hover {
  background: #667eea;
  color: white;
}

.assignment-item {
  display: flex;
  align-items: center;
  gap: 20px;
  padding: 15px;
  border-radius: 8px;
  border: 1px solid #f3f4f6;
  transition: all 0.2s ease;
}

.assignment-item:hover {
  border-color: #667eea;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.assignment-item.overdue {
  border-color: #ef4444;
  background: rgba(239, 68, 68, 0.05);
}

.assignment-icon {
  width: 40px;
  height: 40px;
  border-radius: 8px;
  background: #667eea;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1rem;
}

.assignment-info {
  flex: 1;
}

.assignment-name {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 5px;
}

.assignment-subject {
  font-size: 0.9rem;
  color: #6b7280;
  margin-bottom: 5px;
}

.assignment-due {
  font-size: 0.85rem;
  color: #9ca3af;
}

.assignment-due i {
  margin-right: 5px;
}

.assignment-status .status {
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 500;
}

.assignment-status .status.pending {
  background: #f59e0b;
  color: white;
}

.assignment-status .status.submitted {
  background: #667eea;
  color: white;
}

.assignment-status .status.graded {
  background: #22c55e;
  color: white;
}

.assignment-status .status.overdue {
  background: #ef4444;
  color: white;
}

.no-schedule, .no-exams, .no-assignments {
  text-align: center;
  padding: 40px;
  color: #6b7280;
  font-style: italic;
}

@media (max-width: 768px) {
  .student-dashboard {
    padding: 15px;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .schedule-item, .exam-item, .assignment-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
  
  .time, .exam-date {
    flex-direction: row;
    gap: 10px;
    min-width: auto;
  }
  
  .exam-date .day {
    font-size: 1rem;
  }
  
  .details {
    flex-direction: column;
    gap: 5px;
  }
}
</style>