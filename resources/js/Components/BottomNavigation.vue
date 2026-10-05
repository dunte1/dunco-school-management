<template>
  <div class="bottom-navigation" v-if="showBottomNav" :class="{ 'dark': isDarkMode }">
    <div class="nav-container">
      <router-link 
        v-for="item in navigationItems" 
        :key="item.id"
        :to="item.route"
        class="nav-item"
        :class="{ active: isActiveRoute(item.route) }"
        :title="item.name"
      >
        <i :class="item.icon"></i>
        <span class="nav-label">{{ item.name }}</span>
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { getNavigationItems } from '../navigation/roles';
import { useDarkMode } from '../Composables/useDarkMode';

const page = usePage();

// Use dark mode composable
const { isDarkMode } = useDarkMode();

// Get user role from page props
const userRole = computed(() => {
  return page.props.auth?.user?.roles?.[0]?.name || 'student';
});

// Determine if bottom navigation should be shown
const showBottomNav = computed(() => {
  return ['student', 'teacher', 'parent'].includes(userRole.value);
});

// Get navigation items based on user role
const navigationItems = computed(() => {
  return getNavigationItems(userRole.value);
});

// Check if current route is active
const isActiveRoute = (route) => {
  return window.location.pathname === route;
};

// Add body padding to account for bottom navigation
onMounted(() => {
  if (showBottomNav.value) {
    document.body.style.paddingBottom = '70px';
  }
});
</script>

<style scoped>
.bottom-navigation {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  background: white;
  border-top: 1px solid #e5e7eb;
  z-index: 1000;
  box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
}

.nav-container {
  display: flex;
  justify-content: space-around;
  align-items: center;
  height: 60px;
  max-width: 100%;
  margin: 0 auto;
}

.nav-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  flex: 1;
  height: 100%;
  text-decoration: none;
  color: #6b7280;
  transition: all 0.2s ease;
  font-size: 0.75rem;
}

.nav-item:hover {
  color: #667eea;
}

.nav-item.active {
  color: #667eea;
  font-weight: 600;
}

.nav-item i {
  font-size: 1.25rem;
  margin-bottom: 2px;
}

.nav-label {
  margin-top: 2px;
}

/* Responsive adjustments */
@media (max-width: 480px) {
  .nav-container {
    padding: 0 5px;
  }
  
  .nav-item {
    font-size: 0.65rem;
  }
  
  .nav-item i {
    font-size: 1rem;
  }
}

/* Dark mode support */
.dark .bottom-navigation {
  background: #1f2937;
  border-top-color: #374151;
}

.dark .nav-item {
  color: #9ca3af;
}

.dark .nav-item:hover {
  color: #a5b4fc;
}

.dark .nav-item.active {
  color: #a5b4fc;
}
</style>