@php
    $modules = \App\Helpers\NavigationHelper::getUserModules();
    $moduleMeta = [
        'core' => ['label' => 'Dashboard', 'icon' => 'fa-home', 'href' => '/dashboard'],
        'academic' => ['label' => 'Academic', 'icon' => 'fa-graduation-cap', 'href' => '/academic'],
        'examination' => ['label' => 'Exams', 'icon' => 'fa-file-alt', 'href' => '/examinations'],
        'finance' => ['label' => 'Finance', 'icon' => 'fa-money-bill-wave', 'href' => '/finance'],
        'hr' => ['label' => 'HR', 'icon' => 'fa-user-tie', 'href' => '/hr'],
        'library' => ['label' => 'Library', 'icon' => 'fa-book', 'href' => '/library'],
        'hostel' => ['label' => 'Hostel', 'icon' => 'fa-hotel', 'href' => '/hostel'],
        'transport' => ['label' => 'Transport', 'icon' => 'fa-bus', 'href' => '/transport'],
        'timetable' => ['label' => 'Timetable', 'icon' => 'fa-calendar-alt', 'href' => '/timetable'],
        'attendance' => ['label' => 'Attendance', 'icon' => 'fa-calendar-check', 'href' => '/attendance'],
        'communication' => ['label' => 'Communication', 'icon' => 'fa-comments', 'href' => '/communication'],
        'portal' => ['label' => 'Portal', 'icon' => 'fa-user-friends', 'href' => '/portal'],
        'document' => ['label' => 'Documents', 'icon' => 'fa-folder-open', 'href' => '/documents'],
        'notification' => ['label' => 'Notifications', 'icon' => 'fa-bell', 'href' => '/notifications'],
        'settings' => ['label' => 'Settings', 'icon' => 'fa-cog', 'href' => '/settings'],
        'api' => ['label' => 'API', 'icon' => 'fa-plug', 'href' => '/api'],
        'chatbot' => ['label' => 'ChatBot', 'icon' => 'fa-robot', 'href' => '/chatbot'],
        'nursing' => ['label' => 'Nursing', 'icon' => 'fa-heartbeat', 'href' => '/nursing'],
    ];
    $submenu = [
        'academic' => [
            ['label' => 'Students', 'icon' => 'fa-user-graduate', 'href' => '/academic/students', 'perm' => 'academic.students.view'],
            ['label' => 'Classes', 'icon' => 'fa-door-open', 'href' => '/academic/classes', 'perm' => 'academic.classes.view'],
            ['label' => 'Subjects', 'icon' => 'fa-book-open', 'href' => '/academic/subjects', 'perm' => 'academic.subjects.view'],
        ],
        'finance' => [
            ['label' => 'Fees', 'icon' => 'fa-receipt', 'href' => '/finance/fees', 'perm' => 'finance.fees.view'],
            ['label' => 'Billing', 'icon' => 'fa-file-invoice-dollar', 'href' => '/finance/billing', 'perm' => 'finance.billing.view'],
            ['label' => 'Payments', 'icon' => 'fa-money-check-dollar', 'href' => '/finance/payments', 'perm' => 'finance.payments.view'],
        ],
        'attendance' => [
            ['label' => 'Mark', 'icon' => 'fa-check-square', 'href' => '/attendance/mark', 'perm' => 'attendance.mark.view'],
            ['label' => 'Reports', 'icon' => 'fa-chart-bar', 'href' => '/attendance/reports', 'perm' => 'attendance.reports.view'],
            ['label' => 'Settings', 'icon' => 'fa-sliders-h', 'href' => '/attendance/settings', 'perm' => 'attendance.settings.view'],
        ],
        'library' => [
            ['label' => 'Books', 'icon' => 'fa-book', 'href' => '/library/books', 'perm' => 'library.books.view'],
            ['label' => 'Categories', 'icon' => 'fa-list', 'href' => '/library/categories', 'perm' => 'library.categories.view'],
            ['label' => 'Members', 'icon' => 'fa-users', 'href' => '/library/members', 'perm' => 'library.members.view'],
        ],
        'timetable' => [
            ['label' => 'Schedules', 'icon' => 'fa-calendar-alt', 'href' => '/timetable/schedules', 'perm' => 'timetable.schedules.view'],
            ['label' => 'Rooms', 'icon' => 'fa-door-closed', 'href' => '/timetable/rooms', 'perm' => 'timetable.rooms.view'],
        ],
        'transport' => [
            ['label' => 'Vehicles', 'icon' => 'fa-bus', 'href' => '/transport/vehicles', 'perm' => 'transport.vehicles.view'],
            ['label' => 'Routes', 'icon' => 'fa-route', 'href' => '/transport/routes', 'perm' => 'transport.routes.view'],
            ['label' => 'Drivers', 'icon' => 'fa-id-card', 'href' => '/transport/drivers', 'perm' => 'transport.drivers.view'],
        ],
        'hostel' => [
            ['label' => 'Allocations', 'icon' => 'fa-bed', 'href' => '/hostel/allocations', 'perm' => 'hostel.allocations.view'],
            ['label' => 'Fees', 'icon' => 'fa-coins', 'href' => '/hostel/fees', 'perm' => 'hostel.fees.view'],
        ],
        'communication' => [
            ['label' => 'Inbox', 'icon' => 'fa-inbox', 'href' => '/communication/inbox', 'perm' => 'communication.inbox.view'],
            ['label' => 'Announcements', 'icon' => 'fa-bullhorn', 'href' => '/communication/announcements', 'perm' => 'communication.announcements.view'],
        ],
        'nursing' => [
            ['label' => 'Facilities', 'icon' => 'fa-hospital', 'href' => '/nursing/facilities', 'perm' => 'nursing.placements.view'],
            ['label' => 'Placements', 'icon' => 'fa-map-marker-alt', 'href' => '/nursing/placements', 'perm' => 'nursing.placements.view'],
            ['label' => 'Logbook', 'icon' => 'fa-book-open', 'href' => '/nursing/logbook', 'perm' => 'nursing.logbooks.view'],
            ['label' => 'Skills', 'icon' => 'fa-check-circle', 'href' => '/nursing/skills', 'perm' => 'nursing.skills.view'],
            ['label' => 'Clinical Hours', 'icon' => 'fa-clock', 'href' => '/nursing/hours', 'perm' => 'nursing.hours.view'],
            ['label' => 'Reference', 'icon' => 'fa-book', 'href' => '/nursing/reference', 'perm' => 'nursing.reference.view'],
            ['label' => 'Scenarios', 'icon' => 'fa-lightbulb', 'href' => '/nursing/scenarios', 'perm' => 'nursing.scenarios.view'],
            ['label' => 'CPD', 'icon' => 'fa-certificate', 'href' => '/nursing/cpd', 'perm' => 'nursing.cpd.view'],
            ['label' => 'Calculators', 'icon' => 'fa-calculator', 'href' => '/nursing/calculators', 'perm' => 'nursing.calculators.view'],
            ['label' => 'Reports', 'icon' => 'fa-chart-bar', 'href' => '/nursing/reports', 'perm' => 'nursing.reports.view'],
            ['label' => 'Settings', 'icon' => 'fa-cog', 'href' => '/nursing/settings', 'perm' => 'nursing.settings.manage'],
        ],
    ];
@endphp

<aside id="sidebarNav" class="sidebar">
    <div class="sidebar-header">
        <span class="brand-text">Dunco School</span>
        <div class="d-flex align-items-center gap-2">
            <button id="sidebarCollapseBtn" class="collapse-btn" aria-label="Collapse sidebar">
                <i class="fas fa-angle-double-left"></i>
            </button>
            <button id="sidebarToggle" style="display:none"></button>
            <button id="sidebarToggle2" style="display:none"></button>
        </div>
    </div>

    <div class="px-2">
        <div class="sidebar-section">Modules</div>
        <nav class="nav flex-column">
            @foreach ($modules as $mod)
                @php $meta = $moduleMeta[$mod] ?? null; @endphp
                @if ($meta)
                    <a href="{{ $meta['href'] }}" class="nav-link {{ request()->is(ltrim($meta['href'], '/').'*') ? 'active' : '' }}">
                        <span class="nav-icon"><i class="fas {{ $meta['icon'] }}"></i></span>
                        <span class="nav-text">{{ $meta['label'] }}</span>
                    </a>
                    @if (!empty($submenu[$mod]))
                        @foreach ($submenu[$mod] as $item)
                            @if (\App\Helpers\NavigationHelper::hasPermission($item['perm']))
                                <a href="{{ $item['href'] }}" class="nav-link {{ request()->is(ltrim($item['href'], '/').'*') ? 'active' : '' }}" style="padding-left:2.5rem; font-size:0.98rem; opacity:0.95;">
                                    <span class="nav-icon" style="width:1.2rem;"><i class="fas {{ $item['icon'] }}"></i></span>
                                    <span class="nav-text">{{ $item['label'] }}</span>
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endif
            @endforeach
        </nav>
        @if (\App\Helpers\NavigationHelper::hasRole('admin'))
            <div class="sidebar-section mt-3">Administration</div>
            <nav class="nav flex-column">
                <a href="{{ route('admin.modules.index') }}" class="nav-link {{ request()->is('admin/modules*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="fas fa-puzzle-piece"></i></span>
                    <span class="nav-text">Module Management</span>
                </a>
            </nav>
        @endif
    </div>
</aside>
<div id="sidebarBackdrop" class="sidebar-backdrop"></div>