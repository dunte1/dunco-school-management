<template>
  <div class="app-layout">
    <!-- Top App Bar -->
    <TopAppBar />
    
    <!-- Sidebar Navigation (Admin only) -->
    <SidebarNavigation />
    
    <!-- Main Content -->
    <main class="main-content" :class="{ 'with-sidebar': showSidebar }">
      <slot />
    </main>
    
    <!-- Bottom Navigation (Students, Teachers, Parents) -->
    <BottomNavigation />
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import TopAppBar from '../Components/TopAppBar.vue';
import SidebarNavigation from '../Components/SidebarNavigation.vue';
import BottomNavigation from '../Components/BottomNavigation.vue';

const page = usePage();

// Determine if sidebar should be shown (admin users)
const showSidebar = computed(() => {
  const userRole = page.props.auth?.user?.roles?.[0]?.name || 'student';
  return userRole === 'admin';
});
</script>

<style scoped>
.app-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  padding-top: 60px; /* Height of top app bar */
}

.main-content {
  flex: 1;
  padding: 20px;
  margin-bottom: 0; /* Will be adjusted by bottom navigation if visible */
  transition: margin-left 0.3s ease;
}

.main-content.with-sidebar {
  margin-left: 250px;
}

/* When sidebar is collapsed */
.main-content.with-sidebar.collapsed {
  margin-left: 60px;
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
  .main-content.with-sidebar {
    margin-left: 0;
  }
  
  .main-content {
    padding: 15px;
  }
}

/* Adjust for bottom navigation on mobile devices */
@media (max-width: 768px) {
  .app-layout {
    padding-bottom: 60px; /* Height of bottom navigation */
  }
}
</style>