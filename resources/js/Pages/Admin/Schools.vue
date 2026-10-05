<template>
  <AppLayout>
    <div class="admin-schools">
      <div class="page-header">
        <h1>School Management</h1>
        <p>Manage all schools in your system</p>
      </div>
      
      <div class="schools-controls">
        <button class="btn-primary" @click="openAddSchoolModal">
          <i class="fas fa-plus"></i>
          Add New School
        </button>
        
        <div class="search-controls">
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input 
              type="text" 
              placeholder="Search schools..." 
              v-model="searchQuery"
            >
          </div>
          
          <select v-model="statusFilter">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>
      
      <div class="schools-grid">
        <div 
          v-for="school in filteredSchools" 
          :key="school.id"
          class="school-card"
        >
          <div class="school-header">
            <div class="school-logo">
              <i class="fas fa-school"></i>
            </div>
            <div class="school-info">
              <h3>{{ school.name }}</h3>
              <p>{{ school.address }}</p>
              <span class="school-status" :class="school.status">
                {{ school.status }}
              </span>
            </div>
          </div>
          
          <div class="school-stats">
            <div class="stat-item">
              <i class="fas fa-user-graduate"></i>
              <div>
                <div class="stat-value">{{ school.students }}</div>
                <div class="stat-label">Students</div>
              </div>
            </div>
            
            <div class="stat-item">
              <i class="fas fa-chalkboard-teacher"></i>
              <div>
                <div class="stat-value">{{ school.teachers }}</div>
                <div class="stat-label">Teachers</div>
              </div>
            </div>
            
            <div class="stat-item">
              <i class="fas fa-users"></i>
              <div>
                <div class="stat-value">{{ school.parents }}</div>
                <div class="stat-label">Parents</div>
              </div>
            </div>
          </div>
          
          <div class="school-actions">
            <button class="btn-secondary" @click="viewSchool(school)">
              <i class="fas fa-eye"></i>
              View
            </button>
            <button class="btn-secondary" @click="editSchool(school)">
              <i class="fas fa-edit"></i>
              Edit
            </button>
            <button 
              class="btn-secondary" 
              @click="toggleSchoolStatus(school)"
              :class="{ 'btn-danger': school.status === 'active' }"
            >
              <i :class="school.status === 'active' ? 'fas fa-times' : 'fas fa-check'"></i>
              {{ school.status === 'active' ? 'Deactivate' : 'Activate' }}
            </button>
          </div>
        </div>
      </div>
      
      <div v-if="filteredSchools.length === 0" class="no-schools">
        <i class="fas fa-school"></i>
        <p>No schools found</p>
      </div>
      
      <!-- Add/Edit School Modal -->
      <div v-if="showSchoolModal" class="modal-overlay" @click="closeSchoolModal">
        <div class="modal-content" @click.stop>
          <div class="modal-header">
            <h3>{{ editingSchool ? 'Edit School' : 'Add New School' }}</h3>
            <button class="modal-close" @click="closeSchoolModal">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <div class="modal-body">
            <form @submit.prevent="saveSchool">
              <div class="form-group">
                <label for="schoolName">School Name</label>
                <input 
                  type="text" 
                  id="schoolName" 
                  v-model="schoolForm.name" 
                  required
                >
              </div>
              
              <div class="form-group">
                <label for="schoolAddress">Address</label>
                <input 
                  type="text" 
                  id="schoolAddress" 
                  v-model="schoolForm.address" 
                  required
                >
              </div>
              
              <div class="form-group">
                <label for="schoolPhone">Phone</label>
                <input 
                  type="tel" 
                  id="schoolPhone" 
                  v-model="schoolForm.phone"
                >
              </div>
              
              <div class="form-group">
                <label for="schoolEmail">Email</label>
                <input 
                  type="email" 
                  id="schoolEmail" 
                  v-model="schoolForm.email"
                >
              </div>
              
              <div class="form-group">
                <label for="schoolPrincipal">Principal</label>
                <input 
                  type="text" 
                  id="schoolPrincipal" 
                  v-model="schoolForm.principal"
                >
              </div>
              
              <div class="form-group">
                <label for="schoolStatus">Status</label>
                <select id="schoolStatus" v-model="schoolForm.status">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
              
              <div class="form-actions">
                <button type="button" class="btn-secondary" @click="closeSchoolModal">
                  Cancel
                </button>
                <button type="submit" class="btn-primary">
                  {{ editingSchool ? 'Update School' : 'Add School' }}
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
const statusFilter = ref('');
const showSchoolModal = ref(false);
const editingSchool = ref(null);

const schoolForm = ref({
  name: '',
  address: '',
  phone: '',
  email: '',
  principal: '',
  status: 'active'
});

// Mock data - in a real app, this would come from API
const schools = ref([
  {
    id: 1,
    name: 'Greenwood High School',
    address: '123 Education St, Cityville',
    phone: '(555) 123-4567',
    email: 'info@greenwoodhs.edu',
    principal: 'Dr. Jane Smith',
    status: 'active',
    students: 1200,
    teachers: 45,
    parents: 1100
  },
  {
    id: 2,
    name: 'Riverside Elementary',
    address: '456 Learning Ave, Townsburg',
    phone: '(555) 987-6543',
    email: 'admin@riversideelem.edu',
    principal: 'Mr. Robert Johnson',
    status: 'active',
    students: 800,
    teachers: 30,
    parents: 750
  },
  {
    id: 3,
    name: 'Mountainview Academy',
    address: '789 Knowledge Blvd, Hilltop',
    phone: '(555) 456-7890',
    email: 'contact@mountainview.edu',
    principal: 'Ms. Sarah Wilson',
    status: 'inactive',
    students: 650,
    teachers: 25,
    parents: 600
  }
]);

// Computed properties
const filteredSchools = computed(() => {
  return schools.value.filter(school => {
    const matchesSearch = school.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         school.address.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesStatus = statusFilter.value ? school.status === statusFilter.value : true;
    return matchesSearch && matchesStatus;
  });
});

// Methods
const openAddSchoolModal = () => {
  editingSchool.value = null;
  schoolForm.value = {
    name: '',
    address: '',
    phone: '',
    email: '',
    principal: '',
    status: 'active'
  };
  showSchoolModal.value = true;
};

const closeSchoolModal = () => {
  showSchoolModal.value = false;
};

const editSchool = (school) => {
  editingSchool.value = school;
  schoolForm.value = { ...school };
  showSchoolModal.value = true;
};

const saveSchool = () => {
  if (editingSchool.value) {
    // Update existing school
    const index = schools.value.findIndex(s => s.id === editingSchool.value.id);
    if (index !== -1) {
      schools.value[index] = { ...schools.value[index], ...schoolForm.value };
    }
  } else {
    // Add new school
    const newSchool = {
      id: schools.value.length + 1,
      ...schoolForm.value,
      students: 0,
      teachers: 0,
      parents: 0
    };
    schools.value.push(newSchool);
  }
  
  closeSchoolModal();
};

const viewSchool = (school) => {
  // In a real app, this would navigate to school details page
  alert(`Viewing details for ${school.name}`);
};

const toggleSchoolStatus = (school) => {
  const index = schools.value.findIndex(s => s.id === school.id);
  if (index !== -1) {
    schools.value[index].status = school.status === 'active' ? 'inactive' : 'active';
  }
};

// Lifecycle
onMounted(() => {
  // In a real app, we would fetch data from the API here
});
</script>

<style scoped>
.admin-schools {
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

.schools-controls {
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

.schools-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 25px;
}

.school-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  transition: all 0.2s ease;
}

.school-card:hover {
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
}

.school-header {
  display: flex;
  align-items: center;
  padding: 25px;
  border-bottom: 1px solid #e5e7eb;
}

.school-logo {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  background: #dbeafe;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 20px;
  font-size: 1.8rem;
  color: #3b82f6;
}

.school-info h3 {
  font-size: 1.3rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #1f2937;
}

.school-info p {
  color: #6b7280;
  margin-bottom: 10px;
}

.school-status {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.school-status.active {
  background: #dcfce7;
  color: #166534;
}

.school-status.inactive {
  background: #fee2e2;
  color: #991b1b;
}

.school-stats {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  padding: 20px;
  gap: 15px;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 10px;
}

.stat-item i {
  font-size: 1.2rem;
  color: #6b7280;
}

.stat-value {
  font-size: 1.2rem;
  font-weight: 700;
  color: #1f2937;
}

.stat-label {
  font-size: 0.8rem;
  color: #6b7280;
}

.school-actions {
  display: flex;
  justify-content: space-between;
  padding: 20px;
  background: #f9fafb;
  border-top: 1px solid #e5e7eb;
}

.school-actions button {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 15px;
}

.no-schools {
  text-align: center;
  padding: 60px 20px;
  color: #6b7280;
  grid-column: 1 / -1;
}

.no-schools i {
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
  max-width: 500px;
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

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 15px;
  margin-top: 30px;
}

/* Dark mode support */
.dark .admin-schools {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .school-info h3,
.dark .modal-header h3 {
  color: #f9fafb;
}

.dark .page-header p,
.dark .school-info p,
.dark .stat-label,
.form-group label {
  color: #d1d5db;
}

.dark .school-card,
.dark .modal-content {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .school-header,
.dark .school-actions {
  border-color: #374151;
}

.dark .search-box input,
.dark .search-controls select,
.dark .form-group input,
.dark .form-group select {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
}

.dark .stat-item i {
  color: #9ca3af;
}

.dark .stat-value {
  color: #f9fafb;
}

.dark .no-schools {
  color: #9ca3af;
}

.dark .no-schools i {
  color: #4b5563;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .admin-schools {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .schools-controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .search-controls {
    flex-direction: column;
  }
  
  .search-box input {
    width: 100%;
  }
  
  .schools-grid {
    grid-template-columns: 1fr;
  }
  
  .school-stats {
    grid-template-columns: 1fr;
  }
  
  .school-actions {
    flex-direction: column;
    gap: 10px;
  }
  
  .school-actions button {
    width: 100%;
    justify-content: center;
  }
}
</style>