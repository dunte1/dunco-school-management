<template>
  <AppLayout>
    <div class="admin-exams">
      <div class="page-header">
        <h1>Exam Management</h1>
        <p>Manage exams across all schools in your system</p>
      </div>
      
      <div class="exams-controls">
        <button class="btn-primary" @click="openAddExamModal">
          <i class="fas fa-plus"></i>
          Schedule New Exam
        </button>
        
        <div class="search-controls">
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input 
              type="text" 
              placeholder="Search exams..." 
              v-model="searchQuery"
            >
          </div>
          
          <select v-model="schoolFilter">
            <option value="">All Schools</option>
            <option v-for="school in schools" :key="school.id" :value="school.id">
              {{ school.name }}
            </option>
          </select>
          
          <select v-model="statusFilter">
            <option value="">All Status</option>
            <option value="scheduled">Scheduled</option>
            <option value="ongoing">Ongoing</option>
            <option value="completed">Completed</option>
          </select>
        </div>
      </div>
      
      <div class="exams-grid">
        <div 
          v-for="exam in filteredExams" 
          :key="exam.id"
          class="exam-card"
          :class="exam.status"
        >
          <div class="exam-header">
            <h3>{{ exam.name }}</h3>
            <span class="exam-status" :class="exam.status">
              {{ exam.status }}
            </span>
          </div>
          
          <div class="exam-details">
            <div class="detail-item">
              <i class="fas fa-school"></i>
              <span>{{ exam.school }}</span>
            </div>
            
            <div class="detail-item">
              <i class="fas fa-graduation-cap"></i>
              <span>{{ exam.grade }} - {{ exam.subject }}</span>
            </div>
            
            <div class="detail-item">
              <i class="fas fa-calendar"></i>
              <span>{{ formatDate(exam.date) }}</span>
            </div>
            
            <div class="detail-item">
              <i class="fas fa-clock"></i>
              <span>{{ exam.startTime }} - {{ exam.endTime }}</span>
            </div>
            
            <div class="detail-item">
              <i class="fas fa-chalkboard"></i>
              <span>{{ exam.room }}</span>
            </div>
            
            <div class="detail-item">
              <i class="fas fa-users"></i>
              <span>{{ exam.students }} students</span>
            </div>
          </div>
          
          <div class="exam-actions">
            <button class="btn-secondary" @click="viewExam(exam)">
              <i class="fas fa-eye"></i>
              View
            </button>
            <button class="btn-secondary" @click="editExam(exam)">
              <i class="fas fa-edit"></i>
              Edit
            </button>
            <button 
              v-if="exam.status === 'scheduled'"
              class="btn-secondary" 
              @click="startExam(exam)"
            >
              <i class="fas fa-play"></i>
              Start Exam
            </button>
            <button 
              v-else-if="exam.status === 'ongoing'"
              class="btn-secondary" 
              @click="endExam(exam)"
            >
              <i class="fas fa-stop"></i>
              End Exam
            </button>
            <button 
              v-else
              class="btn-secondary" 
              @click="viewResults(exam)"
            >
              <i class="fas fa-chart-bar"></i>
              Results
            </button>
          </div>
        </div>
      </div>
      
      <div v-if="filteredExams.length === 0" class="no-exams">
        <i class="fas fa-file-alt"></i>
        <p>No exams found</p>
      </div>
      
      <!-- Add/Edit Exam Modal -->
      <div v-if="showExamModal" class="modal-overlay" @click="closeExamModal">
        <div class="modal-content" @click.stop>
          <div class="modal-header">
            <h3>{{ editingExam ? 'Edit Exam' : 'Schedule New Exam' }}</h3>
            <button class="modal-close" @click="closeExamModal">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <div class="modal-body">
            <form @submit.prevent="saveExam">
              <div class="form-group">
                <label for="examName">Exam Name</label>
                <input 
                  type="text" 
                  id="examName" 
                  v-model="examForm.name" 
                  required
                >
              </div>
              
              <div class="form-group">
                <label for="examSchool">School</label>
                <select id="examSchool" v-model="examForm.school" required>
                  <option value="">Select School</option>
                  <option v-for="school in schools" :key="school.id" :value="school.name">
                    {{ school.name }}
                  </option>
                </select>
              </div>
              
              <div class="form-row">
                <div class="form-group">
                  <label for="examGrade">Grade</label>
                  <input 
                    type="text" 
                    id="examGrade" 
                    v-model="examForm.grade" 
                    required
                  >
                </div>
                
                <div class="form-group">
                  <label for="examSubject">Subject</label>
                  <input 
                    type="text" 
                    id="examSubject" 
                    v-model="examForm.subject" 
                    required
                  >
                </div>
              </div>
              
              <div class="form-group">
                <label for="examDate">Date</label>
                <input 
                  type="date" 
                  id="examDate" 
                  v-model="examForm.date" 
                  required
                >
              </div>
              
              <div class="form-row">
                <div class="form-group">
                  <label for="examStartTime">Start Time</label>
                  <input 
                    type="time" 
                    id="examStartTime" 
                    v-model="examForm.startTime" 
                    required
                  >
                </div>
                
                <div class="form-group">
                  <label for="examEndTime">End Time</label>
                  <input 
                    type="time" 
                    id="examEndTime" 
                    v-model="examForm.endTime" 
                    required
                  >
                </div>
              </div>
              
              <div class="form-group">
                <label for="examRoom">Room</label>
                <input 
                  type="text" 
                  id="examRoom" 
                  v-model="examForm.room" 
                  required
                >
              </div>
              
              <div class="form-group">
                <label for="examStatus">Status</label>
                <select id="examStatus" v-model="examForm.status">
                  <option value="scheduled">Scheduled</option>
                  <option value="ongoing">Ongoing</option>
                  <option value="completed">Completed</option>
                </select>
              </div>
              
              <div class="form-actions">
                <button type="button" class="btn-secondary" @click="closeExamModal">
                  Cancel
                </button>
                <button type="submit" class="btn-primary">
                  {{ editingExam ? 'Update Exam' : 'Schedule Exam' }}
                </button>
              </div>
            </form>
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
const searchQuery = ref('');
const schoolFilter = ref('');
const statusFilter = ref('');
const showExamModal = ref(false);
const editingExam = ref(null);

const examForm = ref({
  name: '',
  school: '',
  grade: '',
  subject: '',
  date: '',
  startTime: '',
  endTime: '',
  room: '',
  status: 'scheduled'
});

// Mock data - in a real app, this would come from API
const schools = ref([
  { id: 1, name: 'Greenwood High School' },
  { id: 2, name: 'Riverside Elementary' },
  { id: 3, name: 'Mountainview Academy' }
]);

const exams = ref([
  {
    id: 1,
    name: 'Midterm Exam',
    school: 'Greenwood High School',
    grade: '10',
    subject: 'Mathematics',
    date: '2025-09-20',
    startTime: '09:00',
    endTime: '11:00',
    room: 'Room 101',
    status: 'scheduled',
    students: 120
  },
  {
    id: 2,
    name: 'Quarterly Test',
    school: 'Greenwood High School',
    grade: '11',
    subject: 'Physics',
    date: '2025-09-18',
    startTime: '10:00',
    endTime: '11:30',
    room: 'Lab 2',
    status: 'ongoing',
    students: 95
  },
  {
    id: 3,
    name: 'Final Exam',
    school: 'Riverside Elementary',
    grade: '5',
    subject: 'English',
    date: '2025-09-15',
    startTime: '14:00',
    endTime: '15:30',
    room: 'Room 205',
    status: 'completed',
    students: 80
  },
  {
    id: 4,
    name: 'Midterm Exam',
    school: 'Mountainview Academy',
    grade: '9',
    subject: 'Chemistry',
    date: '2025-09-25',
    startTime: '09:30',
    endTime: '11:30',
    room: 'Lab 1',
    status: 'scheduled',
    students: 65
  }
]);

// Computed properties
const filteredExams = computed(() => {
  return exams.value.filter(exam => {
    const matchesSearch = exam.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         exam.subject.toLowerCase().includes(searchQuery.value.toLowerCase());
    const schoolMatch = schoolFilter.value ? 
      schools.value.find(s => s.id === schoolFilter.value)?.name === exam.school : true;
    const statusMatch = statusFilter.value ? exam.status === statusFilter.value : true;
    return matchesSearch && schoolMatch && statusMatch;
  });
});

// Methods
const formatDate = (dateString) => {
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  return new Date(dateString).toLocaleDateString(undefined, options);
};

const openAddExamModal = () => {
  editingExam.value = null;
  examForm.value = {
    name: '',
    school: '',
    grade: '',
    subject: '',
    date: '',
    startTime: '',
    endTime: '',
    room: '',
    status: 'scheduled'
  };
  showExamModal.value = true;
};

const closeExamModal = () => {
  showExamModal.value = false;
};

const editExam = (exam) => {
  editingExam.value = exam;
  examForm.value = { ...exam };
  showExamModal.value = true;
};

const saveExam = () => {
  if (editingExam.value) {
    // Update existing exam
    const index = exams.value.findIndex(e => e.id === editingExam.value.id);
    if (index !== -1) {
      exams.value[index] = { ...exams.value[index], ...examForm.value };
    }
  } else {
    // Add new exam
    const newExam = {
      id: exams.value.length + 1,
      ...examForm.value,
      students: 0
    };
    exams.value.push(newExam);
  }
  
  closeExamModal();
};

const viewExam = (exam) => {
  // In a real app, this would navigate to exam details page
  alert(`Viewing details for ${exam.name}`);
};

const startExam = (exam) => {
  const index = exams.value.findIndex(e => e.id === exam.id);
  if (index !== -1) {
    exams.value[index].status = 'ongoing';
  }
};

const endExam = (exam) => {
  const index = exams.value.findIndex(e => e.id === exam.id);
  if (index !== -1) {
    exams.value[index].status = 'completed';
  }
};

const viewResults = (exam) => {
  // In a real app, this would navigate to exam results page
  alert(`Viewing results for ${exam.name}`);
};

// Lifecycle
onMounted(() => {
  // In a real app, we would fetch data from the API here
});
</script>

<style scoped>
.admin-exams {
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
  align-items: center;
  margin-bottom: 30px;
  flex-wrap: wrap;
  gap: 15px;
}

.search-controls {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
}

.search-box {
  position: relative;
  display: flex;
  align-items: center;
}

.search-box i {
  position: absolute;
  left: 12px;
  color: #9ca3af;
}

.search-box input {
  padding: 10px 12px 10px 40px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  width: 250px;
}

.search-controls select {
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
}

.exams-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 25px;
}

.exam-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  transition: all 0.2s ease;
  border-left: 4px solid #d1d5db;
}

.exam-card:hover {
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}

.exam-card.scheduled {
  border-left-color: #3b82f6;
}

.exam-card.ongoing {
  border-left-color: #f59e0b;
}

.exam-card.completed {
  border-left-color: #10b981;
}

.exam-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #e5e7eb;
}

.exam-header h3 {
  font-size: 1.3rem;
  font-weight: 600;
  color: #1f2937;
}

.exam-status {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.exam-status.scheduled {
  background: #dbeafe;
  color: #1e40af;
}

.exam-status.ongoing {
  background: #ffedd5;
  color: #9a3412;
}

.exam-status.completed {
  background: #dcfce7;
  color: #166534;
}

.exam-details {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.detail-item {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #6b7280;
}

.detail-item i {
  width: 20px;
  text-align: center;
}

.exam-actions {
  display: flex;
  justify-content: space-between;
  padding: 20px;
  background: #f9fafb;
  border-top: 1px solid #e5e7eb;
}

.exam-actions button {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 15px;
}

.no-exams {
  text-align: center;
  padding: 60px 20px;
  color: #6b7280;
  grid-column: 1 / -1;
}

.no-exams i {
  font-size: 4rem;
  margin-bottom: 20px;
  color: #d1d5db;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 12px;
  width: 100%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #e5e7eb;
}

.modal-header h3 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
}

.modal-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: #9ca3af;
  cursor: pointer;
  padding: 5px;
}

.modal-body {
  padding: 20px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
  color: #1f2937;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 1rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 15px;
  margin-top: 30px;
}

/* Dark mode support */
.dark .admin-exams {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .exam-header h3,
.dark .modal-header h3 {
  color: #f9fafb;
}

.dark .page-header p,
.detail-item {
  color: #d1d5db;
}

.dark .exam-card {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
  border-color: #374151;
}

.dark .exam-header,
.dark .exam-actions {
  border-color: #374151;
}

.dark .search-box input,
.search-controls select,
.form-group input,
.form-group select {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
}

.dark .modal-content {
  background: #1f2937;
}

.dark .modal-header {
  border-color: #374151;
}

.dark .no-exams {
  color: #9ca3af;
}

.dark .no-exams i {
  color: #4b5563;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .admin-exams {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .exams-controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .search-controls {
    flex-direction: column;
  }
  
  .search-box input {
    width: 100%;
  }
  
  .exams-grid {
    grid-template-columns: 1fr;
  }
  
  .exam-actions {
    flex-direction: column;
    gap: 10px;
  }
  
  .exam-actions button {
    width: 100%;
    justify-content: center;
  }
  
  .form-row {
    grid-template-columns: 1fr;
  }
}
</style>