import ApiService from './ApiService';

class ParentService {
  // Get children
  async getChildren() {
    return ApiService.get('/children');
  }

  // Get child attendance
  async getChildAttendance(childId) {
    return ApiService.get(`/children/${childId}/attendance`);
  }

  // Get child results
  async getChildResults(childId) {
    return ApiService.get(`/children/${childId}/results`);
  }

  // Get child fees
  async getChildFees(childId) {
    return ApiService.get(`/children/${childId}/fees`);
  }

  // Get parent dashboard data
  async getDashboardData() {
    return ApiService.get('/dashboard');
  }

  // Get announcements
  async getAnnouncements() {
    return ApiService.get('/announcements');
  }

  // Get a specific announcement
  async getAnnouncement(id) {
    return ApiService.get(`/announcements/${id}`);
  }

  // Mark announcement as read
  async markAnnouncementRead(id) {
    return ApiService.post(`/announcements/${id}/mark-read`);
  }

  // Get notifications
  async getNotifications() {
    return ApiService.get('/notifications');
  }

  // Mark notification as read
  async markNotificationRead(notificationId) {
    return ApiService.post(`/notifications/mark-read`, { notification_id: notificationId });
  }
}

// Export singleton instance
export default new ParentService();