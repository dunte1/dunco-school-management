<template>
  <div class="top-app-bar" :class="{ 'with-sidebar': showSidebar }">
    <div class="app-bar-content">
      <!-- Mobile menu toggle -->
      <button 
        v-if="showSidebar" 
        class="menu-toggle"
        @click="toggleSidebar"
      >
        <i class="fas fa-bars"></i>
      </button>
      
      <!-- App logo and title -->
      <div class="app-brand">
        <i class="fas fa-graduation-cap brand-icon"></i>
        <span class="brand-text" v-if="!isMobile">Dunco School</span>
      </div>
      
      <!-- Search bar (hidden on mobile) -->
      <div class="search-container" v-if="!isMobile">
        <div class="search-box">
          <i class="fas fa-search search-icon"></i>
          <input 
            type="text" 
            placeholder="Search..." 
            class="search-input"
            v-model="searchQuery"
            @keyup.enter="performSearch"
          >
        </div>
      </div>
      
      <!-- Right actions -->
      <div class="actions">
        <!-- PWA Install Button -->
        <button 
          v-if="showInstallButton" 
          class="action-btn install-btn" 
          @click="installPWA"
          title="Install App"
        >
          <i class="fas fa-download"></i>
        </button>
        
        <!-- Dark mode toggle -->
        <button class="action-btn" @click="toggleDarkMode" :title="darkMode ? 'Switch to light mode' : 'Switch to dark mode'">
          <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'"></i>
        </button>
        
        <!-- Notifications -->
        <div class="notification-container" ref="notificationContainer">
          <button 
            class="action-btn notification-btn" 
            @click="toggleNotifications"
            :class="{ 'has-unread': unreadCount > 0 }"
          >
            <i class="fas fa-bell"></i>
            <span v-if="unreadCount > 0" class="notification-badge">{{ unreadCount }}</span>
          </button>
          
          <!-- Notification dropdown -->
          <div 
            v-if="showNotifications" 
            class="notification-dropdown"
            :class="{ 'mobile': isMobile }"
          >
            <div class="notification-header">
              <h3>Notifications</h3>
              <button class="close-btn" @click="toggleNotifications">
                <i class="fas fa-times"></i>
              </button>
            </div>
            
            <div class="notification-list">
              <div 
                v-if="notifications.length === 0" 
                class="no-notifications"
              >
                No new notifications
              </div>
              
              <div 
                v-for="notification in notifications" 
                :key="notification.id"
                class="notification-item"
                :class="{ 'unread': !notification.read }"
              >
                <div class="notification-content">
                  <div class="notification-title">{{ notification.title }}</div>
                  <div class="notification-message">{{ notification.message }}</div>
                  <div class="notification-time">{{ formatTime(notification.created_at) }}</div>
                </div>
                <button 
                  v-if="!notification.read"
                  class="mark-read-btn"
                  @click="markAsRead(notification.id)"
                >
                  <i class="fas fa-check"></i>
                </button>
              </div>
            </div>
            
            <div class="notification-footer" v-if="notifications.length > 0">
              <button class="view-all-btn" @click="viewAllNotifications">
                View all notifications
              </button>
            </div>
          </div>
        </div>
        
        <!-- User profile -->
        <div class="user-container" ref="userContainer">
          <button class="user-btn" @click="toggleUserMenu">
            <img :src="userAvatar" :alt="userName" class="user-avatar">
            <span class="user-name" v-if="!isMobile">{{ userName }}</span>
            <i class="fas fa-chevron-down user-chevron"></i>
          </button>
          
          <!-- User menu dropdown -->
          <div v-if="showUserMenu" class="user-dropdown" :class="{ 'mobile': isMobile }">
            <div class="user-info">
              <img :src="userAvatar" :alt="userName" class="user-avatar-large">
              <div class="user-details">
                <div class="user-name">{{ userName }}</div>
                <div class="user-role">{{ userRole }}</div>
              </div>
            </div>
            
            <div class="user-menu-items">
              <router-link to="/profile" class="menu-item" @click="closeUserMenu">
                <i class="fas fa-user"></i>
                <span>Profile</span>
              </router-link>
              
              <router-link to="/settings" class="menu-item" @click="closeUserMenu">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
              </router-link>
              
              <router-link to="/profile/security" class="menu-item" @click="closeUserMenu">
                <i class="fas fa-shield-alt"></i>
                <span>Security</span>
              </router-link>
              
              <button class="menu-item" @click="toggleDarkMode">
                <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'"></i>
                <span>{{ darkMode ? 'Light Mode' : 'Dark Mode' }}</span>
              </button>
              
              <div class="menu-divider"></div>
              
              <button class="menu-item logout-btn" @click="logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useDarkMode } from '../Composables/useDarkMode';

const page = usePage();
const searchQuery = ref('');
const showNotifications = ref(false);
const showUserMenu = ref(false);
const unreadCount = ref(0);
const notifications = ref([]);
const isMobile = ref(false);

// Use dark mode composable
const { isDarkMode: darkMode, toggleDarkMode } = useDarkMode();

// PWA Install functionality
const showInstallButton = ref(false);
let deferredPrompt = null;

// Refs for dropdown containers
const notificationContainer = ref(null);
const userContainer = ref(null);

// Get user data from page props
const userName = computed(() => page.props.auth?.user?.name || 'User');
const userRole = computed(() => page.props.auth?.user?.roles?.[0]?.name || 'user');
const userAvatar = computed(() => {
  const name = userName.value;
  return `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=667eea&color=fff`;
});

// Determine if sidebar should be shown
const showSidebar = computed(() => {
  return userRole.value === 'admin';
});

// Check if PWA is already installed
const isPWAInstalled = () => {
  return window.matchMedia('(display-mode: standalone)').matches || 
         window.navigator.standalone || 
         document.referrer.includes('android-app://');
};

// Install PWA
const installPWA = async () => {
  if (!deferredPrompt) {
    console.log('No installation prompt available');
    return;
  }

  // Show the install prompt
  deferredPrompt.prompt();
  
  // Wait for the user to respond to the prompt
  const { outcome } = await deferredPrompt.userChoice;
  
  if (outcome === 'accepted') {
    console.log('User accepted the install prompt');
    showInstallButton.value = false;
  } else {
    console.log('User dismissed the install prompt');
  }
  
  // Clear the deferred prompt
  deferredPrompt = null;
};

// Toggle sidebar for mobile
const toggleSidebar = () => {
  // This would communicate with the sidebar component
  window.dispatchEvent(new CustomEvent('toggle-sidebar'));
};

// Perform search
const performSearch = () => {
  if (searchQuery.value.trim()) {
    // Implement search functionality
    console.log('Searching for:', searchQuery.value);
    searchQuery.value = '';
  }
};

// Toggle notifications dropdown
const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value;
  if (showNotifications.value) {
    showUserMenu.value = false;
    fetchNotifications();
  }
};

// Toggle user menu dropdown
const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
  if (showUserMenu.value) {
    showNotifications.value = false;
  }
};

// Close user menu
const closeUserMenu = () => {
  showUserMenu.value = false;
};

// Fetch notifications
const fetchNotifications = async () => {
  try {
    // In a real implementation, this would fetch from your API
    // For now, we'll use mock data
    notifications.value = [
      {
        id: 1,
        title: 'New Assignment',
        message: 'Math assignment due tomorrow',
        created_at: new Date(),
        read: false
      },
      {
        id: 2,
        title: 'Exam Schedule',
        message: 'Physics exam scheduled for next week',
        created_at: new Date(Date.now() - 86400000), // 1 day ago
        read: false
      }
    ];
    
    unreadCount.value = notifications.value.filter(n => !n.read).length;
  } catch (error) {
    console.error('Failed to fetch notifications:', error);
  }
};

// Mark notification as read
const markAsRead = (id) => {
  const notification = notifications.value.find(n => n.id === id);
  if (notification) {
    notification.read = true;
    unreadCount.value = notifications.value.filter(n => !n.read).length;
  }
};

// View all notifications
const viewAllNotifications = () => {
  // Navigate to notifications page
  window.location.href = '/notifications';
};

// Format time for display
const formatTime = (date) => {
  return new Date(date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

// The toggleDarkMode function is now provided by the composable

// Logout function
const logout = () => {
  if (confirm('Are you sure you want to logout?')) {
    // This would typically make an API call to logout
    window.location.href = '/logout';
  }
};

// Close dropdowns when clicking outside
const handleClickOutside = (event) => {
  if (notificationContainer.value && !notificationContainer.value.contains(event.target)) {
    showNotifications.value = false;
  }
  
  if (userContainer.value && !userContainer.value.contains(event.target)) {
    showUserMenu.value = false;
  }
};

// Handle window resize
const handleResize = () => {
  isMobile.value = window.innerWidth < 768;
};

// Initialize component
onMounted(() => {
  // Check dark mode preference
  darkMode.value = localStorage.getItem('darkMode') === 'true' || 
                  window.matchMedia('(prefers-color-scheme: dark)').matches;
  
  if (darkMode.value) {
    document.body.classList.add('dark');
  }
  
  // Check if mobile
  handleResize();
  window.addEventListener('resize', handleResize);
  
  // Add click outside listener
  document.addEventListener('click', handleClickOutside);
  
  // Fetch initial notifications
  fetchNotifications();
  
  // PWA Install Prompt Handling
  window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent the mini-infobar from appearing on mobile
    e.preventDefault();
    // Stash the event so it can be triggered later
    deferredPrompt = e;
    // Show install button if PWA is not already installed
    showInstallButton.value = !isPWAInstalled();
  });
  
  // Listen for successful installation
  window.addEventListener('appinstalled', (evt) => {
    // Hide the install button
    showInstallButton.value = false;
    console.log('PWA was installed');
  });
});

onUnmounted(() => {
  window.removeEventListener('resize', handleResize);
  document.removeEventListener('click', handleClickOutside);
});
</script>

<style scoped>
.top-app-bar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 60px;
  background: white;
  border-bottom: 1px solid #e5e7eb;
  z-index: 999;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  padding: 0 20px;
}

.top-app-bar.with-sidebar {
  left: 250px;
}

.top-app-bar.with-sidebar.collapsed {
  left: 60px;
}

.app-bar-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  max-width: 100%;
}

.menu-toggle {
  background: none;
  border: none;
  font-size: 1.25rem;
  color: #6b7280;
  cursor: pointer;
  padding: 8px;
  border-radius: 6px;
  margin-right: 10px;
  display: none;
}

.menu-toggle:hover {
  background: #f3f4f6;
}

.app-brand {
  display: flex;
  align-items: center;
  gap: 10px;
}

.brand-icon {
  color: #667eea;
  font-size: 1.5rem;
}

.brand-text {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1f2937;
}

.search-container {
  flex: 1;
  max-width: 400px;
  margin: 0 20px;
}

.search-box {
  position: relative;
  display: flex;
  align-items: center;
}

.search-icon {
  position: absolute;
  left: 12px;
  color: #9ca3af;
  font-size: 0.9rem;
}

.search-input {
  width: 100%;
  padding: 10px 15px 10px 35px;
  border: 1px solid #e5e7eb;
  border-radius: 20px;
  font-size: 0.9rem;
  transition: all 0.2s ease;
}

.search-input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.actions {
  display: flex;
  align-items: center;
  gap: 15px;
}

.action-btn {
  background: none;
  border: none;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #6b7280;
  cursor: pointer;
  position: relative;
  transition: all 0.2s ease;
}

.action-btn:hover {
  background: #f3f4f6;
  color: #1f2937;
}

.install-btn {
  color: #10b981;
}

.install-btn:hover {
  background: #dcfce7;
  color: #047857;
}

.notification-btn.has-unread::after {
  content: '';
  position: absolute;
  top: 8px;
  right: 8px;
  width: 8px;
  height: 8px;
  background: #ef4444;
  border-radius: 50%;
}

.notification-badge {
  position: absolute;
  top: 5px;
  right: 5px;
  background: #ef4444;
  color: white;
  font-size: 0.7rem;
  font-weight: 600;
  min-width: 18px;
  height: 18px;
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0 5px;
}

.notification-container,
.user-container {
  position: relative;
}

.notification-dropdown {
  position: absolute;
  top: 50px;
  right: 0;
  width: 350px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  border: 1px solid #e5e7eb;
  z-index: 1000;
  max-height: 80vh;
  display: flex;
  flex-direction: column;
}

.notification-dropdown.mobile {
  width: calc(100vw - 40px);
  max-width: 350px;
  right: auto;
  left: 50%;
  transform: translateX(-50%);
}

.notification-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 15px;
  border-bottom: 1px solid #e5e7eb;
}

.notification-header h3 {
  margin: 0;
  font-size: 1.1rem;
  font-weight: 600;
}

.close-btn {
  background: none;
  border: none;
  color: #6b7280;
  font-size: 1.1rem;
  cursor: pointer;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.close-btn:hover {
  background: #f3f4f6;
}

.notification-list {
  flex: 1;
  overflow-y: auto;
  max-height: 300px;
}

.no-notifications {
  padding: 30px;
  text-align: center;
  color: #6b7280;
}

.notification-item {
  display: flex;
  align-items: center;
  padding: 15px;
  border-bottom: 1px solid #f3f4f6;
  transition: background 0.2s ease;
}

.notification-item:hover {
  background: #f9fafb;
}

.notification-item.unread {
  background: #f0f9ff;
}

.notification-item.unread:hover {
  background: #e0f2fe;
}

.notification-content {
  flex: 1;
  min-width: 0;
}

.notification-title {
  font-weight: 600;
  margin-bottom: 5px;
  color: #1f2937;
}

.notification-message {
  font-size: 0.9rem;
  color: #6b7280;
  margin-bottom: 5px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.notification-time {
  font-size: 0.8rem;
  color: #9ca3af;
}

.mark-read-btn {
  background: none;
  border: none;
  color: #667eea;
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.mark-read-btn:hover {
  background: #e0f2fe;
}

.notification-footer {
  padding: 15px;
  border-top: 1px solid #e5e7eb;
  text-align: center;
}

.view-all-btn {
  background: none;
  border: none;
  color: #667eea;
  font-weight: 500;
  cursor: pointer;
  padding: 5px 10px;
  border-radius: 6px;
  transition: all 0.2s ease;
}

.view-all-btn:hover {
  background: #e0f2fe;
}

.user-btn {
  background: none;
  border: none;
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  padding: 5px;
  border-radius: 20px;
  transition: all 0.2s ease;
}

.user-btn:hover {
  background: #f3f4f6;
}

.user-avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  object-fit: cover;
}

.user-name {
  font-weight: 500;
  color: #1f2937;
}

.user-chevron {
  color: #6b7280;
  font-size: 0.8rem;
}

.user-dropdown {
  position: absolute;
  top: 50px;
  right: 0;
  width: 250px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  border: 1px solid #e5e7eb;
  z-index: 1000;
}

.user-dropdown.mobile {
  width: calc(100vw - 40px);
  max-width: 250px;
  right: auto;
  left: 50%;
  transform: translateX(-50%);
}

.user-info {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 20px;
  border-bottom: 1px solid #e5e7eb;
}

.user-avatar-large {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  object-fit: cover;
}

.user-details {
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.user-details .user-name {
  font-weight: 600;
  font-size: 1rem;
  margin-bottom: 2px;
}

.user-details .user-role {
  font-size: 0.85rem;
  color: #6b7280;
}

.user-menu-items {
  padding: 10px 0;
}

.menu-item {
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
  padding: 12px 20px;
  text-decoration: none;
  color: #1f2937;
  background: none;
  border: none;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.menu-item:hover {
  background: #f9fafb;
}

.menu-item i {
  width: 20px;
  text-align: center;
  color: #6b7280;
}

.logout-btn {
  color: #ef4444;
}

.logout-btn i {
  color: #ef4444;
}

.menu-divider {
  height: 1px;
  background: #e5e7eb;
  margin: 5px 0;
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
  .top-app-bar.with-sidebar {
    left: 0;
  }
  
  .menu-toggle {
    display: flex;
  }
  
  .brand-text {
    display: none;
  }
  
  .search-container {
    display: none;
  }
  
  .user-name {
    display: none;
  }
  
  .user-chevron {
    display: none;
  }
  
  .user-btn {
    padding: 5px;
    border-radius: 50%;
  }
  
  .user-avatar {
    width: 36px;
    height: 36px;
  }
}

/* Dark mode support */
.dark .top-app-bar {
  background: #1f2937;
  border-bottom-color: #374151;
}

.dark .brand-text {
  color: #f9fafb;
}

.dark .search-input {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
}

.dark .search-input:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
}

.dark .action-btn {
  color: #9ca3af;
}

.dark .action-btn:hover {
  background: #374151;
  color: #f9fafb;
}

.dark .install-btn {
  color: #22c55e;
}

.dark .install-btn:hover {
  background: #166534;
  color: #bbf7d0;
}

.dark .notification-dropdown,
.dark .user-dropdown {
  background: #1f2937;
  border-color: #374151;
}

.dark .notification-header {
  border-bottom-color: #374151;
}

.dark .notification-item {
  border-bottom-color: #374151;
}

.dark .notification-item:hover {
  background: #374151;
}

.dark .notification-item.unread {
  background: #1e3a8a;
}

.dark .notification-item.unread:hover {
  background: #1e40af;
}

.dark .notification-title {
  color: #f9fafb;
}

.dark .notification-message {
  color: #d1d5db;
}

.dark .notification-time {
  color: #9ca3af;
}

.dark .mark-read-btn:hover {
  background: #1e40af;
}

.dark .notification-footer {
  border-top-color: #374151;
}

.dark .view-all-btn:hover {
  background: #1e40af;
}

.dark .close-btn:hover {
  background: #374151;
}

.dark .user-info {
  border-bottom-color: #374151;
}

.dark .user-details .user-name {
  color: #f9fafb;
}

.dark .user-details .user-role {
  color: #9ca3af;
}

.dark .menu-item {
  color: #f9fafb;
}

.dark .menu-item:hover {
  background: #374151;
}

.dark .menu-divider {
  background: #374151;
}
</style>