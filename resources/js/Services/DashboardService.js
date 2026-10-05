import ApiService from './ApiService';

class DashboardService {
  // Get dashboard data based on user role
  async getDashboardData() {
    return ApiService.get('/dashboard');
  }

  // Get student attendance percentage
  async getStudentAttendancePct(userId, start, end) {
    const params = {
      user_id: userId,
      start: start.toISOString(),
      end: end.toISOString()
    };
    return ApiService.get('/attendance/summary', params);
  }
}

// Export singleton instance
export default new DashboardService();