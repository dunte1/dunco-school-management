# Dunco School Management System

A comprehensive, modular school management system built with Laravel 11 and designed for multi-tenant architecture.

## 🚀 Features

### ✅ Implemented (Core Module)

- **User Management**: Complete CRUD operations for users with role-based access
- **Role Management**: Create, edit, delete roles with permission assignments
- **Permission Management**: Granular permission system with module-based organization
- **School Management**: Multi-tenant school setup with individual domains
- **Audit Logging**: Comprehensive activity tracking for all system actions
- **School Settings**: Configurable settings per school
- **Dashboard**: Statistics and overview with recent activity
- **Authentication**: Laravel Breeze-based authentication system
- **Role-Based Access Control**: Middleware and Blade directives for permission checking

### 🔧 Technical Features

- **Multi-Tenancy**: Support for multiple schools with isolated data
- **Modular Architecture**: Laravel modules for easy feature expansion
- **Responsive UI**: Bootstrap 5-based responsive design
- **Database Migrations**: Complete database structure with relationships
- **Seeders**: Initial data setup with default roles and permissions
- **API Ready**: Structured for API development

## 📁 Project Structure

```
duncoschool/
├── app/
│   ├── Models/                 # Core models (User, Role, Permission, School, etc.)
│   ├── Http/Controllers/       # Main application controllers
│   ├── Http/Middleware/        # Custom middleware (CheckPermission, etc.)
│   ├── Providers/              # Service providers
│   └── Traits/                 # Reusable traits (HasPermissions)
├── Modules/                    # Laravel modules
│   ├── Core/                   # ✅ Fully implemented
│   │   ├── app/Http/Controllers/
│   │   ├── resources/views/
│   │   ├── routes/
│   │   └── database/
│   ├── Academic/               # 🔄 Partially implemented
│   ├── HR/                     # ❌ Not implemented
│   ├── Finance/                # ❌ Not implemented
│   └── ... (30+ modules)
├── database/
│   ├── migrations/             # Core database migrations
│   └── seeders/                # Data seeders
└── resources/views/
    ├── layouts/                # Main layout templates
    └── dashboard.blade.php     # Dashboard view
```

## 🛠 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd duncoschool
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

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed --class=CoreSeeder
   ```

5. **Storage setup**
   ```bash
   php artisan storage:link
   ```

6. **Start the application**
   ```bash
   php artisan serve
   ```

## 🔐 Default Login

After running the seeder, you can login with:
- **Email**: admin@dunco.com
- **Password**: password

## 📊 Module Status

| Module | Status | Description |
|--------|--------|-------------|
| Core | ✅ Complete | User, Role, Permission, School management |
| Academic | 🔄 Partial | Basic structure, needs implementation |
| HR | ❌ Not Started | Human resources management |
| Finance | ❌ Not Started | Financial management |
| Library | ❌ Not Started | Library management |
| Communication | ❌ Not Started | Communication tools |
| Hostel | ❌ Not Started | Hostel management |
| Transport | ❌ Not Started | Transport management |
| Document | ❌ Not Started | Document management |
| LMS | ❌ Not Started | Learning management system |
| Portal | ❌ Not Started | Student/parent portal |
| Examination | ❌ Not Started | Examination management |
| Attendance | ❌ Not Started | Attendance tracking |
| Timetable | ❌ Not Started | Class scheduling |
| Notification | ❌ Not Started | Notification system |
| Analytics | ❌ Not Started | Data analytics |
| Settings | ❌ Not Started | System settings |
| API | ❌ Not Started | API endpoints |
| PWA | ❌ Not Started | Progressive web app |
| Alumni | ❌ Not Started | Alumni management |
| Research | ❌ Not Started | Research management |
| ELibrary | ❌ Not Started | Digital library |
| Marketplace | ❌ Not Started | School marketplace |
| Welfare | ❌ Not Started | Student welfare |
| Cafeteria | ❌ Not Started | Cafeteria management |
| Inventory | ❌ Not Started | Inventory management |
| Assets | ❌ Not Started | Asset management |
| Compliance | ❌ Not Started | Compliance tracking |
| Audit | ❌ Not Started | Advanced auditing |
| Localization | ❌ Not Started | Multi-language support |
| ChatBot | ❌ Not Started | AI chatbot |

## 🔑 Permission System

The system uses a granular permission system:

### Core Permissions
- `schools.view`, `schools.create`, `schools.edit`, `schools.delete`
- `users.view`, `users.create`, `users.edit`, `users.delete`
- `roles.view`, `roles.create`, `roles.edit`, `roles.delete`
- `permissions.view`, `permissions.create`, `permissions.edit`, `permissions.delete`
- `audit.view`
- `settings.view`, `settings.edit`

### Usage in Views
```blade
@permission('users.create')
    <a href="{{ route('core.users.create') }}" class="btn btn-primary">Create User</a>
@endpermission

@anypermission(['users.edit', 'users.delete'])
    <div class="btn-group">
        @permission('users.edit')
            <a href="{{ route('core.users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
        @endpermission
        @permission('users.delete')
            <form method="POST" action="{{ route('core.users.destroy', $user->id) }}" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        @endpermission
    </div>
@endanypermission
```

### Usage in Controllers
```php
public function store(Request $request)
{
    $this->middleware('permission:users.create');
    // Controller logic
}
```

## 🏗 Multi-Tenancy

The system supports multiple schools with:
- Isolated databases per school
- School-specific domains
- School-specific settings
- User isolation by school

## 📈 Next Steps

### Priority 1: Complete Core Features
1. ✅ Fix missing permissions (DONE)
2. ✅ Add permission checking middleware (DONE)
3. ✅ Create missing views (DONE)
4. 🔄 Implement file upload for school logos
5. 🔄 Add email verification
6. 🔄 Add password reset functionality

### Priority 2: Academic Module
1. 🔄 Complete Academic module implementation
2. 🔄 Add class management
3. 🔄 Add subject management
4. 🔄 Add student enrollment
5. 🔄 Add grade management

### Priority 3: Other Modules
1. ❌ Implement HR module
2. ❌ Implement Finance module
3. ❌ Implement Library module
4. ❌ Implement Communication module

### Priority 4: Advanced Features
1. ❌ API development
2. ❌ Real-time notifications
3. ❌ Mobile app support
4. ❌ Multi-language support
5. ❌ Advanced reporting

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📝 License

This project is licensed under the MIT License.

## 🆘 Support

For support and questions, please contact the development team.
