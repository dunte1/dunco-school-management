<template>
  <AppLayout>
    <div class="teacher-attendance">
      <div class="page-header">
        <h1>Attendance Management</h1>
        <p>Track and manage student attendance for your classes</p>
      </div>
      
      <div class="attendance-controls">
        <div class="class-selector">
          <label for="class-select">Select Class:</label>
          <select id="class-select" v-model="selectedClass">
            <option value="">All Classes</option>
            <option 
              v-for="classItem in classes" 
              :key="classItem.id" 
              :value="classItem.id"
            >
              {{ classItem.name }} ({{ classItem.studentCount }} students)
            </option>
          </select>
        </div>
        
        <div class="date-selector">
          <label for="date-select">Select Date:</label>
          <input 
            type="date" 
            id="date-select" 
            v-model="selectedDate"
            :max="today"
          >
        </div>
        
        <div class="actions">
          <button class="btn-primary" @click="takeAttendance">
            <i class="fas fa-clipboard-list"></i>
            Take Attendance
          </button>
          <button class="btn-secondary" @click="exportAttendance">
            <i class="fas fa-download"></i>
            Export
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
              <div class="stat-value">{{ attendanceStats.present }}</div>
              <div class="stat-label">Present</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon absent">
              <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ attendanceStats.absent }}</div>
              <div class="stat-label">Absent</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon late">
              <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ attendanceStats.late }}</div>
              <div class="stat-label">Late</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon percentage">
              <i class="fas fa-percentage"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ attendanceStats.percentage }}%</div>
              <div class="stat-label">Attendance Rate</div>
            </div>
          </div>
        </div>
        
        <div class="attendance-chart">
          <h3>Weekly Attendance Trend</h3>
          <div class="chart-container">
            <div 
              v-for="(day, index) in attendanceTrend" 
              :key="index"
              class="chart-bar"
              :class="day.status"
              :style="{ height: getBarHeight(day.percentage) }"
              :title="`${day.date}: ${day.percentage}%`"
            ></div>
          </div>
        </div>
      </div>
      
      <div class="attendance-tabs">
        <button 
          :class="{ active: activeTab === 'records' }"
          @click="activeTab = 'records'"
        >
          Attendance Records
        </button>
        <button 
          :class="{ active: activeTab === 'reports' }"
          @click="activeTab = 'reports'"
        >
          Reports
        </button>
        <button 
          :class="{ active: activeTab === 'settings' }"
          @click="activeTab = 'settings'"
        >
          Settings
        </button>
      </div>
      
      <!-- Attendance Records Tab -->
      <div v-if="activeTab === 'records'" class="records-tab">
        <div class="filters">
          <div class="filter-group">
            <label for="status-filter">Status:</label>
            <select id="status-filter" v-model="statusFilter">
              <option value="">All</option>
              <option value="present">Present</option>
              <option value="absent">Absent</option>
              <option value="late">Late</option>
            </select>
          </div>
          
          <div class="filter-group">
            <label for="search-filter">Search:</label>
            <input 
              type="text" 
              id="search-filter" 
              placeholder="Search students..."
              v-model="searchFilter"
            >
          </div>
        </div>
        
        <div class="attendance-table">
          <div class="table-header">
            <div class="header-cell">Student</div>
            <div class="header-cell">Class</div>
            <div class="header-cell">Status</div>
            <div class="header-cell">Time</div>
            <div class="header-cell">Remarks</div>
            <div class="header-cell">Actions</div>
          </div>
          
          <div 
            v-for="record in filteredRecords" 
            :key="record.id"
            class="table-row"
          >
            <div class="table-cell">
              <div class="student-info">
                <div class="student-avatar">
                  <i class="fas fa-user"></i>
                </div>
                <div>
                  <div class="student-name">{{ record.student.name }}</div>
                  <div class="student-id">{{ record.student.id }}</div>
                </div>
              </div>
            </div>
            <div class="table-cell">{{ record.class }}</div>
            <div class="table-cell">
              <span class="status-badge" :class="record.status">
                {{ record.status }}
              </span>
            </div>
            <div class="table-cell">{{ record.time }}</div>
            <div class="table-cell">{{ record.remarks }}</div>
            <div class="table-cell">
              <button class="btn-icon" @click="editRecord(record)">
                <i class="fas fa-edit"></i>
              </button>
            </div>
          </div>
          
          <div v-if="filteredRecords.length === 0" class="no-records">
            <i class="fas fa-clipboard-list"></i>
            <p>No attendance records found</p>
          </div>
        </div>
      </div>
      
      <!-- Reports Tab -->
      <div v-else-if="activeTab === 'reports'" class="reports-tab">
        <div class="report-controls">
          <div class="control-group">
            <label for="report-period">Period:</label>
            <select id="report-period" v-model="reportPeriod">
              <option value="daily">Daily</option>
              <option value="weekly">Weekly</option>
              <option value="monthly">Monthly</option>
              <option value="custom">Custom Range</option>
            </select>
          </div>
          
          <div v-if="reportPeriod === 'custom'" class="control-group">
            <label for="start-date">Start:</label>
            <input type="date" id="start-date" v-model="customStartDate">
            <label for="end-date">End:</label>
            <input type="date" id="end-date" v-model="customEndDate">
          </div>
          
          <button class="btn-primary" @click="generateReport">
            <i class="fas fa-file-alt"></i>
            Generate Report
          </button>
        </div>
        
        <div class="reports-list">
          <div 
            v-for="report in reports" 
            :key="report.id"
            class="report-item"
          >
            <div class="report-info">
              <h4>{{ report.title }}</h4>
              <p>{{ report.description }}</p>
              <span class="report-date">{{ formatDate(report.date) }}</span>
            </div>
            <div class="report-actions">
              <button class="btn-icon" @click="viewReport(report)">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn-icon" @click="downloadReport(report)">
                <i class="fas fa-download"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Settings Tab -->
      <div v-else class="settings-tab">
        <div class="settings-section">
          <h3>Attendance Settings</h3>
          <div class="setting-item">
            <label for="late-threshold">Late Arrival Threshold (minutes):</label>
            <input 
              type="number" 
              id="late-threshold" 
              v-model="lateThreshold"
              min="1"
              max="60"
            >
          </div>
          
          <div class="setting-item">
            <label for="auto-notify">Auto-notify parents for absences:</label>
            <input 
              type="checkbox" 
              id="auto-notify" 
              v-model="autoNotifyParents"
            >
          </div>
          
          <div class="setting-item">
            <label for="notify-threshold">Notify after consecutive absences:</label>
            <input 
              type="number" 
              id="notify-threshold" 
              v-model="notifyThreshold"
              min="1"
              max="10"
            >
          </div>
          
          <button class="btn-primary" @click="saveSettings">
            <i class="fas fa-save"></i>
            Save Settings
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';

// Reactive data
const activeTab = ref('records');
const selectedClass = ref('');
const selectedDate = ref(new Date().toISOString().split('T')[0]);
const today = ref(new Date().toISOString().split('T')[0]);
const statusFilter = ref('');
const searchFilter = ref('');
const reportPeriod = ref('weekly');
const customStartDate = ref(new Date(new Date().setDate(new Date().getDate() - 7)).toISOString().split('T')[0]);
const customEndDate = ref(new Date().toISOString().split('T')[0]);
const lateThreshold = ref(10);
const autoNotifyParents = ref(true);
const notifyThreshold = ref(3);

// Mock data - in a real app, this would come from API
const classes = ref([
  { id: 1, name: 'Mathematics 101', studentCount: 25 },
  { id: 2, name: 'Physics 201', studentCount: 22 },
  { id: 3, name: 'Chemistry 101', studentCount: 24 }
]);

const attendanceRecords = ref([
  {
    id: 1,
    student: { id: 'STU001', name: 'John Smith' },
    class: 'Mathematics 101',
    status: 'present',
    time: '09:00',
    remarks: '',
    date: '2025-09-15'
  },
  {
    id: 2,
    student: { id: 'STU002', name: 'Emma Johnson' },
    class: 'Mathematics 101',
    status: 'late',
    time: '09:15',
    remarks: '15 minutes late',
    date: '2025-09-15'
  },
  {
    id: 3,
    student: { id: 'STU003', name: 'Michael Brown' },
    class: 'Mathematics 101',
    status: 'absent',
    time: '',
    remarks: 'Sick leave',
    date: '2025-09-15'
  },
  {
    id: 4,
    student: { id: 'STU004', name: 'Sarah Davis' },
    class: 'Physics 201',
    status: 'present',
    time: '10:00',
    remarks: '',
    date: '2025-09-15'
  }
]);

const reports = ref([
  {
    id: 1,
    title: 'Mathematics 101 - Weekly Report',
    description: 'Attendance report for Grade 10A Mathematics class',
    date: '2025-09-15'
  },
  {
    id: 2,
    title: 'Physics 201 - Monthly Report',
    description: 'Attendance report for Grade 11B Physics class',
    date: '2025-09-10'
  }
]);

const attendanceTrend = ref([
  { date: 'Mon', percentage: 95, status: 'present' },
  { date: 'Tue', percentage: 88, status: 'late' },
  { date: 'Wed', percentage: 92, status: 'present' },
  { date: 'Thu', percentage: 90, status: 'present' },
  { date: 'Fri', percentage: 85, status: 'absent' }
]);

// Computed properties
const attendanceStats = computed(() => {
  const present = attendanceRecords.value.filter(r => r.status === 'present').length;
  const absent = attendanceRecords.value.filter(r => r.status === 'absent').length;
  const late = attendanceRecords.value.filter(r => r.status === 'late').length;
  const total = attendanceRecords.value.length;
  const percentage = total > 0 ? Math.round(((present + late) / total) * 100) : 0;
  
  return { present, absent, late, percentage };
});

const filteredRecords = computed(() => {
  return attendanceRecords.value.filter(record => {
    const matchesStatus = statusFilter.value ? record.status === statusFilter.value : true;
    const matchesSearch = searchFilter.value ? 
      record.student.name.toLowerCase().includes(searchFilter.value.toLowerCase()) ||
      record.student.id.toLowerCase().includes(searchFilter.value.toLowerCase()) : true;
    
    return matchesStatus && matchesSearch;
  });
});

// Methods
const takeAttendance = () => {
  alert('Taking attendance for selected class and date');
};

const exportAttendance = () => {
  alert('Exporting attendance records');
};

const getBarHeight = (percentage) => {
  return `${percentage}%`;
};

const editRecord = (record) => {
  alert(`Editing attendance record for ${record.student.name}`);
};

const generateReport = () => {
  alert(`Generating ${reportPeriod.value} report`);
};

const viewReport = (report) => {
  alert(`Viewing report: ${report.title}`);
};

const downloadReport = (report) => {
  alert(`Downloading report: ${report.title}`);
};

const saveSettings = () => {
  alert('Saving attendance settings');
};

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

// Lifecycle
onMounted(() => {
  // In a real app, we would fetch data from the API here
});
</script>

<style scoped>
.teacher-attendance {
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
  align-items: flex-end;
  gap: 20px;
  margin-bottom: 30px;
  flex-wrap: wrap;
}

.class-selector,
.date-selector {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.class-selector label,
.date-selector label {
  font-weight: 500;
  color: #1f2937;
}

.class-selector select,
.date-selector input {
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
  min-width: 200px;
}

.actions {
  display: flex;
  gap: 10px;
}

.actions button {
  display: flex;
  align-items: center;
  gap: 8px;
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
  gap: 10px;
  padding: 10px 0;
}

.chart-bar {
  flex: 1;
  background: #e5e7eb;
  border-radius: 4px 4px 0 0;
  min-width: 30px;
  transition: height 0.3s ease;
  position: relative;
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

/* Records Tab */
.records-tab {
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
  flex-direction: column;
  gap: 8px;
}

.filter-group label {
  font-weight: 500;
  color: #1f2937;
}

.filter-group select,
.filter-group input {
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
  min-width: 150px;
}

.attendance-table {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
}

.table-header {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 2fr 0.5fr;
  background: #f9fafb;
  font-weight: 600;
  padding: 12px 15px;
}

.table-row {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 2fr 0.5fr;
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
  display: flex;
  align-items: center;
}

.student-info {
  display: flex;
  align-items: center;
  gap: 15px;
}

.student-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #9ca3af;
}

.student-name {
  font-weight: 500;
  color: #1f2937;
}

.student-id {
  font-size: 0.85rem;
  color: #6b7280;
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

.btn-icon {
  background: none;
  border: none;
  color: #3b82f6;
  cursor: pointer;
  font-size: 1.1rem;
  padding: 5px;
  border-radius: 4px;
}

.btn-icon:hover {
  background: #dbeafe;
}

.no-records {
  grid-column: 1 / -1;
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.no-records i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
}

/* Reports Tab */
.reports-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.report-controls {
  display: flex;
  gap: 20px;
  margin-bottom: 30px;
  flex-wrap: wrap;
  align-items: end;
}

.control-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.control-group label {
  font-weight: 500;
  color: #1f2937;
}

.control-group select,
.control-group input {
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
  min-width: 120px;
}

.reports-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.report-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  transition: all 0.2s ease;
}

.report-item:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border-color: #d1d5db;
}

.report-info h4 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #1f2937;
}

.report-info p {
  color: #6b7280;
  margin-bottom: 10px;
}

.report-date {
  font-size: 0.85rem;
  color: #9ca3af;
}

.report-actions {
  display: flex;
  gap: 10px;
}

/* Settings Tab */
.settings-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.settings-section h3 {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 20px;
  color: #1f2937;
}

.setting-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 0;
  border-bottom: 1px solid #e5e7eb;
}

.setting-item:last-child {
  border-bottom: none;
}

.setting-item label {
  font-weight: 500;
  color: #1f2937;
}

.setting-item input[type="number"] {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  width: 80px;
}

.setting-item input[type="checkbox"] {
  width: 20px;
  height: 20px;
}

/* Dark mode support */
.dark .teacher-attendance {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .stat-value,
.dark .student-name,
.dark .report-info h4 {
  color: #f9fafb;
}

.dark .page-header p,
.dark .stat-label,
.dark .class-selector label,
.dark .date-selector label,
.dark .filter-group label,
.dark .control-group label,
.dark .setting-item label,
.dark .student-id,
.dark .report-info p,
.report-date {
  color: #d1d5db;
}

.dark .stat-card,
.dark .attendance-chart,
.dark .records-tab,
.dark .reports-tab,
.dark .settings-tab {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .attendance-tabs {
  background: #374151;
}

.dark .attendance-tabs button.active {
  background: #1f2937;
}

.dark .class-selector select,
.dark .date-selector input,
.dark .filter-group select,
.dark .filter-group input,
.dark .control-group select,
.dark .control-group input,
.dark .setting-item input[type="number"] {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
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

.dark .report-item {
  border-color: #374151;
  background: #1f2937;
}

.dark .report-item:hover {
  border-color: #4b5563;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.dark .btn-icon:hover {
  background: #1e3a8a;
}

.dark .no-records {
  color: #9ca3af;
}

.dark .no-records i {
  color: #4b5563;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .teacher-attendance {
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
  
  .stats-cards {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .filters {
    flex-direction: column;
  }
  
  .table-header,
  .table-row {
    grid-template-columns: 1fr;
    gap: 10px;
  }
  
  .report-controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .setting-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }
}
</style>