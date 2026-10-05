<template>
  <button 
    class="dark-mode-toggle"
    :class="{ 'dark': isDarkMode }"
    @click="toggleDarkMode"
    :aria-label="isDarkMode ? 'Switch to light mode' : 'Switch to dark mode'"
    :title="isDarkMode ? 'Switch to light mode' : 'Switch to dark mode'"
  >
    <div class="toggle-container">
      <div class="toggle-thumb">
        <i :class="isDarkMode ? 'fas fa-sun' : 'fas fa-moon'"></i>
      </div>
    </div>
  </button>
</template>

<script setup>
import { useDarkMode } from '../Composables/useDarkMode';

const { isDarkMode, toggleDarkMode } = useDarkMode();
</script>

<style scoped>
.dark-mode-toggle {
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
  width: 50px;
  height: 26px;
  position: relative;
  border-radius: 13px;
  background: #e5e7eb;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: flex-start;
  outline: none;
}

.dark-mode-toggle:hover {
  background: #d1d5db;
}

.dark-mode-toggle.dark {
  background: #4b5563;
  justify-content: flex-end;
}

.dark-mode-toggle.dark:hover {
  background: #374151;
}

.toggle-container {
  width: 100%;
  height: 100%;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: flex-start;
}

.dark-mode-toggle.dark .toggle-container {
  justify-content: flex-end;
}

.toggle-thumb {
  width: 22px;
  height: 22px;
  border-radius: 50%;
  background: white;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
  margin: 2px;
}

.toggle-thumb i {
  font-size: 0.75rem;
  color: #f59e0b;
}

.dark-mode-toggle.dark .toggle-thumb i {
  color: #fbbf24;
}

/* Dark mode support for the toggle itself */
.dark .dark-mode-toggle {
  background: #4b5563;
}

.dark .dark-mode-toggle:hover {
  background: #374151;
}

.dark .dark-mode-toggle.dark {
  background: #e5e7eb;
}

.dark .dark-mode-toggle.dark:hover {
  background: #d1d5db;
}

.dark .toggle-thumb {
  background: #1f2937;
}

.dark .toggle-thumb i {
  color: #fbbf24;
}

.dark .dark-mode-toggle.dark .toggle-thumb i {
  color: #f59e0b;
}

/* Focus styles for accessibility */
.dark-mode-toggle:focus-visible {
  outline: 2px solid #667eea;
  outline-offset: 2px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .dark-mode-toggle {
    width: 45px;
    height: 24px;
  }
  
  .toggle-thumb {
    width: 20px;
    height: 20px;
  }
  
  .toggle-thumb i {
    font-size: 0.7rem;
  }
}
</style>