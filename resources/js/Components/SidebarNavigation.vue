<template>
  <div class="sidebar" v-if="showSidebar" :class="{ collapsed: isCollapsed, dark: isDarkMode }">
    <div class="sidebar-header">
      <div class="brand" v-if="!isCollapsed">
        <i class="fas fa-graduation-cap brand-icon"></i>
        <span class="brand-text">Dunco School</span>
      </div>
      <button class="toggle-btn" @click="toggleSidebar">
        <i :class="isCollapsed ? 'fas fa-arrow-right' : 'fas fa-arrow-left'"></i>
      </button>
    </div>
    
    <div class="sidebar-content">
      <nav class="nav-menu">
        <div 
          v-for="item in navigationItems" 
          :key="item.id"
          class="nav-item"
          :class="{ active: isActiveRoute(item.route), hasChildren: item.children }"
        >
          <router-link 
            v-if="item.route" 
            :to="item.route" 
            class="nav-link"
            :title="isCollapsed ? item.name : ''"
          >
            <i :class="item.icon"></i>
            <span class="nav-text" v-if="!isCollapsed">{{ item.name }}</span>
          </router-link>
          
          <div v-else class="nav-group" @click="toggleGroup(item.id)">
            <div class="nav-link" :title="isCollapsed ? item.name : ''">
              <i :class="item.icon"></i>
              <span class="nav-text" v-if="!isCollapsed">{{ item.name }}</span>
              <i 
                v-if="!isCollapsed" 
                class="fas fa-chevron-down toggle-icon"
                :class="{ rotated: openGroups.includes(item.id) }"
              ></i>
            </div>
            
            <div 
              v-if="item.children && !isCollapsed" 
              class="nav-children"
              :class="{ open: openGroups.includes(item.id) }"
            >
              <router-link 
                v-for="child in item.children" 
                :key="child.id"
                :to="child.route"
                class="nav-child"
                :class="{ active: isActiveRoute(child.route) }"
              >
                <i :class="child.icon"></i>
                <span class="nav-text">{{ child.name }}</span>
              </router-link>
            </div>
          </div>
        </div>
      </nav>
    </div>
    
    <div class="sidebar-footer" v-if="!isCollapsed">
      <div class="user-info">
        <img 
          :src="userAvatar" 
          :alt="userName" 
          class="user-avatar"
        >
        <div class="user-details">
          <div class="user-name">{{ userName }}</div>
          <div class="user-role">{{ userRole }}</div>
        </div>
      </div>
      <div class="user-actions">
        <button class="action-btn" @click="logout">
          <i class="fas fa-sign-out-alt"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { getNavigationItems } from '../navigation/roles';
import { useDarkMode } from '../Composables/useDarkMode';

const page = usePage();
const isCollapsed = ref(false);
const openGroups = ref([]);

// Use dark mode composable
const { isDarkMode } = useDarkMode();

// Get user data from page props
const userName = computed(() => page.props.auth?.user?.name || 'User');
const userRole = computed(() => page.props.auth?.user?.roles?.[0]?.name || 'admin');
const userAvatar = computed(() => {
  const name = userName.value;
  return `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=667eea&color=fff`;
});

// Determine if sidebar should be shown
const showSidebar = computed(() => {
  return userRole.value === 'admin';
});

// Get navigation items based on user role
const navigationItems = computed(() => {
  return getNavigationItems(userRole.value);
});

// Check if current route is active
const isActiveRoute = (route) => {
  if (!route) return false;
  return window.location.pathname === route;
};

// Toggle sidebar collapse state
const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value;
  localStorage.setItem('sidebarCollapsed', isCollapsed.value);
};

// Toggle group open/close state
const toggleGroup = (groupId) => {
  const index = openGroups.value.indexOf(groupId);
  if (index > -1) {
    openGroups.value.splice(index, 1);
  } else {
    openGroups.value.push(groupId);
  }
};

// Logout function
const logout = () => {
  if (confirm('Are you sure you want to logout?')) {
    // This would typically make an API call to logout
    window.location.href = '/logout';
  }
};

// Handle window resize
const handleResize = () => {
  if (window.innerWidth < 768) {
    isCollapsed.value = true;
  } else {
    isCollapsed.value = localStorage.getItem('sidebarCollapsed') === 'true';
  }
};

// Initialize sidebar state
onMounted(() => {
  isCollapsed.value = localStorage.getItem('sidebarCollapsed') === 'true';
  window.addEventListener('resize', handleResize);
  
  // Add body margin to account for sidebar
  if (showSidebar.value) {
    document.body.style.marginLeft = isCollapsed.value ? '60px' : '250px';
  }
});

onUnmounted(() => {
  window.removeEventListener('resize', handleResize);
});
</script>

<style scoped>
.sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 250px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  z-index: 1000;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.sidebar.collapsed {
  width: 60px;
}

.sidebar-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 15px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.brand {
  display: flex;
  align-items: center;
  gap: 10px;
}

.brand-icon {
  font-size: 1.5rem;
}

.brand-text {
  font-size: 1.2rem;
  font-weight: 600;
}

.toggle-btn {
  background: rgba(255, 255, 255, 0.1);
  border: none;
  color: white;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.toggle-btn:hover {
  background: rgba(255, 255, 255, 0.2);
}

.sidebar-content {
  flex: 1;
  overflow-y: auto;
  padding: 10px 0;
}

.nav-menu {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.nav-item {
  width: 100%;
}

.nav-link {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 15px;
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  transition: all 0.2s ease;
  position: relative;
}

.nav-link:hover {
  background: rgba(255, 255, 255, 0.1);
  color: white;
}

.nav-link.active {
  background: rgba(255, 255, 255, 0.2);
  color: white;
  font-weight: 500;
}

.nav-link i {
  font-size: 1.1rem;
  min-width: 20px;
  text-align: center;
}

.nav-text {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.toggle-icon {
  margin-left: auto;
  transition: transform 0.3s ease;
}

.toggle-icon.rotated {
  transform: rotate(180deg);
}

.nav-children {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease;
}

.nav-children.open {
  max-height: 500px;
}

.nav-child {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 15px 10px 45px;
  color: rgba(255, 255, 255, 0.7);
  text-decoration: none;
  transition: all 0.2s ease;
  font-size: 0.9rem;
}

.nav-child:hover {
  background: rgba(255, 255, 255, 0.1);
  color: white;
}

.nav-child.active {
  background: rgba(255, 255, 255, 0.2);
  color: white;
}

.sidebar-footer {
  padding: 15px;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  display: flex;
  align-items: center;
  gap: 10px;
}

.user-info {
  display: flex;
  align-items: center;
  gap: 10px;
  flex: 1;
}

.user-avatar {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  object-fit: cover;
}

.user-details {
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.user-name {
  font-weight: 500;
  font-size: 0.9rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-role {
  font-size: 0.8rem;
  opacity: 0.8;
}

.user-actions {
  display: flex;
  gap: 5px;
}

.action-btn {
  background: rgba(255, 255, 255, 0.1);
  border: none;
  color: white;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.action-btn:hover {
  background: rgba(255, 255, 255, 0.2);
}

/* Collapsed state styles */
.sidebar.collapsed .brand-text,
.sidebar.collapsed .nav-text,
.sidebar.collapsed .user-info,
.sidebar.collapsed .user-actions,
.sidebar.collapsed .toggle-icon {
  display: none;
}

.sidebar.collapsed .nav-link,
.sidebar.collapsed .nav-child {
  justify-content: center;
  padding: 15px;
}

.sidebar.collapsed .nav-child {
  padding: 12px 15px;
}

.sidebar.collapsed .nav-group .nav-link {
  justify-content: center;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .sidebar {
    width: 60px;
  }
  
  .sidebar:not(.collapsed) .brand-text,
  .sidebar:not(.collapsed) .nav-text,
  .sidebar:not(.collapsed) .user-info,
  .sidebar:not(.collapsed) .user-actions,
  .sidebar:not(.collapsed) .toggle-icon {
    display: none;
  }
  
  .sidebar:not(.collapsed) .nav-link,
  .sidebar:not(.collapsed) .nav-child {
    justify-content: center;
    padding: 15px;
  }
}

/* Dark mode support */
.dark .sidebar {
  background: #1f2937;
}
</style>