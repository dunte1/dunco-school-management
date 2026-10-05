<template>
  <AppLayout>
    <div class="parent-attendance">
      <div class="page-header">
        <h1>Child Attendance</h1>
        <p>Track your child's attendance records and statistics</p>
      </div>
      
      <div class="attendance-controls">
        <div class="child-selector">
          <label for="child-select">Select Child:</label>
          <select id="child-select" v-model="selectedChild" @change="loadAttendanceData">
            <option v-for="child in children" :key="child.id" :value="child.id">
              {{ child.name }}
            </option>
          </select>
        </div>
        
        <div class="date-navigation">
          <button @click="previousMonth">
            <i class="fas fa-chevron-left"></i>
          </button>
          <span class="current-period">{{ currentMonthYear }}</span>
          <button @click="nextMonth">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
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
              <div class="stat-label">Attendance Rate</div>
            </div>
          </div>
        </div>
        
        <div class="attendance-chart">
          <h3>Monthly Attendance Trend</h3>
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
          :class="{ active: activeTab === 'analytics' }"
          @click="activeTab = 'analytics'"
        >
          Analytics
        </button>
      </div>
      
      <!-- Calendar View -->
      <div v-if="activeTab === 'calendar'" class="calendar-view">
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
            <div class="remarks" v-if="day.remarks">
              <i class="fas fa-info-circle" :title="day.remarks"></i>
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
            <div class="header-cell">Day</div>
            <div class="header-cell">Status</div>
            <div class="header-cell">Remarks</div>
            <div class="header-cell">Actions</div>
          </div>
          
          <div 
            v-for="record in filteredRecords" 
            :key="record.id"
            class="table-row"
          >
            <div class="table-cell">{{ formatDate(record.date) }}</div>
            <div class="table-cell">{{ formatDate(record.date, 'weekday') }}</div>
            <div class="table-cell">
              <span class="status-badge" :class="record.status">
                {{ record.status }}
              </span>
            </div>
            <div class="table-cell">{{ record.remarks }}</div>
            <div class="table-cell">
              <button 
                v-if="record.status !== 'present'" 
                class="btn-secondary"
                @click="requestExcuse(record)"
              >
                Request Excuse
              </button>
            </div>
          </div>
          
          <div v-if="filteredRecords.length === 0" class="no-records">
            <i class="fas fa-clipboard-list"></i>
            <p>No attendance records found</p>
          </div>
        </div>
      </div>
      
      <!-- Analytics -->
      <div v-else class="analytics-view">
        <div class="analytics-grid">
          <div class="analytics-card">
            <h3>Attendance Trends</h3>
            <div class="trend-chart">
              <div 
                v-for="(month, index) in monthlyAttendance" 
                :key="index"
                class="trend-bar"
                :style="{ height: month.percentage + '%' }"
                :title="`${month.name}: ${month.percentage}%`"
              ></div>
            </div>
            <div class="trend-labels">
              <span v-for="(month, index) in monthlyAttendance" :key="index">
                {{ month.name }}
              </span>
            </div>
          </div>
          
          <div class="analytics-card">
            <h3>Late Arrival Patterns</h3>
            <div class="late-patterns">
              <div 
                v-for="(pattern, index) in latePatterns" 
                :key="index"
                class="pattern-item"
              >
                <div class="pattern-label">{{ pattern.time }}</div>
                <div class="pattern-bar">
                  <div 
                    class="pattern-fill" 
                    :style="{ width: pattern.percentage + '%' }"
                  ></div>
                </div>
                <div class="pattern-value">{{ pattern.count }} times</div>
              </div>
            </div>
          </div>
          
          <div class="analytics-card">
            <h3>Absence Reasons</h3>
            <div class="absence-reasons">
              <div 
                v-for="(reason, index) in absenceReasons" 
                :key="index"
                class="reason-item"
              >
                <div class="reason-label">{{ reason.label }}</div>
                <div class="reason-bar">
                  <div 
                    class="reason-fill" 
                    :style="{ width: reason.percentage + '%' }"
                  ></div>
                </div>
                <div class="reason-value">{{ reason.count }}</div>
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
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Reactive data
const activeTab = ref('calendar');
const selectedChild = ref(1);
const currentDate = ref(new Date());
const startDate = ref(new Date(new Date().setDate(new Date().getDate() - 30)).toISOString().split('T')[0]);
const endDate = ref(new Date().toISOString().split('T')[0]);
const statusFilter = ref('');
const children = ref([
  { id: 1, name: 'John Doe' },
  { id: 2, name: 'Jane Doe' }
]);

// Mock data - in a real app, this would come from API
const attendanceRecords = ref([
  { id: 1, date: '2025-09-01', status: 'present', remarks: '' },
  { id: 2, date: '2025-09-02', status: 'late', remarks: '15 minutes late' },
  { id: 3, date: '2025-09-03', status: 'absent', remarks: 'Sick leave' },
  { id: 4, date: '2025-09-04', status: 'present', remarks: '' },
  { id: 5, date: '2025-09-05', status: 'present', remarks: '' },
  { id: 6, date: '2025-09-08', status: 'late', remarks: 'Traffic delay' },
  { id: 7, date: '2025-09-09', status: 'present', remarks: '' },
  { id: 8, date: '2025-09-10', status: 'present', remarks: '' },
  { id: 9, date: '2025-09-11', status: 'absent', remarks: 'Family event' },
  { id: 10, date: '2025-09-12', status: 'present', remarks: '' },
  { id: 11, date: '2025-09-13', status: 'weekend', remarks: '' },
  { id: 12, date: '2025-09-14', status: 'weekend', remarks: '' }
]);

const monthlyAttendance = ref([
  { name: 'Jun', percentage: 85 },
  { name: 'Jul', percentage: 92 },
  { name: 'Aug', percentage: 88 },
  { name: 'Sep', percentage: 90 }
]);

const latePatterns = ref([
  { time: '09:00-09:10', count: 3, percentage: 60 },
  { time: '09:10-09:20', count: 2, percentage: 40 }
]);

const absenceReasons = ref([
  { label: 'Sick', count: 2, percentage: 50 },
  { label: 'Family Event', count: 1, percentage: 25 },
  { label: 'Other', count: 1, percentage: 25 }
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
  const total = presentCount.value + absentCount.value + lateCount.value;
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
      status: record ? record.status : null,
      remarks: record ? record.remarks : ''
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
      status: record ? record.status : null,
      remarks: record ? record.remarks : ''
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
      status: record ? record.status : null,
      remarks: record ? record.remarks : ''
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
    
    return dateInRange && statusMatch && record.status !== 'weekend';
  });
});

// Methods
const formatDate = (dateString, format) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  const date = new Date(dateString);
  
  if (format === 'weekday') {
    return date.toLocaleDateString('en-US', { weekday: 'short' });
  }
  
  return date.toLocaleDateString(undefined, options);
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

const loadAttendanceData = () => {
  // In a real app, this would fetch data from the API for the selected child
  console.log(`Loading attendance data for child ID: ${selectedChild.value}`);
};

const requestExcuse = (record) => {
  // In a real app, this would open a modal or navigate to excuse request form
  alert(`Requesting excuse for absence on ${formatDate(record.date)}`);
};

// Lifecycle
onMounted(() => {
  // In a real app, we would fetch data from the API here
});
</script>

<style scoped>
.parent-attendance {
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

.attendance-controls {
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

.child-selector {
  display: flex;
  align-items: center;
  gap: 10px;
}

.child-selector label {
  font-weight: 500;
  color: #1f2937;
}

.child-selector select {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 1rem;
}

.date-navigation {
  display: flex;
  align-items: center;
  gap: 15px;
}

.date-navigation button {
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

.date-navigation button:hover {
  background: #e5e7eb;
}

.current-period {
  font-weight: 600;
  color: #1f2937;
  min-width: 200px;
  text-align: center;
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
  bottom: 15px;
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

.remarks {
  position: absolute;
  top: 5px;
  right: 5px;
  font-size: 0.7rem;
  color: #9ca3af;
}

.remarks i {
  cursor: help;
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
  grid-template-columns: 1fr 1fr 1fr 2fr 1fr;
  background: #f9fafb;
  font-weight: 600;
  padding: 12px 15px;
}

.table-row {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr 2fr 1fr;
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

/* Analytics View */
.analytics-view {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.analytics-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 20px;
}

.analytics-card {
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 20px;
}

.analytics-card h3 {
  margin-bottom: 20px;
  color: #1f2937;
}

.trend-chart {
  display: flex;
  align-items: flex-end;
  height: 200px;
  gap: 10px;
  padding: 20px 0;
}

.trend-bar {
  flex: 1;
  background: #e5e7eb;
  border-radius: 4px 4px 0 0;
  min-width: 30px;
  position: relative;
}

.trend-bar::after {
  content: attr(title);
  position: absolute;
  bottom: -25px;
  left: 0;
  right: 0;
  text-align: center;
  font-size: 0.8rem;
  color: #6b7280;
}

.trend-labels {
  display: flex;
  justify-content: space-around;
  margin-top: 30px;
  font-size: 0.9rem;
  color: #6b7280;
}

.late-patterns,
.absence-reasons {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.pattern-item,
.reason-item {
  display: flex;
  align-items: center;
  gap: 10px;
}

.pattern-label,
.reason-label {
  width: 100px;
  font-size: 0.9rem;
  color: #6b7280;
}

.pattern-bar,
.reason-bar {
  flex: 1;
  height: 20px;
  background: #e5e7eb;
  border-radius: 10px;
  overflow: hidden;
}

.pattern-fill,
.reason-fill {
  height: 100%;
  background: #3b82f6;
  border-radius: 10px;
}

.pattern-value,
.reason-value {
  width: 80px;
  text-align: right;
  font-size: 0.9rem;
  font-weight: 500;
  color: #1f2937;
}

/* Dark mode support */
.dark .parent-attendance {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .stat-value,
.analytics-card h3 {
  color: #f9fafb;
}

.dark .page-header p,
.dark .stat-label,
.dark .calendar-day-header,
.dark .filter-group label,
.pattern-label,
.reason-label {
  color: #d1d5db;
}

.dark .attendance-controls,
.dark .stat-card,
.dark .attendance-chart,
.dark .calendar-view,
.dark .records-view,
.dark .analytics-view {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .attendance-tabs {
  background: #374151;
}

.dark .attendance-tabs button.active {
  background: #1f2937;
}

.dark .date-navigation button {
  background: #374151;
}

.dark .date-navigation button:hover {
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

.dark .analytics-card {
  border-color: #374151;
}

.dark .trend-bar {
  background: #374151;
}

.dark .pattern-bar,
.dark .reason-bar {
  background: #374151;
}

.dark .pattern-fill,
.dark .reason-fill {
  background: #6366f1;
}

.dark .no-records {
  background: #1f2937;
  color: #9ca3af;
}

.dark .no-records i {
  color: #4b5563;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .parent-attendance {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .attendance-controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .attendance-overview {
    grid-template-columns: 1fr;
  }
  
  .calendar-grid {
    gap: 3px;
  }
  
  .table-row {
    grid-template-columns: 1fr 1fr 1fr 2fr 1fr;
  }
  
  .analytics-grid {
    grid-template-columns: 1fr;
  }
}
</style>