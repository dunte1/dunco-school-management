import ApiService from './ApiService';

class AdminService {
  // Get admin dashboard data
  async getDashboardData() {
    return ApiService.get('/dashboard');
  }

  // Get schools
  async getSchools() {
    return ApiService.get('/schools');
  }

  // Get a specific school
  async getSchool(id) {
    return ApiService.get(`/schools/${id}`);
  }

  // Get users
  async getUsers(params = {}) {
    return ApiService.get('/users', params);
  }

  // Get a specific user
  async getUser(id) {
    return ApiService.get(`/users/${id}`);
  }

  // Create a user
  async createUser(data) {
    return ApiService.post('/users', data);
  }

  // Update a user
  async updateUser(id, data) {
    return ApiService.put(`/users/${id}`, data);
  }

  // Delete a user
  async deleteUser(id) {
    return ApiService.delete(`/users/${id}`);
  }

  // Get roles
  async getRoles() {
    return ApiService.get('/roles');
  }

  // Get attendance alert rules
  async getAttendanceAlertRules() {
    return ApiService.get('/attendance/alert-rules');
  }

  // Create attendance alert rule
  async createAttendanceAlertRule(data) {
    return ApiService.post('/attendance/alert-rules', data);
  }

  // Update attendance alert rule
  async updateAttendanceAlertRule(id, data) {
    return ApiService.put(`/attendance/alert-rules/${id}`, data);
  }

  // Delete attendance alert rule
  async deleteAttendanceAlertRule(id) {
    return ApiService.delete(`/attendance/alert-rules/${id}`);
  }

  // Trigger attendance alert rules
  async triggerAttendanceAlertRules() {
    return ApiService.post('/attendance/alert-rules-trigger');
  }

  // Get finance data
  async getFinanceData() {
    return ApiService.get('/finance/summary');
  }

  // Get payments
  async getPayments() {
    return ApiService.get('/finance/payments');
  }

  // Get exams
  async getExams() {
    return ApiService.get('/exams');
  }

  // Create exam
  async createExam(data) {
    return ApiService.post('/exams', data);
  }

  // Update exam
  async updateExam(id, data) {
    return ApiService.put(`/exams/${id}`, data);
  }

  // Delete exam
  async deleteExam(id) {
    return ApiService.delete(`/exams/${id}`);
  }

  // Get reports
  async getReports() {
    return ApiService.get('/reports');
  }

  // Get analytics
  async getAnalytics() {
    return ApiService.get('/analytics');
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
export default new AdminService();
