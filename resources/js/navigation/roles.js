// Role-based navigation configuration
export const roleNavigation = {
  student: [
    {
      id: 'home',
      name: 'Home',
      icon: 'fas fa-home',
      route: '/dashboard',
      priority: 1
    },
    {
      id: 'exams',
      name: 'Exams',
      icon: 'fas fa-file-alt',
      route: '/exams',
      priority: 2
    },
    {
      id: 'timetable',
      name: 'Timetable',
      icon: 'fas fa-calendar-alt',
      route: '/timetable',
      priority: 3
    },
    {
      id: 'attendance',
      name: 'Attendance',
      icon: 'fas fa-clipboard-list',
      route: '/attendance',
      priority: 5
    },
    {
      id: 'finance',
      name: 'Finance',
      icon: 'fas fa-money-bill-wave',
      route: '/finance',
      priority: 6
    },
    {
      id: 'library',
      name: 'Library',
      icon: 'fas fa-book',
      route: '/library',
      priority: 7
    },
    {
      id: 'assignments',
      name: 'Assignments',
      icon: 'fas fa-tasks',
      route: '/assignments',
      priority: 4
    },
    {
      id: 'messages',
      name: 'Messages',
      icon: 'fas fa-comments',
      route: '/messages',
      priority: 5
    },
    {
      id: 'profile',
      name: 'Profile',
      icon: 'fas fa-user',
      route: '/profile',
      priority: 6
    }
  ],
  teacher: [
    {
      id: 'dashboard',
      name: 'Dashboard',
      icon: 'fas fa-tachometer-alt',
      route: '/dashboard',
      priority: 1
    },
    {
      id: 'classes',
      name: 'Classes',
      icon: 'fas fa-chalkboard',
      route: '/classes',
      priority: 2
    },
    {
      id: 'exams',
      name: 'Exams',
      icon: 'fas fa-file-alt',
      route: '/exams',
      priority: 3
    },
    {
      id: 'messages',
      name: 'Messages',
      icon: 'fas fa-comments',
      route: '/messages',
      priority: 4
    },
    {
      id: 'profile',
      name: 'Profile',
      icon: 'fas fa-user',
      route: '/profile',
      priority: 5
    }
  ],
  parent: [
    {
      id: 'child',
      name: 'Child',
      icon: 'fas fa-child',
      route: '/child',
      priority: 1
    },
    {
      id: 'finance',
      name: 'Finance',
      icon: 'fas fa-money-bill-wave',
      route: '/finance',
      priority: 2
    },
    {
      id: 'messages',
      name: 'Messages',
      icon: 'fas fa-comments',
      route: '/messages',
      priority: 3
    },
    {
      id: 'notifications',
      name: 'Notifications',
      icon: 'fas fa-bell',
      route: '/notifications',
      priority: 4
    },
    {
      id: 'profile',
      name: 'Profile',
      icon: 'fas fa-user',
      route: '/profile',
      priority: 5
    }
  ],
  admin: [
    {
      id: 'dashboard',
      name: 'Dashboard',
      icon: 'fas fa-tachometer-alt',
      route: '/admin/dashboard',
      priority: 1
    },
    {
      id: 'management',
      name: 'Management',
      icon: 'fas fa-cogs',
      children: [
        {
          id: 'schools',
          name: 'Schools',
          icon: 'fas fa-school',
          route: '/admin/schools'
        },
        {
          id: 'users',
          name: 'Users',
          icon: 'fas fa-users',
          route: '/admin/users'
        },
        {
          id: 'roles',
          name: 'Roles',
          icon: 'fas fa-user-shield',
          route: '/admin/roles'
        }
      ]
    },
    {
      id: 'hr',
      name: 'HR',
      icon: 'fas fa-chalkboard-teacher',
      route: '/admin/hr'
    },
    {
      id: 'academic',
      name: 'Academic',
      icon: 'fas fa-graduation-cap',
      route: '/admin/academic'
    },
    {
      id: 'exams',
      name: 'Exams',
      icon: 'fas fa-file-alt',
      route: '/admin/exams'
    },
    {
      id: 'finance',
      name: 'Finance',
      icon: 'fas fa-money-bill-wave',
      route: '/admin/finance'
    },
    {
      id: 'library',
      name: 'Library',
      icon: 'fas fa-book',
      route: '/admin/library'
    },
    {
      id: 'reports',
      name: 'Reports',
      icon: 'fas fa-chart-bar',
      route: '/admin/reports'
    },
    {
      id: 'notifications',
      name: 'Notifications',
      icon: 'fas fa-bell',
      route: '/admin/notifications'
    }
  ]
};

// Function to get navigation items based on user role
export function getNavigationItems(userRole) {
  return roleNavigation[userRole] || [];
}

// Function to check if user has access to a specific navigation item
export function hasAccessToNavItem(userRole, navItemId) {
  const navItems = getNavigationItems(userRole);
  
  // Recursive function to search through navigation items and children
  function searchItems(items) {
    for (const item of items) {
      if (item.id === navItemId) {
        return true;
      }
      if (item.children && searchItems(item.children)) {
        return true;
      }
    }
    return false;
  }
  
  return searchItems(navItems);
}