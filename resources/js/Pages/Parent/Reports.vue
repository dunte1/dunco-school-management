<template>
  <AppLayout>
    <div class="parent-reports">
      <div class="page-header">
        <h1>Academic Reports</h1>
        <p>View your child's academic performance and progress</p>
      </div>
      
      <div class="reports-controls">
        <div class="child-selector">
          <label for="child-select">Select Child:</label>
          <select id="child-select" v-model="selectedChild" @change="loadReportData">
            <option v-for="child in children" :key="child.id" :value="child.id">
              {{ child.name }}
            </option>
          </select>
        </div>
        
        <div class="report-filters">
          <div class="filter-group">
            <label for="term-filter">Term:</label>
            <select id="term-filter" v-model="selectedTerm">
              <option value="all">All Terms</option>
              <option value="term1">Term 1</option>
              <option value="term2">Term 2</option>
              <option value="term3">Term 3</option>
            </select>
          </div>
          
          <div class="filter-group">
            <label for="subject-filter">Subject:</label>
            <select id="subject-filter" v-model="selectedSubject">
              <option value="all">All Subjects</option>
              <option v-for="subject in subjects" :key="subject.id" :value="subject.id">
                {{ subject.name }}
              </option>
            </select>
          </div>
        </div>
      </div>
      
      <div class="reports-overview">
        <div class="overall-performance">
          <h2>Overall Performance</h2>
          <div class="performance-cards">
            <div class="performance-card">
              <div class="card-header">
                <h3>Current Average</h3>
                <div class="grade-badge" :class="getGradeClass(overallAverage)">
                  {{ overallAverage }}
                </div>
              </div>
              <div class="trend-indicator" :class="overallTrend">
                <i :class="overallTrend === 'up' ? 'fas fa-arrow-up' : 'fas fa-arrow-down'"></i>
                <span>{{ overallTrend === 'up' ? 'Improving' : 'Declining' }}</span>
              </div>
            </div>
            
            <div class="performance-card">
              <div class="card-header">
                <h3>Best Subject</h3>
                <div class="subject-badge">
                  {{ bestSubject.name }}
                </div>
              </div>
              <div class="subject-grade">
                <span>Grade:</span>
                <span class="grade-badge" :class="getGradeClass(bestSubject.grade)">
                  {{ bestSubject.grade }}
                </span>
              </div>
            </div>
            
            <div class="performance-card">
              <div class="card-header">
                <h3>Needs Attention</h3>
                <div class="subject-badge">
                  {{ needsAttention.name }}
                </div>
              </div>
              <div class="subject-grade">
                <span>Grade:</span>
                <span class="grade-badge" :class="getGradeClass(needsAttention.grade)">
                  {{ needsAttention.grade }}
                </span>
              </div>
            </div>
          </div>
        </div>
        
        <div class="performance-chart">
          <h3>Performance Trend</h3>
          <div class="chart-container">
            <canvas ref="performanceChart"></canvas>
          </div>
        </div>
      </div>
      
      <div class="reports-tabs">
        <button 
          :class="{ active: activeTab === 'subjects' }"
          @click="activeTab = 'subjects'"
        >
          Subject Reports
        </button>
        <button 
          :class="{ active: activeTab === 'assignments' }"
          @click="activeTab = 'assignments'"
        >
          Assignment Performance
        </button>
        <button 
          :class="{ active: activeTab === 'exams' }"
          @click="activeTab = 'exams'"
        >
          Exam Results
        </button>
        <button 
          :class="{ active: activeTab === 'comments' }"
          @click="activeTab = 'comments'"
        >
          Teacher Comments
        </button>
      </div>
      
      <!-- Subject Reports -->
      <div v-if="activeTab === 'subjects'" class="subjects-tab">
        <div class="subjects-grid">
          <div 
            v-for="subject in subjectReports" 
            :key="subject.id"
            class="subject-card"
            :class="getGradeClass(subject.average)"
          >
            <div class="subject-header">
              <h4>{{ subject.name }}</h4>
              <div class="subject-grade">
                <span class="grade-badge" :class="getGradeClass(subject.average)">
                  {{ subject.average }}
                </span>
              </div>
            </div>
            
            <div class="subject-details">
              <div class="detail-item">
                <span>Assignments:</span>
                <span>{{ subject.assignmentsCompleted }}/{{ subject.totalAssignments }}</span>
              </div>
              <div class="detail-item">
                <span>Exams Taken:</span>
                <span>{{ subject.examsTaken }}</span>
              </div>
              <div class="detail-item">
                <span>Attendance:</span>
                <span>{{ subject.attendance }}%</span>
              </div>
            </div>
            
            <div class="subject-progress">
              <div class="progress-bar">
                <div 
                  class="progress-fill" 
                  :style="{ width: getGradePercentage(subject.average) + '%' }"
                  :class="getGradeClass(subject.average)"
                ></div>
              </div>
              <div class="progress-label">{{ getGradePercentage(subject.average) }}%</div>
            </div>
            
            <button class="btn-secondary view-details" @click="viewSubjectDetails(subject)">
              View Details
            </button>
          </div>
        </div>
      </div>
      
      <!-- Assignment Performance -->
      <div v-else-if="activeTab === 'assignments'" class="assignments-tab">
        <div class="assignments-table">
          <div class="table-header">
            <div class="header-cell">Assignment</div>
            <div class="header-cell">Subject</div>
            <div class="header-cell">Due Date</div>
            <div class="header-cell">Status</div>
            <div class="header-cell">Grade</div>
            <div class="header-cell">Feedback</div>
          </div>
          
          <div 
            v-for="assignment in assignmentReports" 
            :key="assignment.id"
            class="table-row"
          >
            <div class="table-cell">{{ assignment.title }}</div>
            <div class="table-cell">{{ assignment.subject }}</div>
            <div class="table-cell">{{ formatDate(assignment.dueDate) }}</div>
            <div class="table-cell">
              <span class="status-badge" :class="assignment.status">
                {{ assignment.status }}
              </span>
            </div>
            <div class="table-cell">
              <span 
                v-if="assignment.grade" 
                class="grade-badge" 
                :class="getGradeClass(assignment.grade)"
              >
                {{ assignment.grade }}
              </span>
              <span v-else>-</span>
            </div>
            <div class="table-cell">
              <button 
                v-if="assignment.feedback" 
                class="btn-icon"
                @click="viewFeedback(assignment)"
                title="View Feedback"
              >
                <i class="fas fa-comment"></i>
              </button>
              <span v-else>-</span>
            </div>
          </div>
          
          <div v-if="assignmentReports.length === 0" class="no-assignments">
            <i class="fas fa-tasks"></i>
            <p>No assignment reports available</p>
          </div>
        </div>
      </div>
      
      <!-- Exam Results -->
      <div v-else-if="activeTab === 'exams'" class="exams-tab">
        <div class="exams-grid">
          <div 
            v-for="exam in examReports" 
            :key="exam.id"
            class="exam-card"
          >
            <div class="exam-header">
              <h4>{{ exam.name }}</h4>
              <span class="exam-subject">{{ exam.subject }}</span>
            </div>
            
            <div class="exam-details">
              <div class="detail-item">
                <span>Date:</span>
                <span>{{ formatDate(exam.date) }}</span>
              </div>
              <div class="detail-item">
                <span>Grade:</span>
                <span class="grade-badge" :class="getGradeClass(exam.grade)">
                  {{ exam.grade }}
                </span>
              </div>
              <div class="detail-item">
                <span>Percentile:</span>
                <span>{{ exam.percentile }}%</span>
              </div>
              <div class="detail-item">
                <span>Class Average:</span>
                <span>{{ exam.classAverage }}</span>
              </div>
            </div>
            
            <div class="exam-progress">
              <div class="progress-bar">
                <div 
                  class="progress-fill" 
                  :style="{ width: exam.percentage + '%' }"
                  :class="getGradeClass(exam.grade)"
                ></div>
              </div>
              <div class="progress-label">{{ exam.percentage }}%</div>
            </div>
            
            <button class="btn-secondary view-report" @click="viewExamReport(exam)">
              Detailed Report
            </button>
          </div>
        </div>
      </div>
      
      <!-- Teacher Comments -->
      <div v-else class="comments-tab">
        <div class="comments-list">
          <div 
            v-for="comment in teacherComments" 
            :key="comment.id"
            class="comment-item"
          >
            <div class="comment-header">
              <div class="teacher-info">
                <div class="teacher-avatar">
                  <i class="fas fa-user"></i>
                </div>
                <div class="teacher-details">
                  <h4>{{ comment.teacher }}</h4>
                  <p>{{ comment.subject }} • {{ formatDate(comment.date) }}</p>
                </div>
              </div>
              <div class="comment-tags">
                <span 
                  v-for="tag in comment.tags" 
                  :key="tag"
                  class="tag"
                  :class="tag"
                >
                  {{ tag }}
                </span>
              </div>
            </div>
            
            <div class="comment-content">
              <p>{{ comment.content }}</p>
            </div>
            
            <div class="comment-actions">
              <button class="btn-secondary" @click="replyToTeacher(comment)">
                <i class="fas fa-reply"></i>
                Reply
              </button>
              <button class="btn-secondary" @click="scheduleMeeting(comment)">
                <i class="fas fa-calendar-check"></i>
                Schedule Meeting
              </button>
            </div>
          </div>
          
          <div v-if="teacherComments.length === 0" class="no-comments">
            <i class="fas fa-comments"></i>
            <p>No teacher comments available</p>
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
const activeTab = ref('subjects');
const selectedChild = ref(1);
const selectedTerm = ref('all');
const selectedSubject = ref('all');
const performanceChart = ref(null);
const chartInstance = ref(null);

const children = ref([
  { id: 1, name: 'John Doe' },
  { id: 2, name: 'Jane Doe' }
]);

const subjects = ref([
  { id: 1, name: 'Mathematics' },
  { id: 2, name: 'English' },
  { id: 3, name: 'Physics' },
  { id: 4, name: 'Chemistry' },
  { id: 5, name: 'Biology' }
]);

// Mock data - in a real app, this would come from API
const reportData = ref({
  overallAverage: 'B+',
  overallTrend: 'up',
  bestSubject: { name: 'Mathematics', grade: 'A' },
  needsAttention: { name: 'Chemistry', grade: 'C-' },
  subjectReports: [
    { 
      id: 1, 
      name: 'Mathematics', 
      average: 'A', 
      assignmentsCompleted: 12, 
      totalAssignments: 15, 
      examsTaken: 3, 
      attendance: 95 
    },
    { 
      id: 2, 
      name: 'English', 
      average: 'B+', 
      assignmentsCompleted: 10, 
      totalAssignments: 12, 
      examsTaken: 2, 
      attendance: 90 
    },
    { 
      id: 3, 
      name: 'Physics', 
      average: 'B', 
      assignmentsCompleted: 8, 
      totalAssignments: 10, 
      examsTaken: 3, 
      attendance: 88 
    },
    { 
      id: 4, 
      name: 'Chemistry', 
      average: 'C-', 
      assignmentsCompleted: 7, 
      totalAssignments: 10, 
      examsTaken: 2, 
      attendance: 85 
    },
    { 
      id: 5, 
      name: 'Biology', 
      average: 'B+', 
      assignmentsCompleted: 9, 
      totalAssignments: 11, 
      examsTaken: 2, 
      attendance: 92 
    }
  ],
  assignmentReports: [
    {
      id: 1,
      title: 'Algebra Basics',
      subject: 'Mathematics',
      dueDate: '2025-09-10',
      status: 'submitted',
      grade: 'A',
      feedback: 'Excellent work! Clear understanding of concepts.'
    },
    {
      id: 2,
      title: 'Essay Writing',
      subject: 'English',
      dueDate: '2025-09-12',
      status: 'submitted',
      grade: 'B+',
      feedback: 'Good effort. Work on sentence structure.'
    },
    {
      id: 3,
      title: 'Physics Laws',
      subject: 'Physics',
      dueDate: '2025-09-15',
      status: 'pending',
      grade: null,
      feedback: null
    }
  ],
  examReports: [
    {
      id: 1,
      name: 'Midterm Exam',
      subject: 'Mathematics',
      date: '2025-09-01',
      grade: 'A',
      percentage: 92,
      percentile: 85,
      classAverage: 'B+'
    },
    {
      id: 2,
      name: 'Quarterly Test',
      subject: 'English',
      date: '2025-09-05',
      grade: 'B+',
      percentage: 87,
      percentile: 72,
      classAverage: 'B'
    }
  ],
  teacherComments: [
    {
      id: 1,
      teacher: 'Mr. Johnson',
      subject: 'Mathematics',
      date: '2025-09-10',
      content: 'John has shown excellent improvement in problem-solving skills. Keep up the good work!',
      tags: ['excellent', 'improvement']
    },
    {
      id: 2,
      teacher: 'Ms. Brown',
      subject: 'English',
      date: '2025-09-08',
      content: 'John needs to work on his essay structure. I recommend additional practice with outlining.',
      tags: ['needs-work', 'recommendation']
    }
  ]
});

// Computed properties
const overallAverage = computed(() => reportData.value.overallAverage);
const overallTrend = computed(() => reportData.value.overallTrend);
const bestSubject = computed(() => reportData.value.bestSubject);
const needsAttention = computed(() => reportData.value.needsAttention);
const subjectReports = computed(() => reportData.value.subjectReports);
const assignmentReports = computed(() => reportData.value.assignmentReports);
const examReports = computed(() => reportData.value.examReports);
const teacherComments = computed(() => reportData.value.teacherComments);

// Methods
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const getGradeClass = (grade) => {
  if (!grade) return '';
  
  const gradeValue = grade.replace(/\+|-/g, '');
  
  switch (gradeValue) {
    case 'A': return 'grade-a';
    case 'B': return 'grade-b';
    case 'C': return 'grade-c';
    case 'D': return 'grade-d';
    case 'F': return 'grade-f';
    default: return '';
  }
};

const getGradePercentage = (grade) => {
  switch (grade) {
    case 'A': return 95;
    case 'A-': return 90;
    case 'B+': return 87;
    case 'B': return 83;
    case 'B-': return 80;
    case 'C+': return 77;
    case 'C': return 73;
    case 'C-': return 70;
    case 'D+': return 67;
    case 'D': return 63;
    case 'D-': return 60;
    case 'F': return 40;
    default: return 0;
  }
};

const loadReportData = () => {
  // In a real app, this would fetch data from the API for the selected child
  console.log(`Loading report data for child ID: ${selectedChild.value}`);
};

const viewSubjectDetails = (subject) => {
  // In a real app, this would navigate to detailed subject report
  alert(`Viewing details for ${subject.name}`);
};

const viewFeedback = (assignment) => {
  // In a real app, this would show feedback in a modal
  alert(`Feedback for ${assignment.title}: ${assignment.feedback}`);
};

const viewExamReport = (exam) => {
  // In a real app, this would navigate to detailed exam report
  alert(`Viewing report for ${exam.name}`);
};

const replyToTeacher = (comment) => {
  // In a real app, this would open message composer
  router.visit('/messages');
};

const scheduleMeeting = (comment) => {
  // In a real app, this would open meeting scheduler
  alert(`Scheduling meeting with ${comment.teacher}`);
};

const initChart = () => {
  if (chartInstance.value) {
    chartInstance.value.destroy();
  }
  
  const ctx = performanceChart.value.getContext('2d');
  
  chartInstance.value = new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Term 1', 'Term 2', 'Term 3'],
      datasets: [{
        label: 'Overall Performance',
        data: [75, 82, 88],
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
          min: 50,
          max: 100,
          ticks: {
            callback: function(value) {
              return value + '%';
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
    initChart();
  });
});
</script>

<style scoped>
.parent-reports {
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

.reports-controls {
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

.report-filters {
  display: flex;
  gap: 15px;
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

.filter-group select {
  padding: 8px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 1rem;
}

.reports-overview {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 30px;
  margin-bottom: 30px;
}

.overall-performance h2 {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 20px;
  color: #1f2937;
}

.performance-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
}

.performance-card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.card-header h3 {
  font-size: 1.1rem;
  font-weight: 600;
  color: #1f2937;
}

.trend-indicator {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 0.9rem;
  font-weight: 500;
}

.trend-indicator.up {
  color: #22c55e;
}

.trend-indicator.down {
  color: #ef4444;
}

.subject-badge {
  background: #e5e7eb;
  color: #1f2937;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 500;
}

.subject-grade {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 0.9rem;
  color: #6b7280;
}

.performance-chart {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.performance-chart h3 {
  margin-bottom: 20px;
  color: #1f2937;
}

.chart-container {
  height: 300px;
  position: relative;
}

.reports-tabs {
  display: flex;
  background: #f3f4f6;
  border-radius: 8px;
  padding: 4px;
  margin-bottom: 30px;
}

.reports-tabs button {
  flex: 1;
  background: none;
  border: none;
  padding: 12px 16px;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.reports-tabs button.active {
  background: white;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Subject Reports */
.subjects-tab {
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

.subject-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.subject-header h4 {
  font-size: 1.2rem;
  font-weight: 600;
  color: #1f2937;
}

.subject-details {
  display: grid;
  grid-template-columns: 1fr;
  gap: 10px;
  margin-bottom: 15px;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  font-size: 0.9rem;
}

.detail-item span:first-child {
  color: #6b7280;
}

.detail-item span:last-child {
  font-weight: 500;
  color: #1f2937;
}

.subject-progress {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 15px;
}

.progress-bar {
  flex: 1;
  height: 8px;
  background: #e5e7eb;
  border-radius: 4px;
  overflow: hidden;
}

.progress-fill {
  height: 100%;
  border-radius: 4px;
}

.progress-label {
  font-size: 0.9rem;
  font-weight: 500;
  color: #1f2937;
  min-width: 40px;
}

.view-details {
  width: 100%;
}

/* Assignments Tab */
.assignments-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.assignments-table {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
}

.table-header {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr;
  background: #f9fafb;
  font-weight: 600;
  padding: 12px 15px;
}

.table-row {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr;
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

.status-badge.submitted {
  background: #dcfce7;
  color: #166534;
}

.status-badge.pending {
  background: #ffedd5;
  color: #9a3412;
}

.status-badge.late {
  background: #fee2e2;
  color: #991b1b;
}

.no-assignments {
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.no-assignments i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
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
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
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
  align-items: center;
  margin-bottom: 15px;
}

.exam-header h4 {
  font-size: 1.2rem;
  font-weight: 600;
  color: #1f2937;
}

.exam-subject {
  background: #e5e7eb;
  color: #1f2937;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 500;
}

.exam-details {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-bottom: 15px;
}

.exam-progress {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 15px;
}

.view-report {
  width: 100%;
}

/* Comments Tab */
.comments-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.comments-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.comment-item {
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 20px;
}

.comment-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
  flex-wrap: wrap;
  gap: 15px;
}

.teacher-info {
  display: flex;
  align-items: center;
  gap: 15px;
}

.teacher-avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: #9ca3af;
}

.teacher-details h4 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #1f2937;
}

.teacher-details p {
  font-size: 0.9rem;
  color: #6b7280;
}

.comment-tags {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.tag {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.tag.excellent {
  background: #dcfce7;
  color: #166534;
}

.tag.improvement {
  background: #dbeafe;
  color: #1e40af;
}

.tag.needs-work {
  background: #ffedd5;
  color: #9a3412;
}

.tag.recommendation {
  background: #f0f9ff;
  color: #0369a1;
}

.comment-content {
  margin-bottom: 15px;
  padding: 15px;
  background: #f9fafb;
  border-radius: 8px;
}

.comment-content p {
  color: #1f2937;
  line-height: 1.5;
}

.comment-actions {
  display: flex;
  gap: 10px;
}

.comment-actions button {
  display: flex;
  align-items: center;
  gap: 8px;
}

.no-comments {
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.no-comments i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
}

/* Grade badges */
.grade-badge {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 600;
}

.grade-a {
  background: #dcfce7;
  color: #166534;
}

.grade-b {
  background: #dbeafe;
  color: #1e40af;
}

.grade-c {
  background: #ffedd5;
  color: #9a3412;
}

.grade-d {
  background: #fee2e2;
  color: #991b1b;
}

.grade-f {
  background: #fecaca;
  color: #7f1d1d;
}

/* Progress fill colors */
.progress-fill.grade-a {
  background: #22c55e;
}

.progress-fill.grade-b {
  background: #3b82f6;
}

.progress-fill.grade-c {
  background: #f97316;
}

.progress-fill.grade-d {
  background: #ef4444;
}

.progress-fill.grade-f {
  background: #dc2626;
}

/* Dark mode support */
.dark .parent-reports {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .card-header h3,
.dark .subject-header h4,
.dark .exam-header h4,
.teacher-details h4 {
  color: #f9fafb;
}

.dark .page-header p,
.dark .detail-item span:first-child,
.teacher-details p {
  color: #d1d5db;
}

.dark .reports-controls,
.dark .performance-card,
.dark .performance-chart,
.dark .subjects-tab,
.dark .assignments-tab,
.dark .exams-tab,
.dark .comments-tab {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .reports-tabs {
  background: #374151;
}

.dark .reports-tabs button.active {
  background: #1f2937;
}

.dark .subject-card,
.dark .exam-card,
.dark .comment-item {
  border-color: #374151;
}

.dark .subject-card:hover,
.dark .exam-card:hover {
  border-color: #4b5563;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
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

.dark .comment-content {
  background: #111827;
}

.dark .no-assignments,
.dark .no-comments {
  background: #1f2937;
  color: #9ca3af;
}

.dark .no-assignments i,
.dark .no-comments i {
  color: #4b5563;
}

.dark .teacher-avatar {
  background: #374151;
  color: #9ca3af;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .parent-reports {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .reports-controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .reports-overview {
    grid-template-columns: 1fr;
  }
  
  .table-row {
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr;
  }
  
  .comment-header {
    flex-direction: column;
  }
  
  .comment-actions {
    flex-wrap: wrap;
  }
}
</style>