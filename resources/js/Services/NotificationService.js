// Notification service for handling push notifications and in-app notifications
class NotificationService {
  constructor() {
    this.listeners = {};
    this.notificationCount = 0;
    this.notifications = [];
  }

  // Subscribe to notification events
  subscribe(event, callback) {
    if (!this.listeners[event]) {
      this.listeners[event] = [];
    }
    this.listeners[event].push(callback);
  }

  // Unsubscribe from notification events
  unsubscribe(event, callback) {
    if (this.listeners[event]) {
      this.listeners[event] = this.listeners[event].filter(cb => cb !== callback);
    }
  }

  // Emit notification events
  emit(event, data) {
    if (this.listeners[event]) {
      this.listeners[event].forEach(callback => callback(data));
    }
  }

  // Register device for push notifications
  async registerDevice(token, platform = 'web') {
    try {
      const response = await fetch('/api/mobile/v1/device/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          token: token,
          platform: platform,
          device_id: this.generateDeviceId(),
          app_version: '1.0.0',
          firebase_token: token
        })
      });

      if (!response.ok) {
        throw new Error('Failed to register device');
      }

      const data = await response.json();
      this.emit('deviceRegistered', data);
      return data;
    } catch (error) {
      console.error('Error registering device:', error);
      this.emit('registrationError', error);
      throw error;
    }
  }

  // Unregister device from push notifications
  async unregisterDevice(token) {
    try {
      const response = await fetch('/api/mobile/v1/device/unregister', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          token: token
        })
      });

      if (!response.ok) {
        throw new Error('Failed to unregister device');
      }

      const data = await response.json();
      this.emit('deviceUnregistered', data);
      return data;
    } catch (error) {
      console.error('Error unregistering device:', error);
      this.emit('unregistrationError', error);
      throw error;
    }
  }

  // Fetch unread notification count
  async fetchUnreadCount() {
    try {
      const response = await fetch('/api/notifications/unread-count', {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      if (!response.ok) {
        throw new Error('Failed to fetch unread count');
      }

      const data = await response.json();
      this.notificationCount = data.count || 0;
      this.emit('unreadCountUpdated', this.notificationCount);
      return this.notificationCount;
    } catch (error) {
      console.error('Error fetching unread count:', error);
      this.emit('unreadCountError', error);
      return 0;
    }
  }

  // Fetch notifications
  async fetchNotifications(limit = 20) {
    try {
      const response = await fetch(`/api/mobile/v1/notifications?limit=${limit}`, {
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      if (!response.ok) {
        throw new Error('Failed to fetch notifications');
      }

      const data = await response.json();
      this.notifications = data.notifications || [];
      this.emit('notificationsUpdated', this.notifications);
      return this.notifications;
    } catch (error) {
      console.error('Error fetching notifications:', error);
      this.emit('notificationsError', error);
      return [];
    }
  }

  // Mark notification as read
  async markAsRead(notificationId) {
    try {
      const response = await fetch(`/api/mobile/v1/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      if (!response.ok) {
        throw new Error('Failed to mark notification as read');
      }

      const data = await response.json();
      
      // Update local notification
      const notification = this.notifications.find(n => n.id === notificationId);
      if (notification) {
        notification.read = true;
      }
      
      // Decrement notification count
      this.notificationCount = Math.max(0, this.notificationCount - 1);
      
      this.emit('notificationRead', { notificationId, data });
      this.emit('unreadCountUpdated', this.notificationCount);
      
      return data;
    } catch (error) {
      console.error('Error marking notification as read:', error);
      this.emit('markReadError', error);
      throw error;
    }
  }

  // Mark all notifications as read
  async markAllAsRead() {
    try {
      const response = await fetch('/api/mobile/v1/notifications/mark-read', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ all: true })
      });

      if (!response.ok) {
        throw new Error('Failed to mark all notifications as read');
      }

      const data = await response.json();
      
      // Update all local notifications
      this.notifications.forEach(notification => {
        notification.read = true;
      });
      
      // Reset notification count
      this.notificationCount = 0;
      
      this.emit('allNotificationsRead', data);
      this.emit('unreadCountUpdated', this.notificationCount);
      
      return data;
    } catch (error) {
      console.error('Error marking all notifications as read:', error);
      this.emit('markAllReadError', error);
      throw error;
    }
  }

  // Send a test notification
  async sendTestNotification() {
    try {
      const response = await fetch('/api/mobile/v1/websocket/test-broadcast', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          title: 'Test Notification',
          body: 'This is a test notification from the PWA',
          data: { type: 'test' }
        })
      });

      if (!response.ok) {
        throw new Error('Failed to send test notification');
      }

      const data = await response.json();
      this.emit('testNotificationSent', data);
      return data;
    } catch (error) {
      console.error('Error sending test notification:', error);
      this.emit('testNotificationError', error);
      throw error;
    }
  }

  // Generate a unique device ID
  generateDeviceId() {
    return 'web-' + btoa(navigator.userAgent + Date.now()).slice(0, 32);
  }

  // Get current notification count
  getNotificationCount() {
    return this.notificationCount;
  }

  // Get current notifications
  getNotifications() {
    return this.notifications;
  }
}

// Export singleton instance
export default new NotificationService();