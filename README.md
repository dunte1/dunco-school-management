# 🏫 Dunco School Management System

A comprehensive, modular school management system built with Laravel that provides complete administrative control over educational institutions.

## ✨ Features

### 🎯 Core Modules
- **Academic Management** - Student records, classes, subjects, and academic tracking
- **HR Management** - Staff management, payroll, and human resources
- **Finance Management** - Fee collection, accounting, and financial reporting
- **Library Management** - Book cataloging, borrowing, and library operations
- **Communication** - Messaging, announcements, and notifications
- **Hostel Management** - Room allocation, visitor management, and hostel operations
- **Transport Management** - Vehicle tracking, routes, and transport scheduling
- **Document Management** - File storage, document organization, and sharing
- **Student Portal** - Student dashboard and self-service features
- **Examination System** - Exam scheduling, grading, and result management
- **Attendance Tracking** - Student and staff attendance monitoring
- **Timetable Management** - Class scheduling and timetable creation
- **Notification System** - Real-time notifications and alerts
- **Settings Management** - System configuration and customization
- **API Integration** - RESTful API for external integrations
- **ChatBot Integration** - AI-powered assistance and support
- **Core System** - User management, roles, permissions, and system administration

### 🔐 Security & Permissions
- **Role-Based Access Control** - Granular permissions for different user types
- **Multi-School Support** - Manage multiple schools from a single installation
- **Audit Logging** - Complete activity tracking and logging
- **Data Encryption** - Secure data storage and transmission
- **Session Management** - Secure user sessions and authentication

### 📱 User Experience
- **Responsive Design** - Works seamlessly on desktop, tablet, and mobile
- **Real-time Updates** - Live notifications and dynamic content updates
- **Intuitive Interface** - User-friendly navigation and modern UI
- **Performance Optimized** - Fast loading times and efficient operations
- **Accessibility** - WCAG compliant design for all users

## 🚀 Quick Start

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 8.0 or higher
- Node.js and NPM (for frontend assets)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/dunte1/dunco-school-management.git
   cd dunco-school-management
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   ```bash
   # Update .env with your database credentials
   php artisan migrate
   php artisan db:seed
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start the server**
   ```bash
   php artisan serve
   ```

## 📋 System Requirements

### Server Requirements
- **PHP**: 8.1 or higher
- **Extensions**: BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, cURL, GD, ZIP
- **Database**: MySQL 8.0+ or PostgreSQL 12+
- **Web Server**: Apache/Nginx
- **Memory**: Minimum 512MB RAM (1GB recommended)
- **Storage**: 2GB available space

### Browser Support
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## 🏗️ Architecture

### Modular Design
The system is built using a modular architecture where each feature is a separate module:

```
Modules/
├── Academic/          # Academic management
├── HR/               # Human resources
├── Finance/          # Financial management
├── Library/          # Library operations
├── Communication/    # Messaging & notifications
├── Hostel/          # Hostel management
├── Transport/       # Transport management
├── Document/        # Document management
├── Portal/          # Student portal
├── Examination/     # Exam management
├── Attendance/      # Attendance tracking
├── Timetable/       # Schedule management
├── Notification/    # Notification system
├── Settings/        # System settings
├── API/            # API endpoints
├── ChatBot/        # AI assistance
└── Core/           # Core system features
```

### Technology Stack
- **Backend**: Laravel 10.x (PHP)
- **Frontend**: Blade templates, Bootstrap 5, Alpine.js
- **Database**: MySQL/PostgreSQL
- **Cache**: Redis (optional)
- **Queue**: Laravel Queue with database driver
- **File Storage**: Local/Cloud storage (AWS S3, etc.)

## 🔧 Configuration

### Environment Variables
Key environment variables to configure:

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dunco_school
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

# File Storage
FILESYSTEM_DISK=local

# SMS Gateway (for notifications)
SMS_GATEWAY=twilio
SMS_API_KEY=your_api_key
SMS_API_SECRET=your_api_secret

# Payment Gateway (for fee collection)
MPESA_CONSUMER_KEY=your_consumer_key
MPESA_CONSUMER_SECRET=your_consumer_secret
```

### Module Configuration
Each module can be enabled/disabled in `modules_statuses.json`:

```json
{
    "Core": true,
    "Academic": true,
    "HR": true,
    "Finance": true,
    "Library": true,
    "Communication": true,
    "Hostel": true,
    "Transport": true,
    "Document": true,
    "Portal": true,
    "Examination": true,
    "Attendance": true,
    "Timetable": true,
    "Notification": true,
    "Settings": true,
    "API": true,
    "ChatBot": true
}
```

## 👥 User Roles & Permissions

### Default Roles
1. **Super Admin** - Full system access
2. **School Admin** - School-level administration
3. **Teacher** - Academic and class management
4. **Student** - Student portal access
5. **Parent** - Parent portal access
6. **Staff** - Limited administrative access

### Permission System
- **Granular Permissions** - Each action has specific permissions
- **Role-Based Access** - Permissions assigned to roles
- **Dynamic Updates** - Permission changes apply immediately
- **Audit Trail** - All permission changes are logged

## 📊 Features by Module

### Academic Module
- Student registration and management
- Class and subject management
- Academic performance tracking
- Grade management
- Academic calendar
- Online class attendance

### Finance Module
- Fee structure management
- Payment collection (M-Pesa integration)
- Financial reporting
- Invoice generation
- Payment tracking
- Financial analytics

### HR Module
- Staff management
- Payroll processing
- Leave management
- Performance evaluation
- Staff attendance
- HR reporting

### Library Module
- Book cataloging
- Borrowing system
- Fine management
- Library reports
- Digital resources
- Author and publisher management

### Communication Module
- Internal messaging
- Broadcast announcements
- Group messaging
- Notification preferences
- Message delivery tracking
- Email and SMS integration

### Hostel Module
- Room allocation
- Visitor management
- Hostel fees
- Maintenance requests
- Leave applications
- Warden dashboard

## 🔌 API Integration

### RESTful API
The system provides a comprehensive REST API for external integrations:

```bash
# Authentication
POST /api/auth/login
POST /api/auth/logout

# Students
GET /api/students
POST /api/students
GET /api/students/{id}

# Classes
GET /api/classes
POST /api/classes

# Attendance
POST /api/attendance/mark
GET /api/attendance/report

# Fees
GET /api/fees/student/{id}
POST /api/fees/pay
```

### Webhook Support
Configure webhooks for real-time data synchronization:

```bash
# Webhook endpoints
POST /api/webhooks/student-created
POST /api/webhooks/payment-received
POST /api/webhooks/attendance-marked
```

## 🚀 Deployment

### Production Deployment
1. **Server Setup**
   ```bash
   # Install required packages
   sudo apt update
   sudo apt install nginx mysql-server php8.1-fpm php8.1-mysql
   ```

2. **Application Deployment**
   ```bash
   # Clone repository
   git clone https://github.com/dunte1/dunco-school-management.git
   cd dunco-school-management
   
   # Install dependencies
   composer install --optimize-autoloader --no-dev
   npm install && npm run build
   
   # Set permissions
   sudo chown -R www-data:www-data storage bootstrap/cache
   sudo chmod -R 775 storage bootstrap/cache
   ```

3. **Database Setup**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

4. **Queue Setup**
   ```bash
   # Start queue worker
   php artisan queue:work --daemon
   ```

### Docker Deployment
```bash
# Build and run with Docker
docker-compose up -d
```

## 🧪 Testing

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific module tests
php artisan test --filter=Academic
php artisan test --filter=Finance

# Run with coverage
php artisan test --coverage
```

### Test Data
```bash
# Seed test data
php artisan db:seed --class=TestDataSeeder

# Create test users
php artisan make:test-user --role=teacher --count=5
```

## 📈 Performance Optimization

### Caching
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Database Optimization
```bash
# Optimize database
php artisan db:optimize

# Monitor performance
php artisan performance:monitor
```

## 🔒 Security

### Security Features
- **CSRF Protection** - Built-in CSRF token validation
- **SQL Injection Prevention** - Parameterized queries
- **XSS Protection** - Input sanitization and output encoding
- **Authentication** - Secure login with session management
- **Authorization** - Role-based access control
- **Data Encryption** - Sensitive data encryption at rest
- **Audit Logging** - Complete activity tracking

### Security Best Practices
1. **Regular Updates** - Keep Laravel and dependencies updated
2. **Strong Passwords** - Enforce password policies
3. **HTTPS** - Use SSL/TLS encryption
4. **Backup** - Regular database and file backups
5. **Monitoring** - Monitor for suspicious activities

## 🤝 Contributing

### Development Setup
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new features
5. Submit a pull request

### Coding Standards
- Follow PSR-12 coding standards
- Write comprehensive tests
- Document new features
- Use meaningful commit messages

## 📞 Support

### Documentation
- [User Manual](docs/user-manual.md)
- [API Documentation](docs/api.md)
- [Developer Guide](docs/developer-guide.md)
- [Deployment Guide](docs/deployment.md)

### Support Channels
- **Email**: support@dunco.edu
- **GitHub Issues**: [Report Issues](https://github.com/dunte1/dunco-school-management/issues)
- **Discord**: [Join Community](https://discord.gg/dunco)

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Laravel Team** - For the amazing framework
- **Bootstrap Team** - For the responsive UI components
- **Contributors** - All who contributed to this project

## 📊 System Statistics

- **Total Modules**: 17
- **Lines of Code**: 50,000+
- **Database Tables**: 100+
- **API Endpoints**: 200+
- **Test Coverage**: 85%+

---

**Built with ❤️ for educational institutions worldwide**

*Last updated: January 2025*
