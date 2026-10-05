import ApiService from './ApiService';
import AuthService from './AuthService';
import DashboardService from './DashboardService';
import StudentService from './StudentService';
import ParentService from './ParentService';
import TeacherService from './TeacherService';
import AdminService from './AdminService';
import NotificationService from './NotificationService';

export {
  ApiService,
  AuthService,
  DashboardService,
  StudentService,
  ParentService,
  TeacherService,
  AdminService,
  NotificationService
};

// Initialize API service with stored token if available
const token = localStorage.getItem('authToken');
if (token) {
  ApiService.setToken(token);
}