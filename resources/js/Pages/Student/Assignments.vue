<template>
  <AppLayout>
    <div class="student-assignments">
      <div class="page-header">
        <h1>My Assignments</h1>
        <p>View and submit your assignments</p>
      </div>
      
      <div class="assignments-tabs">
        <button 
          :class="{ active: activeTab === 'pending' }"
          @click="activeTab = 'pending'"
        >
          Pending Assignments
        </button>
        <button 
          :class="{ active: activeTab === 'submitted' }"
          @click="activeTab = 'submitted'"
        >
          Submitted
        </button>
        <button 
          :class="{ active: activeTab === 'graded' }"
          @click="activeTab = 'graded'"
        >
          Graded
        </button>
      </div>
      
      <!-- Pending Assignments Tab -->
      <div v-if="activeTab === 'pending'" class="assignments-tab-content">
        <div class="assignments-grid">
          <div 
            v-for="assignment in pendingAssignments" 
            :key="assignment.id"
            class="assignment-card"
          >
            <div class="assignment-header">
              <h3>{{ assignment.title }}</h3>
              <span class="assignment-subject">{{ assignment.subject }}</span>
            </div>
            
            <div class="assignment-details">
              <div class="detail-item">
                <i class="fas fa-calendar"></i>
                <span>Due: {{ formatDate(assignment.dueDate) }}</span>
              </div>
              <div class="detail-item">
                <i class="fas fa-clock"></i>
                <span>{{ getDaysUntilDue(assignment.dueDate) }} days remaining</span>
              </div>
              <div class="detail-item">
                <i class="fas fa-file-alt"></i>
                <span>{{ assignment.type }}</span>
              </div>
              <div class="detail-item">
                <i class="fas fa-weight-hanging"></i>
                <span>{{ assignment.weight }}% of grade</span>
              </div>
            </div>
            
            <div class="assignment-description">
              <p>{{ assignment.description }}</p>
            </div>
            
            <div class="assignment-footer">
              <button class="btn-primary" @click="openSubmissionModal(assignment)">
                Submit Assignment
              </button>
            </div>
          </div>
          
          <div v-if="pendingAssignments.length === 0" class="no-assignments">
            <i class="fas fa-file-alt"></i>
            <p>No pending assignments</p>
          </div>
        </div>
      </div>
      
      <!-- Submitted Assignments Tab -->
      <div v-else-if="activeTab === 'submitted'" class="assignments-tab-content">
        <div class="assignments-list">
          <div 
            v-for="assignment in submittedAssignments" 
            :key="assignment.id"
            class="assignment-item"
          >
            <div class="assignment-info">
              <h4>{{ assignment.title }}</h4>
              <p class="assignment-subject">{{ assignment.subject }}</p>
              <div class="assignment-meta">
                <span class="meta-item">
                  <i class="fas fa-calendar"></i>
                  Submitted: {{ formatDate(assignment.submittedDate) }}
                </span>
                <span class="meta-item">
                  <i class="fas fa-clock"></i>
                  Due: {{ formatDate(assignment.dueDate) }}
                </span>
              </div>
            </div>
            
            <div class="assignment-status">
              <span class="status-badge pending">Pending Review</span>
            </div>
          </div>
          
          <div v-if="submittedAssignments.length === 0" class="no-assignments">
            <i class="fas fa-paper-plane"></i>
            <p>No submitted assignments</p>
          </div>
        </div>
      </div>
      
      <!-- Graded Assignments Tab -->
      <div v-else class="assignments-tab-content">
        <div class="assignments-list">
          <div 
            v-for="assignment in gradedAssignments" 
            :key="assignment.id"
            class="assignment-item"
          >
            <div class="assignment-info">
              <h4>{{ assignment.title }}</h4>
              <p class="assignment-subject">{{ assignment.subject }}</p>
              <div class="assignment-meta">
                <span class="meta-item">
                  <i class="fas fa-calendar"></i>
                  Submitted: {{ formatDate(assignment.submittedDate) }}
                </span>
                <span class="meta-item">
                  <i class="fas fa-clock"></i>
                  Due: {{ formatDate(assignment.dueDate) }}
                </span>
              </div>
            </div>
            
            <div class="assignment-grade">
              <div class="grade-value">{{ assignment.grade }}</div>
              <div class="grade-comment" v-if="assignment.comment">
                <i class="fas fa-comment"></i>
                {{ assignment.comment }}
              </div>
            </div>
          </div>
          
          <div v-if="gradedAssignments.length === 0" class="no-assignments">
            <i class="fas fa-graduation-cap"></i>
            <p>No graded assignments</p>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Submission Modal -->
    <div v-if="showSubmissionModal" class="modal-overlay" @click="closeSubmissionModal">
      <div class="modal-content" @click.stop>
        <div class="modal-header">
          <h2>Submit Assignment: {{ selectedAssignment?.title }}</h2>
          <button class="close-button" @click="closeSubmissionModal">
            <i class="fas fa-times"></i>
          </button>
        </div>
        
        <div class="modal-body">
          <div class="form-group">
            <label for="submissionText">Submission Text</label>
            <textarea 
              id="submissionText"
              v-model="submissionText"
              placeholder="Enter your assignment response..."
              rows="6"
            ></textarea>
          </div>
          
          <div class="form-group">
            <label>Upload Files</label>
            <div class="file-upload-area" @dragover.prevent @drop.prevent="handleFileDrop">
              <input 
                type="file" 
                ref="fileInput" 
                multiple 
                @change="handleFileSelect"
                style="display: none;"
              >
              <div class="upload-content" @click="triggerFileInput">
                <i class="fas fa-cloud-upload-alt"></i>
                <p>Drag & drop files here or click to browse</p>
                <p class="upload-hint">Supports PDF, DOC, DOCX, JPG, PNG files</p>
              </div>
            </div>
            
            <div v-if="uploadedFiles.length > 0" class="uploaded-files">
              <div 
                v-for="(file, index) in uploadedFiles" 
                :key="index"
                class="uploaded-file"
              >
                <i class="fas fa-file"></i>
                <span class="file-name">{{ file.name }}</span>
                <span class="file-size">{{ formatFileSize(file.size) }}</span>
                <button @click="removeFile(index)" class="remove-file">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal-footer">
          <button class="btn-secondary" @click="closeSubmissionModal">Cancel</button>
          <button class="btn-primary" @click="submitAssignment" :disabled="isSubmitting">
            {{ isSubmitting ? 'Submitting...' : 'Submit Assignment' }}
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Reactive data
const activeTab = ref('pending');
const showSubmissionModal = ref(false);
const selectedAssignment = ref(null);
const submissionText = ref('');
const uploadedFiles = ref([]);
const fileInput = ref(null);
const isSubmitting = ref(false);

// Mock data - in a real app, this would come from API
const assignmentsData = ref([
  {
    id: 1,
    title: 'Mathematical Proofs Assignment',
    subject: 'Mathematics',
    dueDate: '2023-06-20',
    type: 'Essay',
    weight: 15,
    description: 'Solve the given mathematical proofs and provide detailed explanations for each step.',
    status: 'pending'
  },
  {
    id: 2,
    title: 'Physics Lab Report',
    subject: 'Physics',
    dueDate: '2023-06-25',
    type: 'Report',
    weight: 20,
    description: 'Complete the lab report for the experiment on Newton\'s laws of motion.',
    status: 'pending'
  },
  {
    id: 3,
    title: 'Literature Analysis',
    subject: 'English Literature',
    dueDate: '2023-06-15',
    type: 'Essay',
    weight: 10,
    description: 'Analyze the themes in the provided literary work and write a 500-word essay.',
    status: 'submitted',
    submittedDate: '2023-06-14'
  },
  {
    id: 4,
    title: 'Historical Event Research',
    subject: 'History',
    dueDate: '2023-06-10',
    type: 'Research Paper',
    weight: 25,
    description: 'Research and write about a significant historical event of your choice.',
    status: 'graded',
    submittedDate: '2023-06-09',
    grade: 'A-',
    comment: 'Excellent research and well-structured argument.'
  }
]);

// Computed properties
const pendingAssignments = computed(() => {
  return assignmentsData.value
    .filter(assignment => assignment.status === 'pending')
    .sort((a, b) => new Date(a.dueDate) - new Date(b.dueDate));
});

const submittedAssignments = computed(() => {
  return assignmentsData.value
    .filter(assignment => assignment.status === 'submitted')
    .sort((a, b) => new Date(b.submittedDate) - new Date(a.submittedDate));
});

const gradedAssignments = computed(() => {
  return assignmentsData.value
    .filter(assignment => assignment.status === 'graded')
    .sort((a, b) => new Date(b.submittedDate) - new Date(a.submittedDate));
});

// Methods
const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const getDaysUntilDue = (dueDate) => {
  const today = new Date();
  const due = new Date(dueDate);
  const diffTime = due - today;
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  return diffDays;
};

const openSubmissionModal = (assignment) => {
  selectedAssignment.value = assignment;
  showSubmissionModal.value = true;
  submissionText.value = '';
  uploadedFiles.value = [];
};

const closeSubmissionModal = () => {
  showSubmissionModal.value = false;
  selectedAssignment.value = null;
};

const triggerFileInput = () => {
  fileInput.value.click();
};

const handleFileSelect = (event) => {
  const files = Array.from(event.target.files);
  uploadedFiles.value = [...uploadedFiles.value, ...files];
};

const handleFileDrop = (event) => {
  const files = Array.from(event.dataTransfer.files);
  uploadedFiles.value = [...uploadedFiles.value, ...files];
};

const removeFile = (index) => {
  uploadedFiles.value.splice(index, 1);
};

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes';
  const k = 1024;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const submitAssignment = () => {
  isSubmitting.value = true;
  
  // In a real app, this would be an API call
  setTimeout(() => {
    // Update assignment status
    const assignmentIndex = assignmentsData.value.findIndex(
      a => a.id === selectedAssignment.value.id
    );
    
    if (assignmentIndex !== -1) {
      assignmentsData.value[assignmentIndex].status = 'submitted';
      assignmentsData.value[assignmentIndex].submittedDate = new Date().toISOString().split('T')[0];
    }
    
    // Reset form and close modal
    isSubmitting.value = false;
    closeSubmissionModal();
    
    // Show success message (in a real app)
    alert('Assignment submitted successfully!');
  }, 1500);
};
</script>

<style scoped>
.student-assignments {
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

/* Assignments Grid */
.assignments-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 20px;
}

.assignment-card {
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  transition: all 0.2s ease;
}

.assignment-card:hover {
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}

.assignment-header h3 {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #1f2937;
}

.assignment-subject {
  color: #667eea;
  font-weight: 500;
}

.assignment-details {
  margin: 20px 0;
  display: flex;
  flex-direction: column;
  gap: 12px;
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
  color: #667eea;
}

.assignment-description {
  margin: 20px 0;
  color: #6b7280;
  line-height: 1.6;
}

.assignment-footer {
  margin-top: 15px;
  padding-top: 15px;
  border-top: 1px solid #f3f4f6;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 6px;
  padding: 10px 20px;
  font-size: 0.95rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
  width: 100%;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
}

.btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.no-assignments {
  grid-column: 1 / -1;
  text-align: center;
  padding: 60px 20px;
  color: #6b7280;
}

.no-assignments i {
  font-size: 3rem;
  margin-bottom: 15px;
  color: #d1d5db;
}

/* Assignments List */
.assignments-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.assignment-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: white;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.assignment-info h4 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #1f2937;
}

.assignment-subject {
  color: #667eea;
  font-weight: 500;
  margin-bottom: 10px;
  display: block;
}

.assignment-meta {
  display: flex;
  gap: 20px;
  color: #6b7280;
  font-size: 0.9rem;
}

.meta-item {
  display: flex;
  align-items: center;
  gap: 5px;
}

.assignment-status .status-badge {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 500;
}

.status-badge.pending {
  background: #fef3c7;
  color: #92400e;
}

.assignment-grade {
  text-align: right;
}

.grade-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 5px;
}

.grade-comment {
  font-size: 0.9rem;
  color: #6b7280;
  display: flex;
  align-items: center;
  gap: 5px;
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
  padding: 20px;
}

.modal-content {
  background: white;
  border-radius: 12px;
  width: 100%;
  max-width: 600px;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #f3f4f6;
}

.modal-header h2 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
  margin: 0;
}

.close-button {
  background: none;
  border: none;
  font-size: 1.5rem;
  color: #6b7280;
  cursor: pointer;
  padding: 5px;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.close-button:hover {
  background: #f3f4f6;
  color: #1f2937;
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

.form-group textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-family: inherit;
  font-size: 1rem;
  resize: vertical;
  min-height: 120px;
}

.form-group textarea:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.file-upload-area {
  border: 2px dashed #d1d5db;
  border-radius: 8px;
  padding: 30px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.file-upload-area:hover {
  border-color: #667eea;
  background: #f9fafb;
}

.upload-content i {
  font-size: 2.5rem;
  color: #667eea;
  margin-bottom: 15px;
}

.upload-content p {
  margin: 0 0 5px 0;
  color: #1f2937;
}

.upload-hint {
  font-size: 0.9rem;
  color: #6b7280;
}

.uploaded-files {
  margin-top: 20px;
}

.uploaded-file {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px;
  background: #f9fafb;
  border-radius: 6px;
  margin-bottom: 10px;
}

.uploaded-file i {
  color: #667eea;
}

.file-name {
  flex: 1;
  font-size: 0.95rem;
  color: #1f2937;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.file-size {
  font-size: 0.85rem;
  color: #6b7280;
}

.remove-file {
  background: none;
  border: none;
  color: #ef4444;
  cursor: pointer;
  padding: 5px;
  border-radius: 50%;
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.remove-file:hover {
  background: #fee2e2;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 15px;
  padding: 20px;
  border-top: 1px solid #f3f4f6;
}

.btn-secondary {
  background: #f3f4f6;
  color: #1f2937;
  border: none;
  border-radius: 6px;
  padding: 10px 20px;
  font-size: 0.95rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-secondary:hover {
  background: #e5e7eb;
}

/* Dark mode support */
.dark .student-assignments {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .assignment-header h3,
.dark .assignment-info h4,
.dark .modal-header h2 {
  color: #f9fafb;
}

.dark .page-header p,
.dark .assignment-subject,
.dark .detail-item,
.dark .assignment-meta,
.dark .meta-item,
.dark .grade-comment {
  color: #d1d5db;
}

.dark .assignments-tabs {
  background: #374151;
}

.dark .assignments-tabs button.active {
  background: #1f2937;
}

.dark .assignment-card,
.dark .assignment-item,
.dark .modal-content {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .assignment-card:hover {
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.3);
}

.dark .assignment-footer {
  border-top-color: #374151;
}

.dark .btn-primary {
  background: linear-gradient(135deg, #818cf8 0%, #a78bfa 100%);
}

.dark .no-assignments {
  color: #9ca3af;
}

.dark .no-assignments i {
  color: #4b5563;
}

.dark .file-upload-area {
  border-color: #4b5563;
  background: #111827;
}

.dark .file-upload-area:hover {
  border-color: #818cf8;
  background: #1f2937;
}

.dark .upload-content p {
  color: #f9fafb;
}

.dark .upload-hint {
  color: #9ca3af;
}

.dark .uploaded-file {
  background: #111827;
}

.dark .uploaded-file i {
  color: #818cf8;
}

.dark .file-name {
  color: #f9fafb;
}

.dark .file-size {
  color: #9ca3af;
}

.dark .remove-file:hover {
  background: #374151;
}

.dark .form-group label {
  color: #f9fafb;
}

.dark .form-group textarea {
  background: #111827;
  border-color: #4b5563;
  color: #f9fafb;
}

.dark .form-group textarea:focus {
  border-color: #818cf8;
  box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2);
}

.dark .modal-header,
.dark .modal-footer {
  border-color: #374151;
}

.dark .close-button {
  color: #9ca3af;
}

.dark .close-button:hover {
  background: #374151;
  color: #f9fafb;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .student-assignments {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .assignments-grid {
    grid-template-columns: 1fr;
  }
  
  .assignment-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .assignment-grade {
    text-align: left;
  }
  
  .modal-content {
    margin: 10px;
    max-height: calc(100vh - 20px);
  }
  
  .modal-header,
  .modal-body,
  .modal-footer {
    padding: 15px;
  }
  
  .assignment-meta {
    flex-direction: column;
    gap: 5px;
  }
}
</style>