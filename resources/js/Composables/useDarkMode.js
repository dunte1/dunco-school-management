import { ref, onMounted, watch } from 'vue';

export function useDarkMode() {
  const isDarkMode = ref(false);

  // Initialize dark mode
  const initDarkMode = () => {
    // Check localStorage first
    const savedMode = localStorage.getItem('darkMode');
    if (savedMode !== null) {
      isDarkMode.value = savedMode === 'true';
    } else {
      // Check system preference
      isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    
    // Apply dark mode class to body
    if (isDarkMode.value) {
      document.body.classList.add('dark');
    } else {
      document.body.classList.remove('dark');
    }
  };

  // Toggle dark mode
  const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    localStorage.setItem('darkMode', isDarkMode.value);
    
    if (isDarkMode.value) {
      document.body.classList.add('dark');
    } else {
      document.body.classList.remove('dark');
    }
  };

  // Set dark mode
  const setDarkMode = (value) => {
    isDarkMode.value = value;
    localStorage.setItem('darkMode', value);
    
    if (value) {
      document.body.classList.add('dark');
    } else {
      document.body.classList.remove('dark');
    }
  };

  // Watch for system preference changes
  const watchSystemPreference = () => {
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    
    const handleSystemChange = (e) => {
      // Only update if user hasn't explicitly set a preference
      if (localStorage.getItem('darkMode') === null) {
        isDarkMode.value = e.matches;
        if (e.matches) {
          document.body.classList.add('dark');
        } else {
          document.body.classList.remove('dark');
        }
      }
    };
    
    mediaQuery.addEventListener('change', handleSystemChange);
    
    return () => {
      mediaQuery.removeEventListener('change', handleSystemChange);
    };
  };

  // Initialize on mount
  onMounted(() => {
    initDarkMode();
    watchSystemPreference();
  });

  // Watch for changes and update localStorage
  watch(isDarkMode, (newValue) => {
    localStorage.setItem('darkMode', newValue);
  });

  return {
    isDarkMode,
    toggleDarkMode,
    setDarkMode
  };
}