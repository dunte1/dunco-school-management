<template>
  <AppLayout>
    <div class="admin-users">
      <div class="page-header">
        <h1>User Management</h1>
        <p>Manage all users across your school system</p>
      </div>
      
      <div class="users-controls">
        <button class="btn-primary" @click="openAddUserModal">
          <i class="fas fa-plus"></i>
          Add New User
        </button>
        
        <div class="search-controls">
          <div class="search-box">
            <i class="fas fa-search"></i>
            <input 
              type="text" 
              placeholder="Search users..." 
              v-model="searchQuery"
            >
          </div>
          
          <select v-model="roleFilter">
            <option value="">All Roles</option>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
            <option value="parent">Parent</option>
            <option value="admin">Admin</option>
          </select>
          
          <select v-model="statusFilter">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
      </div>
      
      <div class="users-table">
        <div class="table-header">
          <div class="header-cell">User</div>
          <div class="header-cell">Role</div>
          <div class="header-cell">School</div>
          <div class="header-cell">Email</div>
          <div class="header-cell">Status</div>
          <div class="header-cell">Actions</div>
        </div>
        
        <div 
          v-for="user in paginatedUsers" 
          :key="user.id"
          class="table-row"
        >
          <div class="table-cell">
            <div class="user-info">
              <div class="user-avatar">
                <i class="fas fa-user"></i>
              </div>
              <div class="user-details">
                <div class="user-name">{{ user.name }}</div>
                <div class="user-id">ID: {{ user.id }}</div>
              </div>
            </div>
          </div>
          
          <div class="table-cell">
            <span class="role-badge" :class="user.role">
              {{ user.role }}
            </span>
          </div>
          
          <div class="table-cell">{{ user.school }}</div>
          <div class="table-cell">{{ user.email }}</div>
          
          <div class="table-cell">
            <span class="status-badge" :class="user.status">
              {{ user.status }}
            </span>
          </div>
          
          <div class="table-cell">
            <div class="action-buttons">
              <button class="btn-icon" @click="viewUser(user)" title="View">
                <i class="fas fa-eye"></i>
              </button>
              <button class="btn-icon" @click="editUser(user)" title="Edit">
                <i class="fas fa-edit"></i>
              </button>
              <button 
                class="btn-icon" 
                @click="toggleUserStatus(user)"
                :title="user.status === 'active' ? 'Deactivate' : 'Activate'"
              >
                <i :class="user.status === 'active' ? 'fas fa-times' : 'fas fa-check'"></i>
              </button>
              <button class="btn-icon danger" @click="deleteUser(user)" title="Delete">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <div class="pagination">
        <button 
          class="pagination-btn" 
          :disabled="currentPage === 1"
          @click="currentPage--"
        >
          <i class="fas fa-chevron-left"></i>
        </button>
        
        <span class="pagination-info">
          Page {{ currentPage }} of {{ totalPages }}
        </span>
        
        <button 
          class="pagination-btn" 
          :disabled="currentPage === totalPages"
          @click="currentPage++"
        >
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
      
      <div v-if="filteredUsers.length === 0" class="no-users">
        <i class="fas fa-users"></i>
        <p>No users found</p>
      </div>
      
      <!-- Add/Edit User Modal -->
      <div v-if="showUserModal" class="modal-overlay" @click="closeUserModal">
        <div class="modal-content" @click.stop>
          <div class="modal-header">
            <h3>{{ editingUser ? 'Edit User' : 'Add New User' }}</h3>
            <button class="modal-close" @click="closeUserModal">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <div class="modal-body">
            <form @submit.prevent="saveUser">
              <div class="form-group">
                <label for="userName">Full Name</label>
                <input 
                  type="text" 
                  id="userName" 
                  v-model="userForm.name" 
                  required
                >
              </div>
              
              <div class="form-group">
                <label for="userEmail">Email</label>
                <input 
                  type="email" 
                  id="userEmail" 
                  v-model="userForm.email" 
                  required
                >
              </div>
              
              <div class="form-group">
                <label for="userRole">Role</label>
                <select id="userRole" v-model="userForm.role" required>
                  <option value="student">Student</option>
                  <option value="teacher">Teacher</option>
                  <option value="parent">Parent</option>
                  <option value="admin">Admin</option>
                </select>
              </div>
              
              <div class="form-group">
                <label for="userSchool">School</label>
                <select id="userSchool" v-model="userForm.school" required>
                  <option v-for="school in schools" :key="school.id" :value="school.name">
                    {{ school.name }}
                  </option>
                </select>
              </div>
              
              <div class="form-group">
                <label for="userStatus">Status</label>
                <select id="userStatus" v-model="userForm.status">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
              
              <div class="form-group" v-if="userForm.role === 'student'">
                <label for="userGrade">Grade</label>
                <input 
                  type="text" 
                  id="userGrade" 
                  v-model="userForm.grade"
                >
              </div>
              
              <div class="form-group" v-if="userForm.role === 'student'">
                <label for="userParent">Parent</label>
                <select id="userParent" v-model="userForm.parent">
                  <option value="">Select Parent</option>
                  <option v-for="parent in parents" :key="parent.id" :value="parent.id">
                    {{ parent.name }}
                  </option>
                </select>
              </div>
              
              <div class="form-actions">
                <button type="button" class="btn-secondary" @click="closeUserModal">
                  Cancel
                </button>
                <button type="submit" class="btn-primary">
                  {{ editingUser ? 'Update User' : 'Add User' }}
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
const roleFilter = ref('');
const statusFilter = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const showUserModal = ref(false);
const editingUser = ref(null);

const userForm = ref({
  name: '',
  email: '',
  role: 'student',
  school: '',
  status: 'active',
  grade: '',
  parent: ''
});

// Mock data - in a real app, this would come from API
const users = ref([
  {
    id: 1,
    name: 'John Smith',
    email: 'john.smith@student.edu',
    role: 'student',
    school: 'Greenwood High School',
    status: 'active',
    grade: '10',
    parent: 101
  },
  {
    id: 2,
    name: 'Sarah Johnson',
    email: 'sarah.johnson@teacher.edu',
    role: 'teacher',
    school: 'Greenwood High School',
    status: 'active'
  },
  {
    id: 3,
    name: 'Michael Brown',
    email: 'michael.brown@parent.edu',
    role: 'parent',
    school: 'Greenwood High School',
    status: 'active'
  },
  {
    id: 4,
    name: 'Emily Davis',
    email: 'emily.davis@admin.edu',
    role: 'admin',
    school: 'Greenwood High School',
    status: 'active'
  },
  {
    id: 5,
    name: 'Robert Wilson',
    email: 'robert.wilson@student.edu',
    role: 'student',
    school: 'Riverside Elementary',
    status: 'active',
    grade: '5',
    parent: 102
  },
  {
    id: 6,
    name: 'Jennifer Lee',
    email: 'jennifer.lee@teacher.edu',
    role: 'teacher',
    school: 'Riverside Elementary',
    status: 'inactive'
  }
]);

const schools = ref([
  { id: 1, name: 'Greenwood High School' },
  { id: 2, name: 'Riverside Elementary' },
  { id: 3, name: 'Mountainview Academy' }
]);

const parents = ref([
  { id: 101, name: 'Michael Brown' },
  { id: 102, name: 'Lisa Wilson' }
]);

// Computed properties
const filteredUsers = computed(() => {
  return users.value.filter(user => {
    const matchesSearch = user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                         user.email.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesRole = roleFilter.value ? user.role === roleFilter.value : true;
    const matchesStatus = statusFilter.value ? user.status === statusFilter.value : true;
    return matchesSearch && matchesRole && matchesStatus;
  });
});

const paginatedUsers = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  const end = start + itemsPerPage.value;
  return filteredUsers.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(filteredUsers.value.length / itemsPerPage.value);
});

// Methods
const openAddUserModal = () => {
  editingUser.value = null;
  userForm.value = {
    name: '',
    email: '',
    role: 'student',
    school: '',
    status: 'active',
    grade: '',
    parent: ''
  };
  showUserModal.value = true;
};

const closeUserModal = () => {
  showUserModal.value = false;
};

const editUser = (user) => {
  editingUser.value = user;
  userForm.value = { ...user };
  showUserModal.value = true;
};

const saveUser = () => {
  if (editingUser.value) {
    // Update existing user
    const index = users.value.findIndex(u => u.id === editingUser.value.id);
    if (index !== -1) {
      users.value[index] = { ...users.value[index], ...userForm.value };
    }
  } else {
    // Add new user
    const newUser = {
      id: users.value.length + 1,
      ...userForm.value
    };
    users.value.push(newUser);
  }
  
  closeUserModal();
};

const viewUser = (user) => {
  // In a real app, this would navigate to user details page
  alert(`Viewing details for ${user.name}`);
};

const toggleUserStatus = (user) => {
  const index = users.value.findIndex(u => u.id === user.id);
  if (index !== -1) {
    users.value[index].status = user.status === 'active' ? 'inactive' : 'active';
  }
};

const deleteUser = (user) => {
  if (confirm(`Are you sure you want to delete ${user.name}?`)) {
    const index = users.value.findIndex(u => u.id === user.id);
    if (index !== -1) {
      users.value.splice(index, 1);
    }
  }
};

// Lifecycle
onMounted(() => {
  // In a real app, we would fetch data from the API here
  if (schools.value.length > 0) {
    userForm.value.school = schools.value[0].name;
  }
});
</script>

<style scoped>
.admin-users {
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

.users-controls {
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

.users-table {
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  overflow: hidden;
  margin-bottom: 30px;
}

.table-header {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 2fr 1fr 1fr;
  background: #f9fafb;
  font-weight: 600;
  padding: 15px 20px;
}

.table-row {
  display: grid;
  grid-template-columns: 2fr 1fr 1fr 2fr 1fr 1fr;
  padding: 15px 20px;
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

.user-info {
  display: flex;
  align-items: center;
  gap: 15px;
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
  color: #9ca3af;
}

.user-details {
  display: flex;
  flex-direction: column;
}

.user-name {
  font-weight: 600;
  color: #1f2937;
}

.user-id {
  font-size: 0.8rem;
  color: #6b7280;
}

.role-badge {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.role-badge.student {
  background: #dbeafe;
  color: #1e40af;
}

.role-badge.teacher {
  background: #dcfce7;
  color: #166534;
}

.role-badge.parent {
  background: #ffedd5;
  color: #9a3412;
}

.role-badge.admin {
  background: #f0f9ff;
  color: #0369a1;
}

.status-badge {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 500;
}

.status-badge.active {
  background: #dcfce7;
  color: #166534;
}

.status-badge.inactive {
  background: #fee2e2;
  color: #991b1b;
}

.action-buttons {
  display: flex;
  gap: 10px;
}

.btn-icon {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: none;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-icon:hover {
  background: #f3f4f6;
}

.btn-icon.danger:hover {
  background: #fee2e2;
  color: #ef4444;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 15px;
  margin-bottom: 30px;
}

.pagination-btn {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: white;
  border: 1px solid #d1d5db;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.pagination-btn:hover:not(:disabled) {
  background: #f3f4f6;
}

.pagination-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination-info {
  font-weight: 500;
  color: #1f2937;
}

.no-users {
  text-align: center;
  padding: 60px 20px;
  color: #6b7280;
}

.no-users i {
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
.dark .admin-users {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .modal-header h3,
.user-name {
  color: #f9fafb;
}

.dark .page-header p,
.user-id {
  color: #d1d5db;
}

.dark .users-table {
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

.dark .search-box input,
.search-controls select,
.form-group input,
.form-group select {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
}

.dark .btn-icon:hover {
  background: #374151;
}

.dark .btn-icon.danger:hover {
  background: #7f1d1d;
}

.dark .pagination-btn {
  background: #1f2937;
  border-color: #374151;
}

.dark .pagination-btn:hover:not(:disabled) {
  background: #374151;
}

.dark .pagination-info {
  color: #f9fafb;
}

.dark .modal-content {
  background: #1f2937;
}

.dark .modal-header {
  border-color: #374151;
}

.dark .no-users {
  color: #9ca3af;
}

.dark .no-users i {
  color: #4b5563;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .admin-users {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .users-controls {
    flex-direction: column;
    align-items: stretch;
  }
  
  .search-controls {
    flex-direction: column;
  }
  
  .search-box input {
    width: 100%;
  }
  
  .table-row {
    grid-template-columns: 2fr 1fr 1fr 2fr 1fr 1fr;
  }
  
  .user-info {
    flex-direction: column;
    align-items: flex-start;
    gap: 5px;
  }
  
  .action-buttons {
    flex-wrap: wrap;
  }
}
</style>