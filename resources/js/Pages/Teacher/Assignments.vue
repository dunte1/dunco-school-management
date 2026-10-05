<template>
  <AppLayout>
    <div class="teacher-assignments">
      <div class="page-header">
        <h1>Assignment Management</h1>
        <p>Create, manage, and grade student assignments</p>
      </div>
      
      <div class="assignments-controls">
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
          <button class="btn-primary" @click="createAssignment">
            <i class="fas fa-plus"></i>
            Create Assignment
          </button>
        </div>
      </div>
      
      <div class="assignments-overview">
        <div class="stats-cards">
          <div class="stat-card">
            <div class="stat-icon total">
              <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ assignments.length }}</div>
              <div class="stat-label">Total Assignments</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon pending">
              <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ pendingAssignments.length }}</div>
              <div class="stat-label">Pending Submissions</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon graded">
              <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ gradedAssignments.length }}</div>
              <div class="stat-label">Graded</div>
            </div>
          </div>
          
          <div class="stat-card">
            <div class="stat-icon overdue">
              <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-info">
              <div class="stat-value">{{ overdueAssignments.length }}</div>
              <div class="stat-label">Overdue</div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="assignments-tabs">
        <button 
          :class="{ active: activeTab === 'active' }"
          @click="activeTab = 'active'"
        >
          Active Assignments
        </button>
        <button 
          :class="{ active: activeTab === 'past' }"
          @click="activeTab = 'past'"
        >
          Past Assignments
        </button>
        <button 
          :class="{ active: activeTab === 'drafts' }"
          @click="activeTab = 'drafts'"
        >
          Drafts
        </button>
      </div>
      
      <!-- Active Assignments Tab -->
      <div v-if="activeTab === 'active'" class="assignments-tab">
        <div class="assignments-grid">
          <div 
            v-for="assignment in activeAssignments" 
            :key="assignment.id"
            class="assignment-card"
          >
            <div class="assignment-header">
              <h3>{{ assignment.title }}</h3>
              <span class="assignment-class">{{ assignment.class }}</span>
            </div>
            
            <div class="assignment-details">
              <p class="assignment-description">{{ assignment.description }}</p>
              
              <div class="assignment-meta">
                <div class="meta-item">
                  <i class="fas fa-calendar"></i>
                  <span>Due: {{ formatDate(assignment.dueDate) }}</span>
                </div>
                <div class="meta-item">
                  <i class="fas fa-file"></i>
                  <span>{{ assignment.submissions }}/{{ assignment.totalStudents }} submissions</span>
                </div>
                <div class="meta-item">
                  <i class="fas fa-star"></i>
                  <span>{{ assignment.points }} points</span>
                </div>
              </div>
            </div>
            
            <div class="assignment-progress">
              <div class="progress-bar">
                <div 
                  class="progress-fill" 
                  :style="{ width: assignment.progress + '%' }"
                ></div>
              </div>
              <div class="progress-text">{{ assignment.progress }}% submitted</div>
            </div>
            
            <div class="assignment-actions">
              <button class="btn-secondary" @click="viewSubmissions(assignment)">
                <i class="fas fa-eye"></i>
                View Submissions
              </button>
              <button class="btn-primary" @click="gradeAssignment(assignment)">
                <i class="fas fa-edit"></i>
                Grade
              </button>
            </div>
          </div>
          
          <div v-if="activeAssignments.length === 0" class="no-assignments">
            <i class="fas fa-tasks"></i>
            <p>No active assignments</p>
          </div>
        </div>
      </div>
      
      <!-- Past Assignments Tab -->
      <div v-else-if="activeTab === 'past'" class="assignments-tab">
        <div class="assignments-grid">
          <div 
            v-for="assignment in pastAssignments" 
            :key="assignment.id"
            class="assignment-card past"
          >
            <div class="assignment-header">
              <h3>{{ assignment.title }}</h3>
              <span class="assignment-class">{{ assignment.class }}</span>
            </div>
            
            <div class="assignment-details">
              <p class="assignment-description">{{ assignment.description }}</p>
              
              <div class="assignment-meta">
                <div class="meta-item">
                  <i class="fas fa-calendar"></i>
                  <span>Due: {{ formatDate(assignment.dueDate) }}</span>
                </div>
                <div class="meta-item">
                  <i class="fas fa-file"></i>
                  <span>{{ assignment.submissions }}/{{ assignment.totalStudents }} submissions</span>
                </div>
                <div class="meta-item">
                  <i class="fas fa-star"></i>
                  <span>{{ assignment.points }} points</span>
                </div>
              </div>
            </div>
            
            <div class="assignment-actions">
              <button class="btn-secondary" @click="viewSubmissions(assignment)">
                <i class="fas fa-eye"></i>
                View Submissions
              </button>
              <button class="btn-secondary" @click="viewGrades(assignment)">
                <i class="fas fa-chart-bar"></i>
                View Grades
              </button>
            </div>
          </div>
          
          <div v-if="pastAssignments.length === 0" class="no-assignments">
            <i class="fas fa-history"></i>
            <p>No past assignments</p>
          </div>
        </div>
      </div>
      
      <!-- Drafts Tab -->
      <div v-else class="assignments-tab">
        <div class="assignments-grid">
          <div 
            v-for="assignment in draftAssignments" 
            :key="assignment.id"
            class="assignment-card draft"
          >
            <div class="assignment-header">
              <h3>{{ assignment.title }}</h3>
              <span class="assignment-class">{{ assignment.class }}</span>
            </div>
            
            <div class="assignment-details">
              <p class="assignment-description">{{ assignment.description }}</p>
              
              <div class="assignment-meta">
                <div class="meta-item">
                  <i class="fas fa-calendar"></i>
                  <span v-if="assignment.dueDate">Due: {{ formatDate(assignment.dueDate) }}</span>
                  <span v-else>No due date</span>
                </div>
                <div class="meta-item">
                  <i class="fas fa-star"></i>
                  <span>{{ assignment.points }} points</span>
                </div>
              </div>
            </div>
            
            <div class="assignment-actions">
              <button class="btn-secondary" @click="editDraft(assignment)">
                <i class="fas fa-edit"></i>
                Edit
              </button>
              <button class="btn-primary" @click="publishDraft(assignment)">
                <i class="fas fa-paper-plane"></i>
                Publish
              </button>
            </div>
          </div>
          
          <div v-if="draftAssignments.length === 0" class="no-assignments">
            <i class="fas fa-file-alt"></i>
            <p>No draft assignments</p>
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
const activeTab = ref('active');
const selectedClass = ref('');

// Mock data - in a real app, this would come from API
const classes = ref([
  { id: 1, name: 'Mathematics 101', studentCount: 25 },
  { id: 2, name: 'Physics 201', studentCount: 22 },
  { id: 3, name: 'Chemistry 101', studentCount: 24 }
]);

const assignments = ref([
  {
    id: 1,
    title: 'Algebra Basics',
    class: 'Mathematics 101',
    description: 'Solve the algebra problems in the attached worksheet',
    dueDate: '2025-09-25',
    points: 20,
    submissions: 18,
    totalStudents: 25,
    progress: 72,
    status: 'active'
  },
  {
    id: 2,
    title: 'Physics Lab Report',
    class: 'Physics 201',
    description: 'Write a detailed report on the momentum experiment',
    dueDate: '2025-09-30',
    points: 30,
    submissions: 15,
    totalStudents: 22,
    progress: 68,
    status: 'active'
  },
  {
    id: 3,
    title: 'Chemistry Equations',
    class: 'Chemistry 101',
    description: 'Balance the chemical equations provided in class',
    dueDate: '2025-09-20',
    points: 15,
    submissions: 24,
    totalStudents: 24,
    progress: 100,
    status: 'past'
  },
  {
    id: 4,
    title: 'Draft Assignment',
    class: 'Mathematics 101',
    description: 'This is a draft assignment that has not been published yet',
    dueDate: null,
    points: 25,
    submissions: 0,
    totalStudents: 0,
    progress: 0,
    status: 'draft'
  }
]);

// Computed properties
const activeAssignments = computed(() => {
  return assignments.value.filter(a => a.status === 'active');
});

const pastAssignments = computed(() => {
  return assignments.value.filter(a => a.status === 'past');
});

const draftAssignments = computed(() => {
  return assignments.value.filter(a => a.status === 'draft');
});

const pendingAssignments = computed(() => {
  return assignments.value.filter(a => 
    a.status === 'active' && a.submissions < a.totalStudents
  );
});

const gradedAssignments = computed(() => {
  return assignments.value.filter(a => 
    a.status === 'past' && a.submissions > 0
  );
});

const overdueAssignments = computed(() => {
  const today = new Date();
  return assignments.value.filter(a => {
    if (a.status !== 'active' || !a.dueDate) return false;
    return new Date(a.dueDate) < today;
  });
});

// Methods
const createAssignment = () => {
  alert('Creating new assignment');
};

const viewSubmissions = (assignment) => {
  alert(`Viewing submissions for: ${assignment.title}`);
};

const gradeAssignment = (assignment) => {
  alert(`Grading assignment: ${assignment.title}`);
};

const viewGrades = (assignment) => {
  alert(`Viewing grades for: ${assignment.title}`);
};

const editDraft = (assignment) => {
  alert(`Editing draft: ${assignment.title}`);
};

const publishDraft = (assignment) => {
  alert(`Publishing draft: ${assignment.title}`);
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
.teacher-assignments {
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

.assignments-controls {
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

.assignments-overview {
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

.stat-icon.pending {
  background: #ffedd5;
  color: #f97316;
}

.stat-icon.graded {
  background: #dcfce7;
  color: #22c55e;
}

.stat-icon.overdue {
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

.assignments-tabs {
  display: flex;
  background: #f3f4f6;
  border-radius: 8px;
  padding: 4px;
  margin-bottom: 30px;
}

.assignments-tabs button {
  flex: 1;
  background: none;
  border: none;
  padding: 12px 16px;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.assignments-tabs button.active {
  background: white;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Assignments Tab */
.assignments-tab {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
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

.assignment-card.past {
  border-left: 4px solid #22c55e;
}

.assignment-card.draft {
  border-left: 4px solid #f59e0b;
}

.assignment-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 15px;
}

.assignment-header h3 {
  font-size: 1.2rem;
  font-weight: 600;
  color: #1f2937;
}

.assignment-class {
  background: #dbeafe;
  color: #3b82f6;
  padding: 4px 8px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.assignment-description {
  color: #6b7280;
  margin-bottom: 20px;
  line-height: 1.5;
}

.assignment-meta {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 20px;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9rem;
  color: #6b7280;
}

.assignment-progress {
  margin-bottom: 20px;
}

.progress-bar {
  height: 8px;
  background: #e5e7eb;
  border-radius: 4px;
  overflow: hidden;
  margin-bottom: 8px;
}

.progress-fill {
  height: 100%;
  background: #3b82f6;
  border-radius: 4px;
}

.progress-text {
  font-size: 0.85rem;
  color: #6b7280;
  text-align: right;
}

.assignment-actions {
  display: flex;
  gap: 10px;
}

.assignment-actions button {
  flex: 1;
  padding: 10px;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
}

.no-assignments {
  grid-column: 1 / -1;
  text-align: center;
  padding: 40px 20px;
  color: #6b7280;
}

.no-assignments i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
}

/* Dark mode support */
.dark .teacher-assignments {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .stat-value,
.dark .assignment-header h3 {
  color: #f9fafb;
}

.dark .page-header p,
.dark .stat-label,
.dark .class-selector label,
.dark .assignment-description,
.dark .meta-item,
.progress-text {
  color: #d1d5db;
}

.dark .stat-card,
.dark .assignments-tab {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .assignments-tabs {
  background: #374151;
}

.dark .assignments-tabs button.active {
  background: #1f2937;
}

.dark .class-selector select {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
}

.dark .assignment-card {
  border-color: #374151;
  background: #1f2937;
}

.dark .assignment-card:hover {
  border-color: #4b5563;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.dark .assignment-card.past {
  border-left-color: #22c55e;
}

.dark .assignment-card.draft {
  border-left-color: #f59e0b;
}

.dark .assignment-class {
  background: #1e3a8a;
  color: #a5b4fc;
}

.dark .progress-bar {
  background: #374151;
}

.dark .no-assignments {
  color: #9ca3af;
}

.dark .no-assignments i {
  color: #4b5563;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .teacher-assignments {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .assignments-controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .stats-cards {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .assignment-actions {
    flex-direction: column;
  }
}
</style>