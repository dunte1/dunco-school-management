import ApiService from './ApiService';

class TeacherService {
  // Get teacher dashboard data
  async getDashboardData() {
    return ApiService.get('/dashboard');
  }

  // Get teacher timetable for today
  async getTodayTimetable() {
    return ApiService.get('/timetable/today');
  }

  // Get teacher timetable for the week
  async getWeekTimetable() {
    return ApiService.get('/timetable/week');
  }

  // Get staff attendance analytics
  async getAttendanceAnalytics() {
    return ApiService.get('/attendance/analytics/staff');
  }

  // Get past attendance records
  async getPastAttendanceRecords() {
    return ApiService.get('/attendance/past-records');
  }

  // Update past attendance record
  async updatePastAttendanceRecord(id, data) {
    return ApiService.put(`/attendance/past-records/${id}`, data);
  }

  // Get attendance heatmap
  async getAttendanceHeatmap() {
    return ApiService.get('/attendance/heatmap');
  }

  // Send bulk attendance notifications
  async sendBulkAttendanceNotifications(data) {
    return ApiService.post('/attendance/notifications/bulk', data);
  }

  // Send X days absent alerts
  async sendXDaysAbsentAlerts(data) {
    return ApiService.post('/attendance/notifications/x-days-absent', data);
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
export default new TeacherService();