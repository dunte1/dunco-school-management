<template>
  <AppLayout>
    <div class="student-attendance">
      <div class="page-header">
        <h1>My Attendance</h1>
        <p>Track your attendance records and statistics</p>
      </div>
      
      <div class="attendance-overview">
        <div class="stats-cards">
          <div class="stat-card">
            <div class="stat-icon present">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ presentCount }}</div>
              <div class="stat-label">Present</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon absent">
              <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ absentCount }}</div>
              <div class="stat-label">Absent</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon late">
              <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ lateCount }}</div>
              <div class="stat-label">Late</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon percentage">
              <i class="fas fa-percentage"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ attendancePercentage }}%</div>
              <div class="stat-label">Attendance</div>
            </div>
          </div>
        </div>
        
        <div class="attendance-chart">
          <h3>Monthly Attendance</h3>
          <div class="chart-container">
            <div 
              v-for="(day, index) in attendanceData" 
              :key="index"
              class="chart-bar"
              :class="day.status"
              :style="{ height: getBarHeight(day.status) }"
              :title="`${formatDate(day.date)}: ${day.status}`"
            ></div>
          </div>
        </div>
      </div>
      
      <div class="attendance-tabs">
        <button 
          :class="{ active: activeTab === 'calendar' }"
          @click="activeTab = 'calendar'"
        >
          Calendar View
        </button>
        <button 
          :class="{ active: activeTab === 'list' }"
          @click="activeTab = 'list'"
        >
          Detailed Records
        </button>
        <button 
          :class="{ active: activeTab === 'subjects' }"
          @click="activeTab = 'subjects'"
        >
          By Subject
        </button>
      </div>
      
      <!-- Calendar View -->
      <div v-if="activeTab === 'calendar'" class="calendar-view">
        <div class="calendar-header">
          <button @click="previousMonth">
            <i class="fas fa-chevron-left"></i>
          </button>
          <h3>{{ currentMonthYear }}</h3>
          <button @click="nextMonth">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
        
        <div class="calendar-grid">
          <div class="calendar-day-header" v-for="day in calendarDays" :key="day">
            {{ day }}
          </div>
          
          <div 
            v-for="day in calendarDaysInMonth" 
            :key="day.date"
            class="calendar-day"
            :class="{ 
              'today': isToday(day.date),
              'present': day.status === 'present',
              'absent': day.status === 'absent',
              'late': day.status === 'late',
              'weekend': isWeekend(day.date)
            }"
          >
            <span class="day-number">{{ day.day }}</span>
            <div class="status-indicator" v-if="day.status">
              <i 
                :class="{
                  'fas fa-check-circle': day.status === 'present',
                  'fas fa-times-circle': day.status === 'absent',
                  'fas fa-clock': day.status === 'late'
                }"
              ></i>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Detailed Records -->
      <div v-else-if="activeTab === 'list'" class="records-view">
        <div class="filters">
          <div class="filter-group">
            <label for="date-filter">Date Range:</label>
            <input type="date" id="date-filter" v-model="startDate">
            <span>to</span>
            <input type="date" v-model="endDate">
          </div>
          
          <div class="filter-group">
            <label for="status-filter">Status:</label>
            <select id="status-filter" v-model="statusFilter">
              <option value="">All</option>
              <option value="present">Present</option>
              <option value="absent">Absent</option>
              <option value="late">Late</option>
            </select>
          </div>
        </div>
        
        <div class="records-table">
          <div class="table-header">
            <div class="header-cell">Date</div>
            <div class="header-cell">Subject</div>
            <div class="header-cell">Status</div>
            <div class="header-cell">Time</div>
            <div class="header-cell">Remarks</div>
          </div>
          
          <div 
            v-for="record in filteredRecords" 
            :key="record.id"
            class="table-row"
          >
            <div class="table-cell">{{ formatDate(record.date) }}</div>
            <div class="table-cell">{{ record.subject }}</div>
            <div class="table-cell">
              <span class="status-badge" :class="record.status">
                {{ record.status }}
              </span>
            </div>
            <div class="table-cell">{{ record.time }}</div>
            <div class="table-cell">{{ record.remarks }}</div>
          </div>
          
          <div v-if="filteredRecords.length === 0" class="no-records">
            <i class="fas fa-clipboard-list"></i>
            <p>No attendance records found</p>
          </div>
        </div>
      </div>
      
      <!-- By Subject -->
      <div v-else class="subjects-view">
        <div class="subjects-grid">
          <div 
            v-for="subject in subjectAttendance" 
            :key="subject.name"
            class="subject-card"
          >
            <div class="subject-header">
              <h4>{{ subject.name }}</h4>
            </div>
            
            <div class="subject-stats">
              <div class="stat-item">
                <span class="stat-label">Present:</span>
                <span class="stat-value">{{ subject.present }}</span>
              </div>
              <div class="stat-item">
                <span class="stat-label">Absent:</span>
                <span class="stat-value">{{ subject.absent }}</span>
              </div>
              <div class="stat-item">
                <span class="stat-label">Late:</span>
                <span class="stat-value">{{ subject.late }}</span>
              </div>
              <div class="stat-item">
                <span class="stat-label">Percentage:</span>
                <span class="stat-value">{{ subject.percentage }}%</span>
              </div>
            </div>
            
            <div class="subject-progress">
              <div class="progress-bar">
                <div 
                  class="progress-fill" 
                  :style="{ width: subject.percentage + '%' }"
                ></div>
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
const activeTab = ref('calendar');
const currentDate = ref(new Date());
const startDate = ref(new Date(new Date().setDate(new Date().getDate() - 30)).toISOString().split('T')[0]);
const endDate = ref(new Date().toISOString().split('T')[0]);
const statusFilter = ref('');

// Mock data - in a real app, this would come from API
const attendanceRecords = ref([
  { id: 1, date: '2025-09-01', subject: 'Mathematics', status: 'present', time: '09:00', remarks: '' },
  { id: 2, date: '2025-09-01', subject: 'English', status: 'present', time: '10:00', remarks: '' },
  { id: 3, date: '2025-09-02', subject: 'Physics', status: 'late', time: '09:15', remarks: '15 minutes late' },
  { id: 4, date: '2025-09-02', subject: 'Chemistry', status: 'present', time: '11:00', remarks: '' },
  { id: 5, date: '2025-09-03', subject: 'Biology', status: 'absent', time: '', remarks: 'Sick leave' },
  { id: 6, date: '2025-09-03', subject: 'History', status: 'present', time: '14:00', remarks: '' },
  { id: 7, date: '2025-09-04', subject: 'Mathematics', status: 'present', time: '09:00', remarks: '' },
  { id: 8, date: '2025-09-04', subject: 'English', status: 'present', time: '10:00', remarks: '' },
  { id: 9, date: '2025-09-05', subject: 'Physics', status: 'present', time: '09:00', remarks: '' },
  { id: 10, date: '2025-09-05', subject: 'Chemistry', status: 'late', time: '11:10', remarks: '10 minutes late' },
  { id: 11, date: '2025-09-08', subject: 'Biology', status: 'present', time: '09:00', remarks: '' },
  { id: 12, date: '2025-09-08', subject: 'History', status: 'absent', time: '', remarks: 'Family event' },
  { id: 13, date: '2025-09-09', subject: 'Mathematics', status: 'present', time: '09:00', remarks: '' },
  { id: 14, date: '2025-09-09', subject: 'English', status: 'present', time: '10:00', remarks: '' },
  { id: 15, date: '2025-09-10', subject: 'Physics', status: 'present', time: '09:00', remarks: '' },
  { id: 16, date: '2025-09-10', subject: 'Chemistry', status: 'present', time: '11:00', remarks: '' },
  { id: 17, date: '2025-09-11', subject: 'Biology', status: 'late', time: '09:05', remarks: '5 minutes late' },
  { id: 18, date: '2025-09-11', subject: 'History', status: 'present', time: '14:00', remarks: '' },
  { id: 19, date: '2025-09-12', subject: 'Mathematics', status: 'absent', time: '', remarks: 'Sick leave' },
  { id: 20, date: '2025-09-12', subject: 'English', status: 'present', time: '10:00', remarks: '' }
]);

// Computed properties
const presentCount = computed(() => {
  return attendanceRecords.value.filter(record => record.status === 'present').length;
});

const absentCount = computed(() => {
  return attendanceRecords.value.filter(record => record.status === 'absent').length;
});

const lateCount = computed(() => {
  return attendanceRecords.value.filter(record => record.status === 'late').length;
});

const attendancePercentage = computed(() => {
  const total = attendanceRecords.value.length;
  if (total === 0) return 0;
  return Math.round(((presentCount.value + lateCount.value) / total) * 100);
});

const attendanceData = computed(() => {
  // Generate data for the last 30 days
  const data = [];
  const today = new Date();
  
  for (let i = 29; i >= 0; i--) {
    const date = new Date(today);
    date.setDate(today.getDate() - i);
    const dateStr = date.toISOString().split('T')[0];
    
    const record = attendanceRecords.value.find(r => r.date === dateStr);
    data.push({
      date: dateStr,
      status: record ? record.status : 'none'
    });
  }
  
  return data;
});

const currentMonthYear = computed(() => {
  return currentDate.value.toLocaleDateString('en-US', { 
    month: 'long', 
    year: 'numeric' 
  });
});

const calendarDays = computed(() => {
  return ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
});

const calendarDaysInMonth = computed(() => {
  const year = currentDate.value.getFullYear();
  const month = currentDate.value.getMonth();
  
  // Get first day of month and last day of month
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);
  
  // Get days from previous month to fill first week
  const prevMonthDays = firstDay.getDay();
  
  // Get days from next month to fill last week
  const nextMonthDays = 6 - lastDay.getDay();
  
  const days = [];
  
  // Previous month days
  for (let i = prevMonthDays - 1; i >= 0; i--) {
    const date = new Date(year, month, -i);
    const dateStr = date.toISOString().split('T')[0];
    const record = attendanceRecords.value.find(r => r.date === dateStr);
    
    days.push({
      date: dateStr,
      day: date.getDate(),
      status: record ? record.status : null
    });
  }
  
  // Current month days
  for (let i = 1; i <= lastDay.getDate(); i++) {
    const date = new Date(year, month, i);
    const dateStr = date.toISOString().split('T')[0];
    const record = attendanceRecords.value.find(r => r.date === dateStr);
    
    days.push({
      date: dateStr,
      day: i,
      status: record ? record.status : null
    });
  }
  
  // Next month days
  for (let i = 1; i <= nextMonthDays; i++) {
    const date = new Date(year, month + 1, i);
    const dateStr = date.toISOString().split('T')[0];
    const record = attendanceRecords.value.find(r => r.date === dateStr);
    
    days.push({
      date: dateStr,
      day: i,
      status: record ? record.status : null
    });
  }
  
  return days;
});

const filteredRecords = computed(() => {
  return attendanceRecords.value.filter(record => {
    const recordDate = new Date(record.date);
    const start = new Date(startDate.value);
    const end = new Date(endDate.value);
    
    const dateInRange = recordDate >= start && recordDate <= end;
    const statusMatch = statusFilter.value ? record.status === statusFilter.value : true;
    
    return dateInRange && statusMatch;
  });
});

const subjectAttendance = computed(() => {
  const subjects = {};
  
  // Initialize subjects
  attendanceRecords.value.forEach(record => {
    if (!subjects[record.subject]) {
      subjects[record.subject] = {
        name: record.subject,
        present: 0,
        absent: 0,
        late: 0,
        total: 0
      };
    }
  });
  
  // Calculate stats
  attendanceRecords.value.forEach(record => {
    const subject = subjects[record.subject];
    subject[record.status]++;
    subject.total++;
  });
  
  // Calculate percentages
  Object.values(subjects).forEach(subject => {
    subject.percentage = subject.total > 0 
      ? Math.round(((subject.present + subject.late) / subject.total) * 100)
      : 0;
  });
  
  return Object.values(subjects);
});

// Methods
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const getBarHeight = (status) => {
  switch (status) {
    case 'present': return '80%';
    case 'late': return '60%';
    case 'absent': return '20%';
    default: return '10%';
  }
};

const previousMonth = () => {
  currentDate.value.setMonth(currentDate.value.getMonth() - 1);
  currentDate.value = new Date(currentDate.value);
};

const nextMonth = () => {
  currentDate.value.setMonth(currentDate.value.getMonth() + 1);
  currentDate.value = new Date(currentDate.value);
};

const isToday = (dateString) => {
  const today = new Date().toISOString().split('T')[0];
  return dateString === today;
};

const isWeekend = (dateString) => {
  const date = new Date(dateString);
  const day = date.getDay();
  return day === 0 || day === 6; // Sunday or Saturday
};

const getGradeStatus = (grade) => {
  if (grade >= 90) return 'excellent';
  if (grade >= 80) return 'good';
  if (grade >= 70) return 'average';
  if (grade >= 60) return 'below';
  return 'poor';
};

// Lifecycle
onMounted(() => {
  // In a real app, we would fetch data from the API here
});
</script>

<style scoped>
.student-attendance {
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

.attendance-overview {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 20px;
  margin-bottom: 30px;
}

.stats-cards {
  display: grid;
  grid-template-columns: 1fr;
  gap: 15px;
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

.stat-icon.present {
  background: #dcfce7;
  color: #22c55e;
}

.stat-icon.absent {
  background: #fee2e2;
  color: #ef4444;
}

.stat-icon.late {
  background: #ffedd5;
  color: #f97316;
}

.stat-icon.percentage {
  background: #dbeafe;
  color: #3b82f6;
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

.attendance-chart {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.attendance-chart h3 {
  margin-bottom: 20px;
  color: #1f2937;
}

.chart-container {
  display: flex;
  align-items: flex-end;
  height: 200px;
  gap: 2px;
  padding: 10px 0;
}

.chart-bar {
  flex: 1;
  background: #e5e7eb;
  border-radius: 4px 4px 0 0;
  min-width: 4px;
  transition: height 0.3s ease;
}

.chart-bar.present {
  background: #22c55e;
}

.chart-bar.absent {
  background: #ef4444;
}

.chart-bar.late {
  background: #f97316;
}

.attendance-tabs {
  display: flex;
  background: #f3f4f6;
  border-radius: 8px;
  padding: 4px;
  margin-bottom: 30px;
}

.attendance-tabs button {
  flex: 1;
  background: none;
  border: none;
  padding: 12px 16px;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.attendance-tabs button.active {
  background: white;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Calendar View */
.calendar-view {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.calendar-header h3 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
}

.calendar-header button {
  background: #f3f4f6;
  border: none;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.calendar-header button:hover {
  background: #e5e7eb;
}

.calendar-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 5px;
}

.calendar-day-header {
  text-align: center;
  font-weight: 600;
  padding: 10px 0;
  color: #6b7280;
}

.calendar-day {
  aspect-ratio: 1/1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  position: relative;
  cursor: pointer;
  transition: all 0.2s ease;
}

.calendar-day:hover {
  background: #f3f4f6;
}

.calendar-day.today {
  background: #dbeafe;
  font-weight: 700;
}

.calendar-day.present {
  background: #dcfce7;
}

.calendar-day.absent {
  background: #fee2e2;
}

.calendar-day.late {
  background: #ffedd5;
}

.calendar-day.weekend {
  background: #f9fafb;
}

.calendar-day .day-number {
  font-size: 0.9rem;
}

.status-indicator {
  position: absolute;
  bottom: 2px;
  font-size: 0.7rem;
}

.status-indicator i {
  color: #6b7280;
}

.calendar-day.present .status-indicator i {
  color: #22c55e;
}

.calendar-day.absent .status-indicator i {
  color: #ef4444;
}

.calendar-day.late .status-indicator i {
  color: #f97316;
}

/* Records View */
.records-view {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.filters {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.filter-group {
  display: flex;
  align-items: center;
  gap: 10px;
}

.filter-group label {
  font-weight: 500;
  color: #1f2937;
}

.filter-group input,
.filter-group select {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
}

.records-table {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
}

.table-header {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr 2fr;
  background: #f9fafb;
  font-weight: 600;
  padding: 12px 15px;
}

.table-row {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 1fr 2fr;
  padding: 12px 15px;
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
}

.status-badge {
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.status-badge.present {
  background: #dcfce7;
  color: #166534;
}

.status-badge.absent {
  background: #fee2e2;
  color: #991b1b;
}

.status-badge.late {
  background: #ffedd5;
  color: #9a3412;
}

.no-records {
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.no-records i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
}

/* Subjects View */
.subjects-view {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.subjects-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.subject-card {
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 20px;
  transition: all 0.2s ease;
}

.subject-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border-color: #d1d5db;
}

.subject-header h4 {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 15px;
  color: #1f2937;
}

.subject-stats {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-bottom: 15px;
}

.stat-item {
  display: flex;
  justify-content: space-between;
  font-size: 0.9rem;
}

.stat-label {
  color: #6b7280;
}

.stat-value {
  font-weight: 600;
  color: #1f2937;
}

.subject-progress {
  height: 8px;
  background: #e5e7eb;
  border-radius: 4px;
  overflow: hidden;
}

.progress-bar {
  height: 100%;
  background: #e5e7eb;
  border-radius: 4px;
}

.progress-fill {
  height: 100%;
  background: #22c55e;
  border-radius: 4px;
}

/* Dark mode support */
.dark .student-attendance {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .subject-header h4,
.dark .stat-value,
.dark .calendar-header h3 {
  color: #f9fafb;
}

.dark .page-header p,
.dark .stat-label,
.dark .calendar-day-header,
.dark .filter-group label {
  color: #d1d5db;
}

.dark .stat-card,
.dark .attendance-chart,
.dark .calendar-view,
.dark .records-view,
.dark .subjects-view {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .attendance-tabs {
  background: #374151;
}

.dark .attendance-tabs button.active {
  background: #1f2937;
}

.dark .calendar-header button {
  background: #374151;
}

.dark .calendar-header button:hover {
  background: #4b5563;
}

.dark .calendar-day:hover {
  background: #374151;
}

.dark .calendar-day.today {
  background: #1e3a8a;
}

.dark .calendar-day.weekend {
  background: #111827;
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

.dark .subject-card {
  border-color: #374151;
}

.dark .subject-card:hover {
  border-color: #4b5563;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.dark .progress-bar {
  background: #374151;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .student-attendance {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .attendance-overview {
    grid-template-columns: 1fr;
  }
  
  .stats-cards {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .filters {
    flex-direction: column;
  }
  
  .table-header,
  .table-row {
    grid-template-columns: 1fr 1fr;
    font-size: 0.9rem;
  }
  
  .subjects-grid {
    grid-template-columns: 1fr;
  }
  
  .calendar-grid {
    gap: 2px;
  }
}
</style>