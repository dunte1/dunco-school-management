# API Services Documentation

This directory contains service classes that handle communication with the Laravel backend API. Each service is designed to encapsulate API calls for specific functionality.

## Services Overview

### ApiService.js
The base service that handles all HTTP requests. It includes:
- Authentication token management
- Default headers configuration
- Generic request methods (GET, POST, PUT, DELETE)
- Error handling

### AuthService.js
Handles user authentication and profile management:
- User login/logout
- Password reset functionality
- 2FA setup and verification
- Biometric authentication
- User profile management

### DashboardService.js
Handles dashboard data retrieval for all user roles:
- Role-specific dashboard data
- User statistics and metrics

### StudentService.js
Manages student-specific functionality:
- Timetable retrieval (daily/weekly)
- Attendance data
- Exam results and schedules
- Finance information (fees, payments)
- Library data (books, borrowing)
- Announcements and notifications

### ParentService.js
Manages parent-specific functionality:
- Child information retrieval
- Child attendance and results
- Child fee management
- Parent dashboard data
- Announcements and notifications

### TeacherService.js
Manages teacher-specific functionality:
- Teacher dashboard data
- Timetable retrieval
- Attendance management
- Past records management
- Announcements and notifications

### AdminService.js
Manages administrator functionality:
- System dashboard data
- School management
- User management
- Role management
- Attendance alert rules
- Finance data
- Exam management
- Reporting
- Announcements and notifications

### NotificationService.js
Handles push notifications and in-app notifications:
- Device registration for push notifications
- Notification retrieval and management
- Unread notification counting

## Usage Examples

### Importing Services
```javascript
import { AuthService, StudentService } from './Services';
```

### Authentication
```javascript
// Login
const loginData = await AuthService.login({ email, password });

// Get current user
const user = await AuthService.getMe();

// Logout
await AuthService.logout();
```

### Student Data
```javascript
// Get today's timetable
const timetable = await StudentService.getTodayTimetable();

// Get upcoming exams
const exams = await StudentService.getUpcomingExams();

// Get fee balances
const balances = await StudentService.getFeeBalances();
```

## Error Handling
All services throw errors that should be caught and handled appropriately:
```javascript
try {
  const data = await StudentService.getTodayTimetable();
  // Handle success
} catch (error) {
  console.error('Error fetching timetable:', error);
  // Handle error (show message to user, etc.)
}
```

## Token Management
The ApiService automatically manages authentication tokens:
- Tokens are stored in localStorage upon login
- Tokens are automatically included in authenticated requests
- Tokens are cleared during logout

## Adding New Services
To add a new service:
1. Create a new file in this directory
2. Extend the base ApiService or create a new class
3. Export the service in the index.js file
4. Import and use the service in your components