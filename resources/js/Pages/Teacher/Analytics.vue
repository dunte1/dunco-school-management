<template>
  <AppLayout>
    <div class="teacher-analytics">
      <div class="page-header">
        <h1>Performance Analytics</h1>
        <p>Track and analyze student performance across your classes</p>
      </div>
      
      <div class="analytics-controls">
        <div class="class-selector">
          <label for="class-select">Select Class:</label>
          <select id="class-select" v-model="selectedClass">
            <option value="">All Classes</option>
            <option 
              v-for="classItem in classes" 
              :key="classItem.id" 
              :value="classItem.id"
            >
              {{ classItem.name }}
            </option>
          </select>
        </div>
        
        <div class="period-selector">
          <label for="period-select">Select Period:</label>
          <select id="period-select" v-model="selectedPeriod">
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="quarterly">Quarterly</option>
            <option value="yearly">Yearly</option>
          </select>
        </div>
        
        <div class="actions">
          <button class="btn-secondary" @click="exportReport">
            <i class="fas fa-download"></i>
            Export Report
          </button>
        </div>
      </div>
      
      <div class="analytics-overview">
        <div class="stats-cards">
          <div class="stat-card">
            <div class="stat-icon average">
              <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ classStats.averageScore }}%</div>
              <div class="stat-label">Class Average</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon highest">
              <i class="fas fa-star"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ classStats.highestScore }}%</div>
              <div class="stat-label">Highest Score</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon lowest">
              <i class="fas fa-arrow-down"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ classStats.lowestScore }}%</div>
              <div class="stat-label">Lowest Score</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon improvement">
              <i class="fas fa-arrow-up"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value" :class="{ 'positive': classStats.improvement > 0, 'negative': classStats.improvement < 0 }">
                {{ classStats.improvement > 0 ? '+' : '' }}{{ classStats.improvement }}%
              </div>
              <div class="stat-label">Improvement</div>
            </div>
          </div>
        </div>
        
        <div class="charts-section">
          <div class="chart-container">
            <h3>Performance Trend</h3>
            <div class="line-chart">
              <div 
                v-for="(point, index) in performanceTrend" 
                :key="index"
                class="chart-point"
                :style="{ height: point.value + '%' }"
                :title="`${point.label}: ${point.value}%`"
              ></div>
            </div>
          </div>
          
          <div class="chart-container">
            <h3>Grade Distribution</h3>
            <div class="bar-chart">
              <div 
                v-for="(grade, index) in gradeDistribution" 
                :key="index"
                class="bar"
                :style="{ height: grade.percentage + '%' }"
                :title="`${grade.grade}: ${grade.count} students (${grade.percentage}%)`"
              >
                <span class="bar-label">{{ grade.grade }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="analytics-tabs">
        <button 
          :class="{ active: activeTab === 'overview' }"
          @click="activeTab = 'overview'"
        >
          Class Overview
        </button>
        <button 
          :class="{ active: activeTab === 'students' }"
          @click="activeTab = 'students'"
        >
          Student Performance
        </button>
        <button 
          :class="{ active: activeTab === 'assignments' }"
          @click="activeTab = 'assignments'"
        >
          Assignment Analysis
        </button>
        <button 
          :class="{ active: activeTab === 'exams' }"
          @click="activeTab = 'exams'"
        >
          Exam Analysis
        </button>
      </div>
      
      <!-- Class Overview Tab -->
      <div v-if="activeTab === 'overview'" class="overview-tab">
        <div class="overview-grid">
          <div class="overview-card">
            <h3>Attendance Rate</h3>
            <div class="progress-circle">
              <svg viewBox="0 0 36 36" class="circular-chart">
                <path class="circle-bg"
                  d="M18 2.0845
                    a 15.9155 15.9155 0 0 1 0 31.831
                    a 15.9155 15.9155 0 0 1 0 -31.831"
                />
                <path class="circle"
                  :stroke-dasharray="attendanceRate + ', 100'"
                  d="M18 2.0845
                    a 15.9155 15.9155 0 0 1 0 31.831
                    a 15.9155 15.9155 0 0 1 0 -31.831"
                />
                <text x="18" y="20.5" class="percentage">{{ attendanceRate }}%</text>
              </svg>
            </div>
            <p class="overview-description">Class attendance rate for the selected period</p>
          </div>
          
          <div class="overview-card">
            <h3>Assignment Completion</h3>
            <div class="progress-circle">
              <svg viewBox="0 0 36 36" class="circular-chart">
                <path class="circle-bg"
                  d="M18 2.0845
                    a 15.9155 15.9155 0 0 1 0 31.831
                    a 15.9155 15.9155 0 0 1 0 -31.831"
                />
                <path class="circle"
                  :stroke-dasharray="assignmentCompletion + ', 100'"
                  d="M18 2.0845
                    a 15.9155 15.9155 0 0 1 0 31.831
                    a 15.9155 15.9155 0 0 1 0 -31.831"
                />
                <text x="18" y="20.5" class="percentage">{{ assignmentCompletion }}%</text>
              </svg>
            </div>
            <p class="overview-description">Percentage of assignments completed on time</p>
          </div>
          
          <div class="overview-card">
            <h3>Participation Rate</h3>
            <div class="progress-circle">
              <svg viewBox="0 0 36 36" class="circular-chart">
                <path class="circle-bg"
                  d="M18 2.0845
                    a 15.9155 15.9155 0 0 1 0 31.831
                    a 15.9155 15.9155 0 0 1 0 -31.831"
                />
                <path class="circle"
                  :stroke-dasharray="participationRate + ', 100'"
                  d="M18 2.0845
                    a 15.9155 15.9155 0 0 1 0 31.831
                    a 15.9155 15.9155 0 0 1 0 -31.831"
                />
                <text x="18" y="20.5" class="percentage">{{ participationRate }}%</text>
              </svg>
            </div>
            <p class="overview-description">Student participation in class activities</p>
          </div>
          
          <div class="overview-card">
            <h3>Behavior Score</h3>
            <div class="progress-circle">
              <svg viewBox="0 0 36 36" class="circular-chart">
                <path class="circle-bg"
                  d="M18 2.0845
                    a 15.9155 15.9155 0 0 1 0 31.831
                    a 15.9155 15.9155 0 0 1 0 -31.831"
                />
                <path class="circle"
                  :stroke-dasharray="behaviorScore + ', 100'"
                  d="M18 2.0845
                    a 15.9155 15.9155 0 0 1 0 31.831
                    a 15.9155 15.9155 0 0 1 0 -31.831"
                />
                <text x="18" y="20.5" class="percentage">{{ behaviorScore }}%</text>
              </svg>
            </div>
            <p class="overview-description">Overall student behavior in class</p>
          </div>
        </div>
      </div>
      
      <!-- Student Performance Tab -->
      <div v-else-if="activeTab === 'students'" class="students-tab">
        <div class="filters">
          <div class="filter-group">
            <label for="sort-by">Sort By:</label>
            <select id="sort-by" v-model="sortBy">
              <option value="name">Name</option>
              <option value="score">Score</option>
              <option value="attendance">Attendance</option>
              <option value="assignments">Assignments</option>
            </select>
          </div>
          
          <div class="filter-group">
            <label for="search-student">Search:</label>
            <input 
              type="text" 
              id="search-student" 
              placeholder="Search students..."
              v-model="searchStudent"
            >
          </div>
        </div>
        
        <div class="students-table">
          <div class="table-header">
            <div class="header-cell">Student</div>
            <div class="header-cell">Average Score</div>
            <div class="header-cell">Attendance</div>
            <div class="header-cell">Assignments</div>
            <div class="header-cell">Trend</div>
            <div class="header-cell">Actions</div>
          </div>
          
          <div 
            v-for="student in filteredStudents" 
            :key="student.id"
            class="table-row"
          >
            <div class="table-cell">
              <div class="student-info">
                <div class="student-avatar">
                  <i class="fas fa-user"></i>
                </div>
                <div>
                  <div class="student-name">{{ student.name }}</div>
                  <div class="student-id">{{ student.id }}</div>
                </div>
              </div>
            </div>
            <div class="table-cell">
              <div class="score-value" :class="getScoreClass(student.averageScore)">
                {{ student.averageScore }}%
              </div>
            </div>
            <div class="table-cell">{{ student.attendance }}%</div>
            <div class="table-cell">{{ student.assignmentsCompleted }}/{{ student.totalAssignments }}</div>
            <div class="table-cell">
              <div class="trend" :class="student.trend">
                <i :class="student.trend === 'up' ? 'fas fa-arrow-up' : student.trend === 'down' ? 'fas fa-arrow-down' : 'fas fa-minus'"></i>
              </div>
            </div>
            <div class="table-cell">
              <button class="btn-icon" @click="viewStudentDetails(student)">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>
          
          <div v-if="filteredStudents.length === 0" class="no-students">
            <i class="fas fa-users"></i>
            <p>No students found</p>
          </div>
        </div>
      </div>
      
      <!-- Assignment Analysis Tab -->
      <div v-else-if="activeTab === 'assignments'" class="assignments-tab">
        <div class="analysis-header">
          <h3>Assignment Performance Analysis</h3>
          <div class="period-filter">
            <label for="assignment-period">Period:</label>
            <select id="assignment-period" v-model="assignmentPeriod">
              <option value="all">All Time</option>
              <option value="month">This Month</option>
              <option value="quarter">This Quarter</option>
            </select>
          </div>
        </div>
        
        <div class="assignments-grid">
          <div 
            v-for="assignment in assignmentAnalysis" 
            :key="assignment.id"
            class="assignment-card"
          >
            <div class="assignment-header">
              <h4>{{ assignment.title }}</h4>
              <span class="assignment-date">{{ formatDate(assignment.date) }}</span>
            </div>
            
            <div class="assignment-stats">
              <div class="stat-item">
                <span class="stat-label">Average Score:</span>
                <span class="stat-value">{{ assignment.averageScore }}%</span>
              </div>
              <div class="stat-item">
                <span class="stat-label">Completion Rate:</span>
                <span class="stat-value">{{ assignment.completionRate }}%</span>
              </div>
              <div class="stat-item">
                <span class="stat-label">Late Submissions:</span>
                <span class="stat-value">{{ assignment.lateSubmissions }}</span>
              </div>
            </div>
            
            <div class="assignment-actions">
              <button class="btn-secondary" @click="viewAssignmentDetails(assignment)">
                <i class="fas fa-eye"></i>
                View Details
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Exam Analysis Tab -->
      <div v-else class="exams-tab">
        <div class="analysis-header">
          <h3>Exam Performance Analysis</h3>
          <div class="period-filter">
            <label for="exam-period">Period:</label>
            <select id="exam-period" v-model="examPeriod">
              <option value="all">All Time</option>
              <option value="month">This Month</option>
              <option value="quarter">This Quarter</option>
            </select>
          </div>
        </div>
        
        <div class="exams-grid">
          <div 
            v-for="exam in examAnalysis" 
            :key="exam.id"
            class="exam-card"
          >
            <div class="exam-header">
              <h4>{{ exam.title }}</h4>
              <span class="exam-date">{{ formatDate(exam.date) }}</span>
            </div>
            
            <div class="exam-stats">
              <div class="stat-item">
                <span class="stat-label">Average Score:</span>
                <span class="stat-value">{{ exam.averageScore }}%</span>
              </div>
              <div class="stat-item">
                <span class="stat-label">Pass Rate:</span>
                <span class="stat-value">{{ exam.passRate }}%</span>
              </div>
              <div class="stat-item">
                <span class="stat-label">Highest Score:</span>
                <span class="stat-value">{{ exam.highestScore }}%</span>
              </div>
            </div>
            
            <div class="exam-actions">
              <button class="btn-secondary" @click="viewExamDetails(exam)">
                <i class="fas fa-eye"></i>
                View Details
              </button>
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
const activeTab = ref('overview');
const selectedClass = ref('');
const selectedPeriod = ref('monthly');
const sortBy = ref('name');
const searchStudent = ref('');
const assignmentPeriod = ref('all');
const examPeriod = ref('all');

// Mock data - in a real app, this would come from API
const classes = ref([
  { id: 1, name: 'Mathematics 101' },
  { id: 2, name: 'Physics 201' },
  { id: 3, name: 'Chemistry 101' }
]);

const classStats = ref({
  averageScore: 78,
  highestScore: 95,
  lowestScore: 42,
  improvement: 5
});

const attendanceRate = ref(85);
const assignmentCompletion = ref(92);
const participationRate = ref(76);
const behaviorScore = ref(88);

const performanceTrend = ref([
  { label: 'Jan', value: 72 },
  { label: 'Feb', value: 75 },
  { label: 'Mar', value: 78 },
  { label: 'Apr', value: 80 },
  { label: 'May', value: 82 },
  { label: 'Jun', value: 85 }
]);

const gradeDistribution = ref([
  { grade: 'A', count: 5, percentage: 20 },
  { grade: 'B', count: 8, percentage: 32 },
  { grade: 'C', count: 7, percentage: 28 },
  { grade: 'D', count: 3, percentage: 12 },
  { grade: 'F', count: 2, percentage: 8 }
]);

const students = ref([
  {
    id: 'STU001',
    name: 'John Smith',
    averageScore: 85,
    attendance: 90,
    assignmentsCompleted: 12,
    totalAssignments: 15,
    trend: 'up'
  },
  {
    id: 'STU002',
    name: 'Emma Johnson',
    averageScore: 92,
    attendance: 95,
    assignmentsCompleted: 14,
    totalAssignments: 15,
    trend: 'up'
  },
  {
    id: 'STU003',
    name: 'Michael Brown',
    averageScore: 76,
    attendance: 80,
    assignmentsCompleted: 10,
    totalAssignments: 15,
    trend: 'down'
  },
  {
    id: 'STU004',
    name: 'Sarah Davis',
    averageScore: 88,
    attendance: 85,
    assignmentsCompleted: 13,
    totalAssignments: 15,
    trend: 'stable'
  }
]);

const assignmentAnalysis = ref([
  {
    id: 1,
    title: 'Algebra Basics',
    date: '2025-09-15',
    averageScore: 82,
    completionRate: 95,
    lateSubmissions: 2
  },
  {
    id: 2,
    title: 'Physics Lab Report',
    date: '2025-09-10',
    averageScore: 78,
    completionRate: 88,
    lateSubmissions: 4
  }
]);

const examAnalysis = ref([
  {
    id: 1,
    title: 'Midterm Exam',
    date: '2025-09-01',
    averageScore: 75,
    passRate: 85,
    highestScore: 95
  },
  {
    id: 2,
    title: 'Quiz 1',
    date: '2025-08-15',
    averageScore: 80,
    passRate: 90,
    highestScore: 92
  }
]);

// Computed properties
const filteredStudents = computed(() => {
  let filtered = students.value;
  
  // Apply search filter
  if (searchStudent.value) {
    const query = searchStudent.value.toLowerCase();
    filtered = filtered.filter(student => 
      student.name.toLowerCase().includes(query) ||
      student.id.toLowerCase().includes(query)
    );
  }
  
  // Apply sorting
  filtered.sort((a, b) => {
    switch (sortBy.value) {
      case 'name':
        return a.name.localeCompare(b.name);
      case 'score':
        return b.averageScore - a.averageScore;
      case 'attendance':
        return b.attendance - a.attendance;
      case 'assignments':
        return (b.assignmentsCompleted / b.totalAssignments) - (a.assignmentsCompleted / a.totalAssignments);
      default:
        return 0;
    }
  });
  
  return filtered;
});

// Methods
const exportReport = () => {
  alert('Exporting analytics report');
};

const viewStudentDetails = (student) => {
  alert(`Viewing details for: ${student.name}`);
};

const viewAssignmentDetails = (assignment) => {
  alert(`Viewing details for: ${assignment.title}`);
};

const viewExamDetails = (exam) => {
  alert(`Viewing details for: ${exam.title}`);
};

const getScoreClass = (score) => {
  if (score >= 90) return 'excellent';
  if (score >= 80) return 'good';
  if (score >= 70) return 'average';
  if (score >= 60) return 'below';
  return 'poor';
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
.teacher-analytics {
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

.analytics-controls {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  gap: 20px;
  margin-bottom: 30px;
  flex-wrap: wrap;
}

.class-selector,
.period-selector {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.class-selector label,
.period-selector label {
  font-weight: 500;
  color: #1f2937;
}

.class-selector select,
.period-selector select {
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
  min-width: 150px;
}

.actions button {
  display: flex;
  align-items: center;
  gap: 8px;
}

.analytics-overview {
  margin-bottom: 30px;
}

.stats-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
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

.stat-icon.average {
  background: #dbeafe;
  color: #3b82f6;
}

.stat-icon.highest {
  background: #dcfce7;
  color: #22c55e;
}

.stat-icon.lowest {
  background: #ffedd5;
  color: #f97316;
}

.stat-icon.improvement {
  background: #f0f9ff;
  color: #0ea5e9;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
}

.stat-value.positive {
  color: #22c55e;
}

.stat-value.negative {
  color: #ef4444;
}

.stat-label {
  font-size: 0.9rem;
  color: #6b7280;
}

.charts-section {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 30px;
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.chart-container h3 {
  margin-bottom: 20px;
  color: #1f2937;
}

.line-chart {
  display: flex;
  align-items: flex-end;
  height: 200px;
  gap: 10px;
  padding: 10px 0;
}

.chart-point {
  flex: 1;
  background: #3b82f6;
  border-radius: 4px 4px 0 0;
  min-width: 20px;
  position: relative;
}

.chart-point::after {
  content: '';
  position: absolute;
  top: -5px;
  left: 50%;
  transform: translateX(-50%);
  width: 10px;
  height: 10px;
  background: #3b82f6;
  border-radius: 50%;
}

.bar-chart {
  display: flex;
  align-items: flex-end;
  height: 200px;
  gap: 15px;
  padding: 10px 0;
}

.bar {
  flex: 1;
  background: #3b82f6;
  border-radius: 4px 4px 0 0;
  min-width: 30px;
  position: relative;
  display: flex;
  align-items: flex-end;
  justify-content: center;
  padding-bottom: 5px;
}

.bar-label {
  font-size: 0.8rem;
  color: #1f2937;
  transform: rotate(-90deg);
  white-space: nowrap;
}

.analytics-tabs {
  display: flex;
  background: #f3f4f6;
  border-radius: 8px;
  padding: 4px;
  margin-bottom: 30px;
  flex-wrap: wrap;
}

.analytics-tabs button {
  flex: 1;
  min-width: 120px;
  background: none;
  border: none;
  padding: 12px 16px;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.analytics-tabs button.active {
  background: white;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Overview Tab */
.overview-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.overview-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.overview-card {
  text-align: center;
  padding: 20px;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
}

.overview-card h3 {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 20px;
  color: #1f2937;
}

.progress-circle {
  width: 120px;
  height: 120px;
  margin: 0 auto 20px;
}

.circular-chart {
  width: 100%;
  height: 100%;
}

.circle-bg {
  fill: none;
  stroke: #e5e7eb;
  stroke-width: 3;
}

.circle {
  fill: none;
  stroke-width: 3;
  stroke: #3b82f6;
  stroke-linecap: round;
  animation: progress 1s ease-out forwards;
}

@keyframes progress {
  0% {
    stroke-dasharray: 0 100;
  }
}

.percentage {
  fill: #1f2937;
  font-size: 0.5em;
  text-anchor: middle;
  font-weight: bold;
}

.overview-description {
  color: #6b7280;
  font-size: 0.9rem;
}

/* Students Tab */
.students-tab {
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

.students-table {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
}

.table-header {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr 0.5fr;
  background: #f9fafb;
  font-weight: 600;
  padding: 12px 15px;
}

.table-row {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr 0.5fr;
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

.score-value {
  font-weight: 600;
}

.score-value.excellent {
  color: #22c55e;
}

.score-value.good {
  color: #3b82f6;
}

.score-value.average {
  color: #f97316;
}

.score-value.below {
  color: #f59e0b;
}

.score-value.poor {
  color: #ef4444;
}

.trend {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.trend.up {
  background: #dcfce7;
  color: #22c55e;
}

.trend.down {
  background: #fee2e2;
  color: #ef4444;
}

.trend.stable {
  background: #f3f4f6;
  color: #6b7280;
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

.no-students {
  grid-column: 1 / -1;
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.no-students i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
}

/* Assignments Tab */
.assignments-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.analysis-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  flex-wrap: wrap;
  gap: 15px;
}

.analysis-header h3 {
  font-size: 1.3rem;
  font-weight: 600;
  color: #1f2937;
}

.period-filter {
  display: flex;
  align-items: center;
  gap: 10px;
}

.period-filter label {
  font-weight: 500;
  color: #1f2937;
}

.period-filter select {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
  min-width: 120px;
}

.assignments-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.assignment-card {
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 20px;
  transition: all 0.2s ease;
}

.assignment-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border-color: #d1d5db;
}

.assignment-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
}

.assignment-header h4 {
  font-size: 1.1rem;
  font-weight: 600;
  color: #1f2937;
}

.assignment-date {
  font-size: 0.85rem;
  color: #6b7280;
}

.assignment-stats {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 20px;
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

.assignment-actions button {
  width: 100%;
  padding: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

/* Exams Tab */
.exams-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.exams-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.exam-card {
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 20px;
  transition: all 0.2s ease;
}

.exam-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border-color: #d1d5db;
}

.exam-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
}

.exam-header h4 {
  font-size: 1.1rem;
  font-weight: 600;
  color: #1f2937;
}

.exam-date {
  font-size: 0.85rem;
  color: #6b7280;
}

.exam-stats {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 20px;
}

.exam-actions button {
  width: 100%;
  padding: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

/* Dark mode support */
.dark .teacher-analytics {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .stat-value,
.dark .overview-card h3,
.analysis-header h3,
.assignment-header h4,
.exam-header h4,
.student-name {
  color: #f9fafb;
}

.dark .page-header p,
.dark .stat-label,
.class-selector label,
.period-selector label,
.filter-group label,
.period-filter label,
.assignment-date,
.exam-date,
.student-id,
.overview-description,
.stat-label {
  color: #d1d5db;
}

.dark .stat-card,
.dark .charts-section,
.dark .overview-tab,
.students-tab,
.assignments-tab,
.exams-tab {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .analytics-tabs {
  background: #374151;
}

.dark .analytics-tabs button.active {
  background: #1f2937;
}

.dark .class-selector select,
.period-selector select,
.filter-group select,
.filter-group input,
.period-filter select {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
}

.dark .overview-card {
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

.dark .btn-icon:hover {
  background: #1e3a8a;
}

.dark .assignment-card,
.dark .exam-card {
  border-color: #374151;
  background: #1f2937;
}

.dark .assignment-card:hover,
.dark .exam-card:hover {
  border-color: #4b5563;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.dark .no-students {
  color: #9ca3af;
}

.dark .no-students i {
  color: #4b5563;
}

.dark .circle-bg {
  stroke: #374151;
}

.dark .circle {
  stroke: #3b82f6;
}

.dark .percentage {
  fill: #f9fafb;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .teacher-analytics {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .analytics-controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .stats-cards {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .charts-section {
    grid-template-columns: 1fr;
  }
  
  .filters {
    flex-direction: column;
  }
  
  .table-header,
  .table-row {
    grid-template-columns: 1fr;
    gap: 10px;
  }
  
  .analysis-header {
    flex-direction: column;
    align-items: stretch;
  }
}
</style>