<template>
  <AppLayout>
    <div class="teacher-exams">
      <div class="page-header">
        <h1>Exam Management</h1>
        <p>Create, schedule, and manage exams for your classes</p>
      </div>
      
      <div class="exams-controls">
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
        
        <div class="actions">
          <button class="btn-primary" @click="createExam">
            <i class="fas fa-plus"></i>
            Create Exam
          </button>
        </div>
      </div>
      
      <div class="exams-overview">
        <div class="stats-cards">
          <div class="stat-card">
            <div class="stat-icon total">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ exams.length }}</div>
              <div class="stat-label">Total Exams</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon upcoming">
              <i class="fas fa-calendar-plus"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ upcomingExams.length }}</div>
              <div class="stat-label">Upcoming</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon ongoing">
              <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ ongoingExams.length }}</div>
              <div class="stat-label">Ongoing</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon completed">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ completedExams.length }}</div>
              <div class="stat-label">Completed</div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="exams-tabs">
        <button 
          :class="{ active: activeTab === 'upcoming' }"
          @click="activeTab = 'upcoming'"
        >
          Upcoming Exams
        </button>
        <button 
          :class="{ active: activeTab === 'ongoing' }"
          @click="activeTab = 'ongoing'"
        >
          Ongoing Exams
        </button>
        <button 
          :class="{ active: activeTab === 'past' }"
          @click="activeTab = 'past'"
        >
          Past Exams
        </button>
        <button 
          :class="{ active: activeTab === 'results' }"
          @click="activeTab = 'results'"
        >
          Results
        </button>
      </div>
      
      <!-- Upcoming Exams Tab -->
      <div v-if="activeTab === 'upcoming'" class="exams-tab">
        <div class="exams-grid">
          <div 
            v-for="exam in upcomingExams" 
            :key="exam.id"
            class="exam-card"
          >
            <div class="exam-header">
              <h3>{{ exam.title }}</h3>
              <span class="exam-class">{{ exam.class }}</span>
            </div>
            
            <div class="exam-details">
              <div class="detail-item">
                <i class="fas fa-calendar"></i>
                <span>{{ formatDate(exam.date) }}</span>
              </div>
              <div class="detail-item">
                <i class="fas fa-clock"></i>
                <span>{{ exam.startTime }} - {{ exam.endTime }}</span>
              </div>
              <div class="detail-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ exam.location }}</span>
              </div>
              <div class="detail-item">
                <i class="fas fa-file-alt"></i>
                <span>{{ exam.type }}</span>
              </div>
            </div>
            
            <div class="exam-footer">
              <div class="days-remaining">
                <span v-if="getDaysUntilExam(exam.date) > 0">
                  {{ getDaysUntilExam(exam.date) }} days remaining
                </span>
                <span v-else-if="getDaysUntilExam(exam.date) === 0">
                  Today
                </span>
                <span v-else>
                  Past exam
                </span>
              </div>
              <div class="exam-actions">
                <button class="btn-secondary" @click="viewExam(exam)">
                  <i class="fas fa-eye"></i>
                  View
                </button>
                <button class="btn-primary" @click="editExam(exam)">
                  <i class="fas fa-edit"></i>
                  Edit
                </button>
              </div>
            </div>
          </div>
          
          <div v-if="upcomingExams.length === 0" class="no-exams">
            <i class="fas fa-calendar-plus"></i>
            <p>No upcoming exams scheduled</p>
          </div>
        </div>
      </div>
      
      <!-- Ongoing Exams Tab -->
      <div v-else-if="activeTab === 'ongoing'" class="exams-tab">
        <div class="exams-grid">
          <div 
            v-for="exam in ongoingExams" 
            :key="exam.id"
            class="exam-card ongoing"
          >
            <div class="exam-header">
              <h3>{{ exam.title }}</h3>
              <span class="exam-class">{{ exam.class }}</span>
            </div>
            
            <div class="exam-details">
              <div class="detail-item">
                <i class="fas fa-calendar"></i>
                <span>{{ formatDate(exam.date) }}</span>
              </div>
              <div class="detail-item">
                <i class="fas fa-clock"></i>
                <span>{{ exam.startTime }} - {{ exam.endTime }}</span>
              </div>
              <div class="detail-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ exam.location }}</span>
              </div>
              <div class="detail-item">
                <i class="fas fa-file-alt"></i>
                <span>{{ exam.type }}</span>
              </div>
            </div>
            
            <div class="exam-footer">
              <div class="time-remaining">
                <span>Ends in {{ getTimeRemaining(exam.endTime) }}</span>
              </div>
              <div class="exam-actions">
                <button class="btn-secondary" @click="viewExam(exam)">
                  <i class="fas fa-eye"></i>
                  View
                </button>
                <button class="btn-primary" @click="manageExam(exam)">
                  <i class="fas fa-cog"></i>
                  Manage
                </button>
              </div>
            </div>
          </div>
          
          <div v-if="ongoingExams.length === 0" class="no-exams">
            <i class="fas fa-clock"></i>
            <p>No exams currently ongoing</p>
          </div>
        </div>
      </div>
      
      <!-- Past Exams Tab -->
      <div v-else-if="activeTab === 'past'" class="exams-tab">
        <div class="exams-grid">
          <div 
            v-for="exam in pastExams" 
            :key="exam.id"
            class="exam-card past"
          >
            <div class="exam-header">
              <h3>{{ exam.title }}</h3>
              <span class="exam-class">{{ exam.class }}</span>
            </div>
            
            <div class="exam-details">
              <div class="detail-item">
                <i class="fas fa-calendar"></i>
                <span>{{ formatDate(exam.date) }}</span>
              </div>
              <div class="detail-item">
                <i class="fas fa-clock"></i>
                <span>{{ exam.startTime }} - {{ exam.endTime }}</span>
              </div>
              <div class="detail-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ exam.location }}</span>
              </div>
              <div class="detail-item">
                <i class="fas fa-file-alt"></i>
                <span>{{ exam.type }}</span>
              </div>
            </div>
            
            <div class="exam-footer">
              <div class="exam-status">
                <span>Completed</span>
              </div>
              <div class="exam-actions">
                <button class="btn-secondary" @click="viewResults(exam)">
                  <i class="fas fa-chart-bar"></i>
                  Results
                </button>
                <button class="btn-secondary" @click="downloadReport(exam)">
                  <i class="fas fa-download"></i>
                  Report
                </button>
              </div>
            </div>
          </div>
          
          <div v-if="pastExams.length === 0" class="no-exams">
            <i class="fas fa-history"></i>
            <p>No past exams</p>
          </div>
        </div>
      </div>
      
      <!-- Results Tab -->
      <div v-else class="results-tab">
        <div class="results-controls">
          <div class="filter-group">
            <label for="exam-filter">Select Exam:</label>
            <select id="exam-filter" v-model="selectedExam">
              <option value="">All Exams</option>
              <option 
                v-for="exam in completedExams" 
                :key="exam.id" 
                :value="exam.id"
              >
                {{ exam.title }} - {{ exam.class }}
              </option>
            </select>
          </div>
          
          <button class="btn-primary" @click="publishResults">
            <i class="fas fa-paper-plane"></i>
            Publish Results
          </button>
        </div>
        
        <div class="results-summary">
          <div class="summary-card">
            <div class="summary-icon">
              <i class="fas fa-users"></i>
            </div>
            <div class="summary-info">
              <div class="summary-value">{{ examResults.totalStudents }}</div>
              <div class="summary-label">Students</div>
            </div>
          </div>
          
          <div class="summary-card">
            <div class="summary-icon">
              <i class="fas fa-chart-line"></i>
            </div>
            <div class="summary-info">
              <div class="summary-value">{{ examResults.averageScore }}%</div>
              <div class="summary-label">Average Score</div>
            </div>
          </div>
          
          <div class="summary-card">
            <div class="summary-icon">
              <i class="fas fa-star"></i>
            </div>
            <div class="summary-info">
              <div class="summary-value">{{ examResults.highestScore }}%</div>
              <div class="summary-label">Highest Score</div>
            </div>
          </div>
          
          <div class="summary-card">
            <div class="summary-icon">
              <i class="fas fa-arrow-down"></i>
            </div>
            <div class="summary-info">
              <div class="summary-value">{{ examResults.lowestScore }}%</div>
              <div class="summary-label">Lowest Score</div>
            </div>
          </div>
        </div>
        
        <div class="results-table">
          <div class="table-header">
            <div class="header-cell">Student</div>
            <div class="header-cell">Score</div>
            <div class="header-cell">Grade</div>
            <div class="header-cell">Status</div>
            <div class="header-cell">Actions</div>
          </div>
          
          <div 
            v-for="result in filteredResults" 
            :key="result.id"
            class="table-row"
          >
            <div class="table-cell">
              <div class="student-info">
                <div class="student-avatar">
                  <i class="fas fa-user"></i>
                </div>
                <div>
                  <div class="student-name">{{ result.student.name }}</div>
                  <div class="student-id">{{ result.student.id }}</div>
                </div>
              </div>
            </div>
            <div class="table-cell">{{ result.score }}%</div>
            <div class="table-cell">
              <span class="grade-badge" :class="getGradeClass(result.score)">
                {{ getGrade(result.score) }}
              </span>
            </div>
            <div class="table-cell">
              <span class="status-badge" :class="result.status">
                {{ result.status }}
              </span>
            </div>
            <div class="table-cell">
              <button class="btn-icon" @click="viewResultDetails(result)">
                <i class="fas fa-eye"></i>
              </button>
            </div>
          </div>
          
          <div v-if="filteredResults.length === 0" class="no-results">
            <i class="fas fa-chart-bar"></i>
            <p>No results available</p>
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
const activeTab = ref('upcoming');
const selectedClass = ref('');
const selectedExam = ref('');

// Mock data - in a real app, this would come from API
const classes = ref([
  { id: 1, name: 'Mathematics 101', studentCount: 25 },
  { id: 2, name: 'Physics 201', studentCount: 22 },
  { id: 3, name: 'Chemistry 101', studentCount: 24 }
]);

const exams = ref([
  {
    id: 1,
    title: 'Midterm Exam',
    class: 'Mathematics 101',
    date: '2025-09-25',
    startTime: '09:00',
    endTime: '11:00',
    location: 'Room 101',
    type: 'Written',
    status: 'upcoming'
  },
  {
    id: 2,
    title: 'Lab Practical',
    class: 'Physics 201',
    date: '2025-09-20',
    startTime: '14:00',
    endTime: '16:00',
    location: 'Lab 2',
    type: 'Practical',
    status: 'ongoing'
  },
  {
    id: 3,
    title: 'Final Exam',
    class: 'Chemistry 101',
    date: '2025-09-15',
    startTime: '10:00',
    endTime: '12:00',
    location: 'Room 205',
    type: 'Written',
    status: 'completed'
  }
]);

const examResults = ref({
  totalStudents: 25,
  averageScore: 78,
  highestScore: 95,
  lowestScore: 42,
  results: [
    {
      id: 1,
      student: { id: 'STU001', name: 'John Smith' },
      score: 85,
      status: 'graded'
    },
    {
      id: 2,
      student: { id: 'STU002', name: 'Emma Johnson' },
      score: 92,
      status: 'graded'
    },
    {
      id: 3,
      student: { id: 'STU003', name: 'Michael Brown' },
      score: 76,
      status: 'graded'
    }
  ]
});

// Computed properties
const upcomingExams = computed(() => {
  return exams.value.filter(e => e.status === 'upcoming');
});

const ongoingExams = computed(() => {
  return exams.value.filter(e => e.status === 'ongoing');
});

const pastExams = computed(() => {
  return exams.value.filter(e => e.status === 'completed');
});

const completedExams = computed(() => {
  return exams.value.filter(e => e.status === 'completed');
});

const filteredResults = computed(() => {
  if (!selectedExam.value) {
    return examResults.value.results;
  }
  // In a real app, we would filter by selected exam
  return examResults.value.results;
});

// Methods
const createExam = () => {
  alert('Creating new exam');
};

const viewExam = (exam) => {
  alert(`Viewing exam: ${exam.title}`);
};

const editExam = (exam) => {
  alert(`Editing exam: ${exam.title}`);
};

const manageExam = (exam) => {
  alert(`Managing exam: ${exam.title}`);
};

const viewResults = (exam) => {
  alert(`Viewing results for: ${exam.title}`);
};

const downloadReport = (exam) => {
  alert(`Downloading report for: ${exam.title}`);
};

const publishResults = () => {
  alert('Publishing exam results');
};

const viewResultDetails = (result) => {
  alert(`Viewing details for: ${result.student.name}`);
};

const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const getDaysUntilExam = (dateString) => {
  const today = new Date();
  const examDate = new Date(dateString);
  const diffTime = examDate - today;
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  return diffDays;
};

const getTimeRemaining = (endTime) => {
  // In a real app, this would calculate actual time remaining
  return '1h 30m';
};

const getGrade = (score) => {
  if (score >= 90) return 'A';
  if (score >= 80) return 'B';
  if (score >= 70) return 'C';
  if (score >= 60) return 'D';
  return 'F';
};

const getGradeClass = (score) => {
  if (score >= 90) return 'a';
  if (score >= 80) return 'b';
  if (score >= 70) return 'c';
  if (score >= 60) return 'd';
  return 'f';
};

// Lifecycle
onMounted(() => {
  // In a real app, we would fetch data from the API here
});
</script>

<style scoped>
.teacher-exams {
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

.exams-controls {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  gap: 20px;
  margin-bottom: 30px;
  flex-wrap: wrap;
}

.class-selector {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.class-selector label {
  font-weight: 500;
  color: #1f2937;
}

.class-selector select {
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
  min-width: 200px;
}

.actions button {
  display: flex;
  align-items: center;
  gap: 8px;
}

.exams-overview {
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

.stat-icon.total {
  background: #dbeafe;
  color: #3b82f6;
}

.stat-icon.upcoming {
  background: #ffedd5;
  color: #f97316;
}

.stat-icon.ongoing {
  background: #ffedd5;
  color: #f97316;
}

.stat-icon.completed {
  background: #dcfce7;
  color: #22c55e;
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

.exams-tabs {
  display: flex;
  background: #f3f4f6;
  border-radius: 8px;
  padding: 4px;
  margin-bottom: 30px;
  flex-wrap: wrap;
}

.exams-tabs button {
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

.exams-tabs button.active {
  background: white;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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

.exam-card.ongoing {
  border-left: 4px solid #f97316;
}

.exam-card.past {
  border-left: 4px solid #22c55e;
}

.exam-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
}

.exam-header h3 {
  font-size: 1.2rem;
  font-weight: 600;
  color: #1f2937;
}

.exam-class {
  background: #dbeafe;
  color: #3b82f6;
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.exam-details {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 20px;
}

.detail-item {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 0.9rem;
  color: #6b7280;
}

.exam-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.days-remaining,
.time-remaining,
.exam-status {
  font-size: 0.9rem;
  font-weight: 500;
  color: #6b7280;
}

.exam-actions {
  display: flex;
  gap: 10px;
}

.exam-actions button {
  padding: 8px 12px;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  gap: 5px;
}

.no-exams {
  grid-column: 1 / -1;
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.no-exams i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
}

/* Results Tab */
.results-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.results-controls {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  gap: 20px;
  margin-bottom: 30px;
  flex-wrap: wrap;
}

.results-controls .filter-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.results-controls .filter-group label {
  font-weight: 500;
  color: #1f2937;
}

.results-controls .filter-group select {
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
  min-width: 200px;
}

.results-controls button {
  display: flex;
  align-items: center;
  gap: 8px;
}

.results-summary {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.summary-card {
  display: flex;
  align-items: center;
  padding: 20px;
  background: #f9fafb;
  border-radius: 12px;
}

.summary-icon {
  width: 50px;
  height: 50px;
  border-radius: 12px;
  background: #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 15px;
  font-size: 1.5rem;
  color: #6b7280;
}

.summary-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
}

.summary-label {
  font-size: 0.9rem;
  color: #6b7280;
}

.results-table {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
}

.table-header {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr;
  background: #f9fafb;
  font-weight: 600;
  padding: 12px 15px;
}

.table-row {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 1fr 0.5fr;
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

.grade-badge {
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.grade-badge.a {
  background: #dcfce7;
  color: #166534;
}

.grade-badge.b {
  background: #dbeafe;
  color: #1e40af;
}

.grade-badge.c {
  background: #ffedd5;
  color: #9a3412;
}

.grade-badge.d {
  background: #fff4ed;
  color: #c2410c;
}

.grade-badge.f {
  background: #fee2e2;
  color: #991b1b;
}

.status-badge {
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.status-badge.graded {
  background: #dcfce7;
  color: #166534;
}

.status-badge.pending {
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

.no-results {
  grid-column: 1 / -1;
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.no-results i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
}

/* Dark mode support */
.dark .teacher-exams {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .stat-value,
.dark .exam-header h3,
.dark .student-name,
.dark .summary-value {
  color: #f9fafb;
}

.dark .page-header p,
.dark .stat-label,
.dark .class-selector label,
.dark .detail-item,
.days-remaining,
.time-remaining,
.exam-status,
.student-id,
.summary-label {
  color: #d1d5db;
}

.dark .stat-card,
.dark .exams-tab,
.dark .results-tab,
.dark .summary-card {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .exams-tabs {
  background: #374151;
}

.dark .exams-tabs button.active {
  background: #1f2937;
}

.dark .class-selector select,
.dark .results-controls .filter-group select {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
}

.dark .exam-card {
  border-color: #374151;
  background: #1f2937;
}

.dark .exam-card:hover {
  border-color: #4b5563;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.dark .exam-card.ongoing {
  border-left-color: #f97316;
}

.dark .exam-card.past {
  border-left-color: #22c55e;
}

.dark .exam-class {
  background: #1e3a8a;
  color: #a5b4fc;
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

.dark .no-exams,
.dark .no-results {
  color: #9ca3af;
}

.dark .no-exams i,
.dark .no-results i {
  color: #4b5563;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .teacher-exams {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .exams-controls,
  .results-controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .stats-cards,
  .results-summary {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .table-header,
  .table-row {
    grid-template-columns: 1fr;
    gap: 10px;
  }
  
  .exam-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .exam-actions {
    width: 100%;
  }
}
</style>