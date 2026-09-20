<?php

namespace Database\Seeders;

use App\Models\AdmissionCycle;
use App\Models\AdmissionDocumentType;
use App\Models\AdmissionField;
use App\Models\AdmissionSection;
use App\Models\Faq;
use App\Models\PricingFeature;
use App\Models\PricingPlan;
use App\Models\PublicModule;
use App\Models\PublicSetting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class CmsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTestimonials();
        $this->seedFaqs();
        $this->seedPricingPlans();
        $this->seedPublicSettings();
        $this->seedModules();
        $this->seedAdmissionCycle();
        $this->seedAdmissionSectionsAndFields();
        $this->seedAdmissionDocumentTypes();
    }

    protected function seedTestimonials(): void
    {
        $testimonials = [
            [
                'name' => 'James Mwangi',
                'position' => 'Principal',
                'institution' => 'Greenfield Academy',
                'content' => 'Dunco SMS has completely transformed how we manage our school. From attendance tracking to fee collection, everything is streamlined and efficient. Our staff save hours every week.',
                'rating' => 5,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Sarah Achieng',
                'position' => 'Bursar',
                'institution' => 'Starlight International',
                'content' => 'The finance module is incredible. Fee tracking, payment reconciliation, and financial reporting have never been easier. I can generate reports in seconds that used to take days.',
                'rating' => 5,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Peter Kipchoge',
                'position' => 'Director',
                'institution' => 'Horizon Schools',
                'content' => 'Parents love the portal. They can track their children\'s progress, pay fees online, and communicate with teachers directly. It has improved parent engagement dramatically.',
                'rating' => 5,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $data) {
            Testimonial::updateOrCreate(
                ['name' => $data['name'], 'institution' => $data['institution']],
                $data
            );
        }
    }

    protected function seedFaqs(): void
    {
        $faqs = [
            [
                'question' => 'What is Dunco SMS?',
                'answer' => 'Dunco School Management System (Dunco SMS) is a comprehensive, all-in-one platform designed to manage every aspect of school administration including students, staff, academics, finances, attendance, examinations, and more.',
                'category' => 'general',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'question' => 'How many modules does it include?',
                'answer' => 'Dunco SMS includes 17 fully integrated modules: Academic, Examination, Finance, Attendance, Timetable, Library, Hostel, Transport, HR, Communication, Notifications, Portal, Documents, Settings, AI ChatBot, Core/Admin, and more. Each module works seamlessly with the others.',
                'category' => 'general',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'question' => 'Is it mobile-friendly?',
                'answer' => 'Yes. Dunco SMS is fully responsive and works on all devices including desktops, tablets, and smartphones. We also offer a dedicated mobile app for Android with push notifications and offline support.',
                'category' => 'general',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'question' => 'How long does setup take?',
                'answer' => 'Setup time depends on your school\'s size and complexity. Typically, a basic setup takes 1-2 weeks. Our onboarding team assists with data migration, configuration, and staff training to ensure a smooth transition.',
                'category' => 'general',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'question' => 'Is my data secure?',
                'answer' => 'Security is our top priority. Dunco SMS uses enterprise-grade encryption, role-based access control, comprehensive audit logging, and regular security audits. All data is backed up automatically and stored securely.',
                'category' => 'general',
                'is_active' => true,
                'sort_order' => 5,
            ],
            [
                'question' => 'Can I import existing data?',
                'answer' => 'Yes. Dunco SMS supports bulk import of student data, staff records, fee structures, and historical data via CSV/Excel files. Our onboarding team will help you migrate from your existing system.',
                'category' => 'general',
                'is_active' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($faqs as $data) {
            Faq::updateOrCreate(
                ['question' => $data['question']],
                $data
            );
        }
    }

    protected function seedPricingPlans(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price' => 49.00,
                'currency' => '$',
                'billing_period' => 'month',
                'description' => 'Perfect for small schools getting started with digital management.',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 1,
                'features' => [
                    ['feature_text' => 'Up to 100 students', 'is_included' => true],
                    ['feature_text' => 'Basic academic module', 'is_included' => true],
                    ['feature_text' => 'Fee tracking', 'is_included' => true],
                    ['feature_text' => 'Attendance management', 'is_included' => true],
                    ['feature_text' => 'Email support', 'is_included' => true],
                ],
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'price' => 99.00,
                'currency' => '$',
                'billing_period' => 'month',
                'description' => 'Ideal for growing schools that need advanced features and reporting.',
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => 2,
                'features' => [
                    ['feature_text' => 'Up to 500 students', 'is_included' => true],
                    ['feature_text' => 'All 17 modules included', 'is_included' => true],
                    ['feature_text' => 'Advanced reporting & analytics', 'is_included' => true],
                    ['feature_text' => 'Parent portal access', 'is_included' => true],
                    ['feature_text' => 'Priority support', 'is_included' => true],
                ],
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'price' => 199.00,
                'currency' => '$',
                'billing_period' => 'month',
                'description' => 'For large institutions requiring unlimited access and dedicated support.',
                'is_featured' => false,
                'is_active' => true,
                'sort_order' => 3,
                'features' => [
                    ['feature_text' => 'Unlimited students', 'is_included' => true],
                    ['feature_text' => 'All modules + premium features', 'is_included' => true],
                    ['feature_text' => 'Custom integrations & API access', 'is_included' => true],
                    ['feature_text' => 'Dedicated account manager', 'is_included' => true],
                    ['feature_text' => '24/7 phone & email support', 'is_included' => true],
                ],
            ],
        ];

        foreach ($plans as $planData) {
            $features = $planData['features'];
            unset($planData['features']);

            $plan = PricingPlan::updateOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );

            foreach ($features as $index => $feature) {
                PricingFeature::updateOrCreate(
                    ['pricing_plan_id' => $plan->id, 'feature_text' => $feature['feature_text']],
                    array_merge($feature, ['sort_order' => $index + 1])
                );
            }
        }
    }

    protected function seedPublicSettings(): void
    {
        $settings = [
            ['key' => 'hero_title', 'value' => 'Transform the Way Your School Works', 'type' => 'text'],
            ['key' => 'hero_subtitle', 'value' => 'A comprehensive, all-in-one platform to manage students, staff, academics, finances, and everything in between. Built for the modern educational institution.', 'type' => 'text'],
            ['key' => 'trust_stat_modules', 'value' => '17', 'type' => 'text'],
            ['key' => 'trust_stat_students', 'value' => '2450', 'type' => 'text'],
            ['key' => 'trust_stat_institutions', 'value' => '50', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'info@duncosms.com', 'type' => 'text'],
            ['key' => 'contact_phone', 'value' => '+1 (555) 123-4567', 'type' => 'text'],
            ['key' => 'contact_address', 'value' => '123 Education Lane, Nairobi, Kenya', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            PublicSetting::setVal($setting['key'], $setting['value'], $setting['type']);
        }
    }

    protected function seedModules(): void
    {
        $modules = [
            [
                'name' => 'Academic',
                'slug' => 'academic',
                'icon' => 'fa-graduation-cap',
                'short_description' => 'Manage students, classes, subjects, and enrollment with a unified academic module.',
                'description' => 'The Academic module is the backbone of your school management system. It handles student registration, class assignments, subject management, and enrollment workflows. Track academic progress across all grade levels with real-time dashboards and comprehensive reporting tools.',
                'features' => ['Student registration and profile management', 'Class and section organization', 'Subject assignment and curriculum mapping', 'Enrollment and re-enrollment workflows', 'Academic year and term configuration', 'Student promotion and transfer tracking'],
                'benefits' => ['Reduce enrollment processing time by 70%', 'Centralized student records accessible anywhere', 'Automated class assignments based on rules', 'Real-time academic performance visibility'],
                'hero_title' => 'Academic Management Made Simple',
                'hero_subtitle' => 'Streamline student registration, class management, and academic tracking in one powerful module.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Examination',
                'slug' => 'examination',
                'icon' => 'fa-file-alt',
                'short_description' => 'Online exams, proctoring, result analysis, and comprehensive assessment management.',
                'description' => 'The Examination module supports the full exam lifecycle from creation to result publication. Conduct online exams with built-in proctoring, auto-grade objective questions, and generate detailed result analytics. Support for multiple exam types, grading scales, and report card generation.',
                'features' => ['Online exam creation and scheduling', 'AI-powered exam proctoring', 'Auto-grading for objective questions', 'Result analysis and class rankings', 'Custom grading scales and report cards', 'Exam timetables and hall ticket generation'],
                'benefits' => ['Eliminate manual grading for objective exams', 'Detect and prevent cheating with proctoring', 'Instant result publication and parent notification', 'Data-driven insights into student performance'],
                'hero_title' => 'Examination & Assessment Hub',
                'hero_subtitle' => 'Conduct secure online exams, automate grading, and publish results instantly.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Finance',
                'slug' => 'finance',
                'icon' => 'fa-coins',
                'short_description' => 'Complete fee management, online payments, invoicing, and financial reporting.',
                'description' => 'The Finance module provides end-to-end financial management for schools. Create fee structures, generate invoices, accept online payments, and produce detailed financial reports. Supports M-Pesa, card payments, and bank transfers with automated reconciliation.',
                'features' => ['Fee structure creation and assignment', 'Automated invoice generation', 'Online payment gateway integration (M-Pesa, cards)', 'Payment reconciliation and receipt generation', 'Outstanding fee tracking and reminders', 'Financial reports and cash flow analytics'],
                'benefits' => ['Increase fee collection rates by 40%', 'Eliminate manual receipt processing', 'Real-time financial visibility for administrators', 'Automated late fee calculations and notifications'],
                'hero_title' => 'Smart Financial Management',
                'hero_subtitle' => 'Automate fee collection, streamline payments, and gain complete financial visibility.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Attendance',
                'slug' => 'attendance',
                'icon' => 'fa-user-check',
                'short_description' => 'Daily attendance tracking with QR codes, biometric, and facial recognition support.',
                'description' => 'The Attendance module offers multiple attendance capture methods including manual, QR code scanning, biometric devices, and facial recognition. Parents receive real-time notifications, and administrators get comprehensive attendance analytics and reporting.',
                'features' => ['Multiple capture methods (manual, QR, biometric, face)', 'Real-time parent notifications', 'Attendance analytics and reports', 'Late arrival and early departure tracking', 'Bulk attendance for events and trips', 'Custom attendance policies and rules'],
                'benefits' => ['Save 30 minutes daily on attendance processing', 'Reduce absenteeism with instant parent alerts', 'Flexible methods suit any school infrastructure', 'Comprehensive attendance history and trends'],
                'hero_title' => 'Smart Attendance Tracking',
                'hero_subtitle' => 'Choose from multiple attendance methods — QR, biometric, facial recognition, or manual.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Timetable',
                'slug' => 'timetable',
                'icon' => 'fa-calendar-alt',
                'short_description' => 'Automated timetable generation with conflict detection and teacher workload balancing.',
                'description' => 'The Timetable module automatically generates optimized class schedules based on teacher availability, room capacity, and subject requirements. Built-in conflict detection ensures no double-bookings, and the system balances teacher workloads fairly.',
                'features' => ['Automated timetable generation', 'Conflict detection and resolution', 'Teacher workload balancing', 'Room and lab scheduling', 'Substitute teacher management', 'Student and parent timetable views'],
                'benefits' => ['Generate complete timetables in minutes', 'Zero scheduling conflicts guaranteed', 'Fair teacher workload distribution', 'Easy rescheduling for events and holidays'],
                'hero_title' => 'Effortless Timetable Management',
                'hero_subtitle' => 'Generate conflict-free schedules automatically with intelligent workload balancing.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Library',
                'slug' => 'library',
                'icon' => 'fa-book-open',
                'short_description' => 'Book catalog, borrowing system, digital library, and reading analytics.',
                'description' => 'The Library module manages your entire library ecosystem from physical book cataloging to digital resource management. Track borrowings, manage reservations, and gain insights into reading habits across the school community.',
                'features' => ['Digital book catalog and search', 'Borrowing and return tracking', 'Reservation and hold system', 'Overdue book management and fines', 'Digital library with e-books and resources', 'Reading analytics and popular books reports'],
                'benefits' => ['Zero lost books with automated tracking', 'Digital access to thousands of resources', 'Encourage reading with analytics insights', 'Streamlined library operations'],
                'hero_title' => 'Modern Library Management',
                'hero_subtitle' => 'From physical books to digital resources — manage your entire library ecosystem.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Hostel',
                'slug' => 'hostel',
                'icon' => 'fa-bed',
                'short_description' => 'Room management, student allocation, warden tools, and mess management.',
                'description' => 'The Hostel module streamlines boarding school and dormitory operations. Manage room inventory, allocate students to rooms, track warden duties, and handle mess and meal planning. Parents can view their child\'s boarding status in real-time.',
                'features' => ['Room inventory and capacity management', 'Student room allocation and transfers', 'Warden duty scheduling', 'Mess and meal planning', 'Visitor management and check-in logs', 'Parent portal for boarding status'],
                'benefits' => ['Optimized room utilization up to 95%', 'Transparent boarding operations for parents', 'Simplified warden and staff coordination', 'Comprehensive visitor tracking for safety'],
                'hero_title' => 'Hostel & Boarding Management',
                'hero_subtitle' => 'Manage rooms, allocations, wardens, and mess — all from one dashboard.',
                'sort_order' => 7,
            ],
            [
                'name' => 'Transport',
                'slug' => 'transport',
                'icon' => 'fa-bus',
                'short_description' => 'Vehicle fleet, route planning, driver management, and live trip tracking.',
                'description' => 'The Transport module gives you complete visibility over your school\'s fleet. Plan routes, manage vehicles and drivers, and let parents track their child\'s bus in real-time. Automated alerts notify parents when the bus is near.',
                'features' => ['Vehicle fleet management', 'Route planning and optimization', 'Driver and conductor management', 'Live GPS tracking for parents', 'Pickup/drop-off time notifications', 'Trip and fuel expense tracking'],
                'benefits' => ['Real-time visibility for anxious parents', 'Optimized routes save fuel and time', 'Enhanced student safety with live tracking', 'Centralized fleet maintenance records'],
                'hero_title' => 'School Transport Management',
                'hero_subtitle' => 'Fleet management, route optimization, and live tracking — keeping students safe on every trip.',
                'sort_order' => 8,
            ],
            [
                'name' => 'HR & Staff',
                'slug' => 'hr-staff',
                'icon' => 'fa-users',
                'short_description' => 'Employee records, payroll, leave management, contracts, and performance tracking.',
                'description' => 'The HR & Staff module handles your entire workforce lifecycle from hiring to retirement. Manage employee records, process payroll, handle leave applications, track contracts, and monitor performance reviews — all in one place.',
                'features' => ['Employee records and document management', 'Payroll processing and payslip generation', 'Leave application and approval workflow', 'Contract management and renewal alerts', 'Attendance and punctuality tracking', 'Performance review and appraisal system'],
                'benefits' => ['Automated payroll saves 20+ hours monthly', 'Paperless leave management', 'Never miss contract renewals with alerts', 'Data-driven performance decisions'],
                'hero_title' => 'HR & Staff Management',
                'hero_subtitle' => 'Manage your entire workforce — from payroll to performance — in one integrated system.',
                'sort_order' => 9,
            ],
            [
                'name' => 'Communication',
                'slug' => 'communication',
                'icon' => 'fa-comments',
                'short_description' => 'Internal messaging, SMS, email, and broadcast announcements to all stakeholders.',
                'description' => 'The Communication module keeps everyone connected. Send targeted messages to students, parents, and staff via SMS, email, or in-app notifications. Create broadcast announcements and manage two-way communication channels.',
                'features' => ['Internal messaging system', 'SMS and email integration', 'Broadcast announcements', 'Group and individual messaging', 'Message templates and scheduling', 'Communication history and delivery reports'],
                'benefits' => ['Reach parents 5x faster than traditional methods', 'Centralized communication across all channels', 'Delivery confirmations and read receipts', 'Template library saves drafting time'],
                'hero_title' => 'Unified Communication Hub',
                'hero_subtitle' => 'SMS, email, in-app messaging — reach every stakeholder through their preferred channel.',
                'sort_order' => 10,
            ],
            [
                'name' => 'Notifications',
                'slug' => 'notifications',
                'icon' => 'fa-bell',
                'short_description' => 'Push notifications, email alerts, SMS, and customizable notification templates.',
                'description' => 'The Notifications module ensures no important update goes missed. Configure automated triggers for events like fee reminders, exam results, attendance alerts, and announcements. Customize templates and delivery preferences per user role.',
                'features' => ['Push notifications (web and mobile)', 'Email and SMS notification delivery', 'Event-triggered automation', 'Customizable notification templates', 'Per-role delivery preferences', 'Notification history and analytics'],
                'benefits' => ['Automate 80% of routine notifications', 'Zero missed deadlines with smart reminders', 'Consistent messaging across all channels', 'Parent engagement increased by 60%'],
                'hero_title' => 'Smart Notifications Engine',
                'hero_subtitle' => 'Automated, timely notifications ensure no important update is ever missed.',
                'sort_order' => 11,
            ],
            [
                'name' => 'Portal',
                'slug' => 'portal',
                'icon' => 'fa-th-large',
                'short_description' => 'Dedicated dashboards for students, parents, and teachers with role-based access.',
                'description' => 'The Portal module provides role-based dashboards tailored for students, parents, and teachers. Each portal surfaces the most relevant information — grades, attendance, fees, homework, and announcements — in a clean, intuitive interface.',
                'features' => ['Student dashboard (grades, timetable, fees)', 'Parent dashboard (children\'s progress, payments)', 'Teacher dashboard (classes, grades, attendance)', 'Role-based access and permissions', 'Mobile-responsive design', 'Customizable widgets and preferences'],
                'benefits' => ['Personalized experience for every user type', 'Self-service reduces admin workload by 50%', 'Real-time access to relevant information', 'Increased engagement from all stakeholders'],
                'hero_title' => 'Multi-Role Portal Dashboards',
                'hero_subtitle' => 'Personalized dashboards for students, parents, and teachers — everyone sees what matters most.',
                'sort_order' => 12,
            ],
            [
                'name' => 'Documents',
                'slug' => 'documents',
                'icon' => 'fa-folder-open',
                'short_description' => 'Upload, categorize, manage, and share files across the school community.',
                'description' => 'The Documents module is your centralized file management system. Upload and categorize academic documents, policies, certificates, and reports. Control access with role-based permissions and share files securely with parents and staff.',
                'features' => ['Centralized document storage', 'Category and tag organization', 'Role-based access control', 'Bulk upload and download', 'Version control and audit trail', 'Secure file sharing with parents'],
                'benefits' => ['Eliminate paper-based document chaos', 'Instant access to any document', 'Secure sharing without email attachments', 'Complete audit trail for compliance'],
                'hero_title' => 'Document Management System',
                'hero_subtitle' => 'Organize, access, and share school documents securely from one central hub.',
                'sort_order' => 13,
            ],
            [
                'name' => 'Settings',
                'slug' => 'settings',
                'icon' => 'fa-cog',
                'short_description' => 'Global configuration, branding, backups, and system-wide preferences.',
                'description' => 'The Settings module gives administrators full control over system configuration. Customize branding, manage academic years, configure backup schedules, and set global preferences that affect all modules.',
                'features' => ['Global system configuration', 'School branding and theming', 'Academic year and term settings', 'Automated backup management', 'System health monitoring', 'Feature toggles and module activation'],
                'benefits' => ['Single place to configure everything', 'Brand consistency across the platform', 'Automated backups for data safety', 'Toggle features on/off as needed'],
                'hero_title' => 'System Settings & Configuration',
                'hero_subtitle' => 'One place to configure, brand, and customize every aspect of your school management system.',
                'sort_order' => 14,
            ],
            [
                'name' => 'AI ChatBot',
                'slug' => 'ai-chatbot',
                'icon' => 'fa-robot',
                'short_description' => 'Gemini and OpenAI integration for intelligent student support and automation.',
                'description' => 'The AI ChatBot module brings artificial intelligence to your school system. Powered by Gemini and OpenAI, it provides 24/7 student support, automates repetitive tasks, answers FAQs, and helps parents navigate the system with natural language conversations.',
                'features' => ['Gemini and OpenAI model integration', '24/7 automated student support', 'FAQ and knowledge base responses', 'Parent query assistance', 'Custom training on school policies', 'Multi-language support'],
                'benefits' => ['Reduce support ticket volume by 45%', 'Instant responses to common queries', '24/7 availability without staff overhead', 'Continuous learning from interactions'],
                'hero_title' => 'AI-Powered School Assistant',
                'hero_subtitle' => 'Leverage Gemini and OpenAI to provide intelligent, always-available support to your school community.',
                'sort_order' => 15,
            ],
            [
                'name' => 'Core / Admin',
                'slug' => 'core-admin',
                'icon' => 'fa-shield-alt',
                'short_description' => 'User management, roles, permissions, and comprehensive audit logging.',
                'description' => 'The Core/Admin module is the security backbone of the system. Manage users with role-based access control, define granular permissions, and maintain comprehensive audit logs of all system activities for compliance and accountability.',
                'features' => ['User management and authentication', 'Role-based access control (RBAC)', 'Granular permission management', 'Comprehensive audit logging', 'Two-factor authentication (2FA)', 'Session management and security'],
                'benefits' => ['Granular control over who accesses what', 'Complete audit trail for compliance', 'Enterprise-grade security built in', 'Easy onboarding with role templates'],
                'hero_title' => 'Security & Administration Core',
                'hero_subtitle' => 'Enterprise-grade user management, RBAC, and audit logging for complete system control.',
                'sort_order' => 16,
            ],
            [
                'name' => 'Admissions',
                'slug' => 'admissions',
                'icon' => 'fa-clipboard-list',
                'short_description' => 'Online applications, admission cycles, workflow automation, and applicant tracking.',
                'description' => 'The Admissions module digitizes your entire enrollment process. Create admission cycles, accept online applications, track applicant status through custom workflows, and seamlessly transition admitted students into the academic system.',
                'features' => ['Online application forms', 'Admission cycle management', 'Custom workflow stages', 'Document upload and verification', 'Applicant communication tracking', 'One-click enrollment to academic module'],
                'benefits' => ['Eliminate paper application processing', '50% faster enrollment turnaround', 'Transparent status for applicants', 'Seamless handoff to academic records'],
                'hero_title' => 'Digital Admissions Workflow',
                'hero_subtitle' => 'From application to enrollment — automate and streamline your entire admissions process.',
                'sort_order' => 17,
            ],
        ];

        foreach ($modules as $data) {
            PublicModule::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['is_active' => true])
            );
        }
    }

    protected function seedAdmissionCycle(): void
    {
        AdmissionCycle::updateOrCreate(
            ['name' => '2026/2027 Academic Year Intake'],
            [
                'academic_year' => '2026-2027',
                'opening_date' => '2026-01-15',
                'closing_date' => '2026-03-31',
                'description' => 'Admissions for the 2026/2027 academic year.',
                'status' => 'open',
                'is_active' => true,
            ]
        );
    }

    protected function seedAdmissionSectionsAndFields(): void
    {
        $sections = [
            [
                'name' => 'Applicant Information',
                'slug' => 'applicant-information',
                'description' => 'Personal details of the applicant.',
                'sort_order' => 1,
                'fields' => [
                    ['name' => 'First Name', 'slug' => 'first_name', 'type' => 'text', 'is_required' => true, 'placeholder' => 'Enter first name', 'sort_order' => 1],
                    ['name' => 'Last Name', 'slug' => 'last_name', 'type' => 'text', 'is_required' => true, 'placeholder' => 'Enter last name', 'sort_order' => 2],
                    ['name' => 'Email', 'slug' => 'email', 'type' => 'email', 'is_required' => true, 'placeholder' => 'Enter email address', 'sort_order' => 3],
                    ['name' => 'Phone', 'slug' => 'phone', 'type' => 'phone', 'is_required' => false, 'placeholder' => 'Enter phone number', 'sort_order' => 4],
                    ['name' => 'Date of Birth', 'slug' => 'date_of_birth', 'type' => 'date', 'is_required' => true, 'placeholder' => 'Select date of birth', 'sort_order' => 5],
                    ['name' => 'Gender', 'slug' => 'gender', 'type' => 'select', 'options' => ['Male', 'Female', 'Other'], 'is_required' => true, 'placeholder' => 'Select gender', 'sort_order' => 6],
                    ['name' => 'Nationality', 'slug' => 'nationality', 'type' => 'text', 'is_required' => false, 'placeholder' => 'Enter nationality', 'sort_order' => 7],
                ],
            ],
            [
                'name' => 'Parent/Guardian Information',
                'slug' => 'parent-guardian-information',
                'description' => 'Details of the parent or guardian.',
                'sort_order' => 2,
                'fields' => [
                    ['name' => 'Parent Name', 'slug' => 'parent_name', 'type' => 'text', 'is_required' => true, 'placeholder' => 'Enter parent/guardian name', 'sort_order' => 1],
                    ['name' => 'Parent Phone', 'slug' => 'parent_phone', 'type' => 'phone', 'is_required' => true, 'placeholder' => 'Enter parent/guardian phone', 'sort_order' => 2],
                    ['name' => 'Parent Email', 'slug' => 'parent_email', 'type' => 'email', 'is_required' => false, 'placeholder' => 'Enter parent/guardian email', 'sort_order' => 3],
                ],
            ],
            [
                'name' => 'Academic History',
                'slug' => 'academic-history',
                'description' => 'Previous academic background.',
                'sort_order' => 3,
                'fields' => [
                    ['name' => 'Previous School', 'slug' => 'previous_school', 'type' => 'text', 'is_required' => false, 'placeholder' => 'Enter previous school name', 'sort_order' => 1],
                    ['name' => 'Last Grade Completed', 'slug' => 'last_grade_completed', 'type' => 'text', 'is_required' => false, 'placeholder' => 'Enter last grade/class completed', 'sort_order' => 2],
                ],
            ],
            [
                'name' => 'Emergency Contact',
                'slug' => 'emergency-contact',
                'description' => 'Emergency contact details.',
                'sort_order' => 4,
                'fields' => [
                    ['name' => 'Emergency Contact Name', 'slug' => 'emergency_contact_name', 'type' => 'text', 'is_required' => true, 'placeholder' => 'Enter emergency contact name', 'sort_order' => 1],
                    ['name' => 'Emergency Contact Phone', 'slug' => 'emergency_contact_phone', 'type' => 'phone', 'is_required' => true, 'placeholder' => 'Enter emergency contact phone', 'sort_order' => 2],
                ],
            ],
            [
                'name' => 'Required Documents',
                'slug' => 'required-documents',
                'description' => 'Upload required admission documents.',
                'sort_order' => 5,
                'fields' => [],
            ],
            [
                'name' => 'Additional Information',
                'slug' => 'additional-information',
                'description' => 'Any other relevant information.',
                'sort_order' => 6,
                'fields' => [
                    ['name' => 'Additional Notes', 'slug' => 'additional_notes', 'type' => 'textarea', 'is_required' => false, 'placeholder' => 'Enter any additional information or special requirements', 'sort_order' => 1],
                ],
            ],
        ];

        foreach ($sections as $sectionData) {
            $fields = $sectionData['fields'];
            unset($sectionData['fields']);

            $section = AdmissionSection::updateOrCreate(
                ['slug' => $sectionData['slug']],
                array_merge($sectionData, ['is_active' => true])
            );

            foreach ($fields as $fieldData) {
                AdmissionField::updateOrCreate(
                    ['admission_section_id' => $section->id, 'slug' => $fieldData['slug']],
                    array_merge($fieldData, ['is_active' => true])
                );
            }
        }
    }

    protected function seedAdmissionDocumentTypes(): void
    {
        $documents = [
            ['name' => 'Birth Certificate', 'is_required' => true, 'allowed_types' => 'pdf,jpg,png', 'max_size_kb' => 5120, 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Passport Photo', 'is_required' => true, 'allowed_types' => 'jpg,png', 'max_size_kb' => 2048, 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Previous Report Card', 'is_required' => false, 'allowed_types' => 'pdf,jpg,png', 'max_size_kb' => 5120, 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Transfer Letter', 'is_required' => false, 'allowed_types' => 'pdf,jpg,png', 'max_size_kb' => 5120, 'is_active' => true, 'sort_order' => 4],
            ['name' => 'Parent/Guardian ID', 'is_required' => false, 'allowed_types' => 'pdf,jpg,png', 'max_size_kb' => 5120, 'is_active' => true, 'sort_order' => 5],
        ];

        foreach ($documents as $doc) {
            AdmissionDocumentType::updateOrCreate(
                ['name' => $doc['name']],
                $doc
            );
        }
    }
}
