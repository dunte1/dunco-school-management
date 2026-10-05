import { ref, onMounted, onUnmounted } from 'vue';
import { 
  requestNotificationPermission, 
  handleForegroundMessages,
  registerDeviceWithBackend,
  unregisterDeviceWithBackend,
  initializeFirebaseMessaging
} from '../firebase';

export function usePushNotifications() {
  const isSupported = ref(false);
  const permissionStatus = ref('default'); // 'default', 'granted', 'denied'
  const fcmToken = ref(null);
  const isSubscribed = ref(false);
  const notificationCount = ref(0);
  const notifications = ref([]);

  // Check if push notifications are supported
  const checkSupport = () => {
    if ('serviceWorker' in navigator && 'PushManager' in window) {
      isSupported.value = true;
    }
  };

  // Request notification permission
  const requestPermission = async () => {
    if (!isSupported.value) {
      console.warn('Push notifications not supported');
      return false;
    }

    try {
      const token = await requestNotificationPermission();
      if (token) {
        permissionStatus.value = 'granted';
        fcmToken.value = token;
        
        // Register device with backend
        const result = await registerDeviceWithBackend(token);
        if (result) {
          isSubscribed.value = true;
        }
        
        return true;
      } else {
        permissionStatus.value = 'denied';
        return false;
      }
    } catch (error) {
      console.error('Error requesting notification permission:', error);
      permissionStatus.value = 'denied';
      return false;
    }
  };

  // Unsubscribe from push notifications
  const unsubscribe = async () => {
    if (!fcmToken.value) return;

    try {
      const result = await unregisterDeviceWithBackend(fcmToken.value);
      if (result) {
        isSubscribed.value = false;
        fcmToken.value = null;
        permissionStatus.value = 'default';
      }
    } catch (error) {
      console.error('Error unsubscribing from push notifications:', error);
    }
  };

  // Handle incoming messages
  const handleMessage = (event) => {
    const { title, options, data } = event.detail;
    
    // Add to notifications list
    notifications.value.unshift({
      id: Date.now(),
      title,
      body: options.body,
      icon: options.icon,
      data,
      timestamp: new Date(),
      read: false
    });
    
    // Increment notification count
    notificationCount.value++;
    
    // Dispatch custom event for other components
    window.dispatchEvent(new CustomEvent('notification-received', {
      detail: { title, body: options.body, data }
    }));
  };

  // Mark notification as read
  const markAsRead = (id) => {
    const notification = notifications.value.find(n => n.id === id);
    if (notification) {
      notification.read = true;
      notificationCount.value = Math.max(0, notificationCount.value - 1);
    }
  };

  // Mark all notifications as read
  const markAllAsRead = () => {
    notifications.value.forEach(notification => {
      notification.read = true;
    });
    notificationCount.value = 0;
  };

  // Clear all notifications
  const clearNotifications = () => {
    notifications.value = [];
    notificationCount.value = 0;
  };

  // Initialize push notifications
  const init = () => {
    checkSupport();
    
    if (isSupported.value) {
      // Initialize Firebase messaging
      initializeFirebaseMessaging();
      
      // Listen for foreground messages
      handleForegroundMessages();
      
      // Listen for custom firebase message events
      window.addEventListener('firebase-message', handleMessage);
      
      // Check current permission status
      if (Notification.permission) {
        permissionStatus.value = Notification.permission;
      }
    }
  };

  // Cleanup
  const cleanup = () => {
    window.removeEventListener('firebase-message', handleMessage);
  };

  // Initialize on mount
  onMounted(() => {
    init();
  });

  // Cleanup on unmount
  onUnmounted(() => {
    cleanup();
  });

  return {
    // Reactive state
    isSupported,
    permissionStatus,
    fcmToken,
    isSubscribed,
    notificationCount,
    notifications,
    
    // Methods
    requestPermission,
    unsubscribe,
    markAsRead,
    markAllAsRead,
    clearNotifications
  };
}