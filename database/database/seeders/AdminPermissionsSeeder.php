<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the admin role
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $this->command->error('Admin role not found! Please create the admin role first.');
            return;
        }

        $this->command->info('Adding comprehensive permissions to admin role...');

        // Define all permissions by module
        $permissions = [
            // Hostel Module
            'hostel.view', 'hostel.create', 'hostel.edit', 'hostel.delete', 'hostel.manage',
            'hostel.allocations.view', 'hostel.allocations.create', 'hostel.allocations.edit', 'hostel.allocations.delete',
            'hostel.rooms.view', 'hostel.rooms.create', 'hostel.rooms.edit', 'hostel.rooms.delete',
            'hostel.floors.view', 'hostel.floors.create', 'hostel.floors.edit', 'hostel.floors.delete',
            'hostel.beds.view', 'hostel.beds.create', 'hostel.beds.edit', 'hostel.beds.delete',
            'hostel.fees.view', 'hostel.fees.create', 'hostel.fees.edit', 'hostel.fees.delete',
            'hostel.issues.view', 'hostel.issues.create', 'hostel.issues.edit', 'hostel.issues.delete',
            'hostel.leave.view', 'hostel.leave.create', 'hostel.leave.edit', 'hostel.leave.delete',
            'hostel.visitors.view', 'hostel.visitors.create', 'hostel.visitors.edit', 'hostel.visitors.delete',
            'hostel.announcements.view', 'hostel.announcements.create', 'hostel.announcements.edit', 'hostel.announcements.delete',
            'hostel.wardens.view', 'hostel.wardens.create', 'hostel.wardens.edit', 'hostel.wardens.delete',
            'hostel.reports.view', 'hostel.reports.create', 'hostel.reports.edit', 'hostel.reports.delete',

            // Finance Module
            'finance.view', 'finance.create', 'finance.edit', 'finance.delete', 'finance.manage',
            'finance.fees.view', 'finance.fees.create', 'finance.fees.edit', 'finance.fees.delete',
            'finance.payments.view', 'finance.payments.create', 'finance.payments.edit', 'finance.payments.delete',
            'finance.reports.view', 'finance.reports.create', 'finance.reports.edit', 'finance.reports.delete',
            'finance.invoices.view', 'finance.invoices.create', 'finance.invoices.edit', 'finance.invoices.delete',
            'finance.refunds.view', 'finance.refunds.create', 'finance.refunds.edit', 'finance.refunds.delete',

            // Academic Module
            'academic.view', 'academic.create', 'academic.edit', 'academic.delete', 'academic.manage',
            'academic.courses.view', 'academic.courses.create', 'academic.courses.edit', 'academic.courses.delete',
            'academic.subjects.view', 'academic.subjects.create', 'academic.subjects.edit', 'academic.subjects.delete',
            'academic.classes.view', 'academic.classes.create', 'academic.classes.edit', 'academic.classes.delete',
            'academic.syllabus.view', 'academic.syllabus.create', 'academic.syllabus.edit', 'academic.syllabus.delete',
            'academic.curriculum.view', 'academic.curriculum.create', 'academic.curriculum.edit', 'academic.curriculum.delete',

            // Analytics Module
            'analytics.view', 'analytics.create', 'analytics.edit', 'analytics.delete', 'analytics.manage',
            'analytics.reports.view', 'analytics.reports.create', 'analytics.reports.edit', 'analytics.reports.delete',
            'analytics.insights.view', 'analytics.insights.create', 'analytics.insights.edit', 'analytics.insights.delete',
            'analytics.dashboard.view', 'analytics.dashboard.create', 'analytics.dashboard.edit', 'analytics.dashboard.delete',

            // Communication Module
            'communication.view', 'communication.create', 'communication.edit', 'communication.delete', 'communication.manage',
            'communication.messages.view', 'communication.messages.create', 'communication.messages.edit', 'communication.messages.delete',
            'communication.announcements.view', 'communication.announcements.create', 'communication.announcements.edit', 'communication.announcements.delete',
            'communication.notifications.view', 'communication.notifications.create', 'communication.notifications.edit', 'communication.notifications.delete',
            'communication.email.view', 'communication.email.create', 'communication.email.edit', 'communication.email.delete',
            'communication.sms.view', 'communication.sms.create', 'communication.sms.edit', 'communication.sms.delete',

            // Examination Module
            'examination.view', 'examination.create', 'examination.edit', 'examination.delete', 'examination.manage',
            'examination.exams.view', 'examination.exams.create', 'examination.exams.edit', 'examination.exams.delete',
            'examination.results.view', 'examination.results.create', 'examination.results.edit', 'examination.results.delete',
            'examination.reports.view', 'examination.reports.create', 'examination.reports.edit', 'examination.reports.delete',
            'examination.schedules.view', 'examination.schedules.create', 'examination.schedules.edit', 'examination.schedules.delete',
            'examination.grades.view', 'examination.grades.create', 'examination.grades.edit', 'examination.grades.delete',

            // Transport Module
            'transport.view', 'transport.create', 'transport.edit', 'transport.delete', 'transport.manage',
            'transport.vehicles.view', 'transport.vehicles.create', 'transport.vehicles.edit', 'transport.vehicles.delete',
            'transport.routes.view', 'transport.routes.create', 'transport.routes.edit', 'transport.routes.delete',
            'transport.drivers.view', 'transport.drivers.create', 'transport.drivers.edit', 'transport.drivers.delete',
            'transport.schedules.view', 'transport.schedules.create', 'transport.schedules.edit', 'transport.schedules.delete',
            'transport.reports.view', 'transport.reports.create', 'transport.reports.edit', 'transport.reports.delete',

            // Audit Module
            'audit.view', 'audit.create', 'audit.edit', 'audit.delete', 'audit.manage',
            'audit.logs.view', 'audit.logs.create', 'audit.logs.edit', 'audit.logs.delete',
            'audit.reports.view', 'audit.reports.create', 'audit.reports.edit', 'audit.reports.delete',
            'audit.trails.view', 'audit.trails.create', 'audit.trails.edit', 'audit.trails.delete',

            // ChatBot Module
            'chatbot.view', 'chatbot.create', 'chatbot.edit', 'chatbot.delete', 'chatbot.manage',
            'chatbot.conversations.view', 'chatbot.conversations.create', 'chatbot.conversations.edit', 'chatbot.conversations.delete',
            'chatbot.settings.view', 'chatbot.settings.create', 'chatbot.settings.edit', 'chatbot.settings.delete',
            'chatbot.training.view', 'chatbot.training.create', 'chatbot.training.edit', 'chatbot.training.delete',

            // Compliance Module
            'compliance.view', 'compliance.create', 'compliance.edit', 'compliance.delete', 'compliance.manage',
            'compliance.policies.view', 'compliance.policies.create', 'compliance.policies.edit', 'compliance.policies.delete',
            'compliance.reports.view', 'compliance.reports.create', 'compliance.reports.edit', 'compliance.reports.delete',
            'compliance.standards.view', 'compliance.standards.create', 'compliance.standards.edit', 'compliance.standards.delete',

            // Document Module
            'document.view', 'document.create', 'document.edit', 'document.delete', 'document.manage',
            'document.files.view', 'document.files.create', 'document.files.edit', 'document.files.delete',
            'document.templates.view', 'document.templates.create', 'document.templates.edit', 'document.templates.delete',
            'document.categories.view', 'document.categories.create', 'document.categories.edit', 'document.categories.delete',

            // Marketplace Module
            'marketplace.view', 'marketplace.create', 'marketplace.edit', 'marketplace.delete', 'marketplace.manage',
            'marketplace.products.view', 'marketplace.products.create', 'marketplace.products.edit', 'marketplace.products.delete',
            'marketplace.orders.view', 'marketplace.orders.create', 'marketplace.orders.edit', 'marketplace.orders.delete',
            'marketplace.inventory.view', 'marketplace.inventory.create', 'marketplace.inventory.edit', 'marketplace.inventory.delete',

            // Notification Module
            'notification.view', 'notification.create', 'notification.edit', 'notification.delete', 'notification.manage',
            'notification.settings.view', 'notification.settings.create', 'notification.settings.edit', 'notification.settings.delete',
            'notification.templates.view', 'notification.templates.create', 'notification.templates.edit', 'notification.templates.delete',
            'notification.channels.view', 'notification.channels.create', 'notification.channels.edit', 'notification.channels.delete',

            // PWA Module
            'pwa.view', 'pwa.create', 'pwa.edit', 'pwa.delete', 'pwa.manage',
            'pwa.settings.view', 'pwa.settings.create', 'pwa.settings.edit', 'pwa.settings.delete',
            'pwa.manifest.view', 'pwa.manifest.create', 'pwa.manifest.edit', 'pwa.manifest.delete',

            // Research Module
            'research.view', 'research.create', 'research.edit', 'research.delete', 'research.manage',
            'research.projects.view', 'research.projects.create', 'research.projects.edit', 'research.projects.delete',
            'research.publications.view', 'research.publications.create', 'research.publications.edit', 'research.publications.delete',
            'research.funding.view', 'research.funding.create', 'research.funding.edit', 'research.funding.delete',

            // Welfare Module
            'welfare.view', 'welfare.create', 'welfare.edit', 'welfare.delete', 'welfare.manage',
            'welfare.programs.view', 'welfare.programs.create', 'welfare.programs.edit', 'welfare.programs.delete',
            'welfare.cases.view', 'welfare.cases.create', 'welfare.cases.edit', 'welfare.cases.delete',
            'welfare.reports.view', 'welfare.reports.create', 'welfare.reports.edit', 'welfare.reports.delete',

            // Cafeteria Module
            'cafeteria.view', 'cafeteria.create', 'cafeteria.edit', 'cafeteria.delete', 'cafeteria.manage',
            'cafeteria.menu.view', 'cafeteria.menu.create', 'cafeteria.menu.edit', 'cafeteria.menu.delete',
            'cafeteria.orders.view', 'cafeteria.orders.create', 'cafeteria.orders.edit', 'cafeteria.orders.delete',
            'cafeteria.inventory.view', 'cafeteria.inventory.create', 'cafeteria.inventory.edit', 'cafeteria.inventory.delete',

            // Inventory Module
            'inventory.view', 'inventory.create', 'inventory.edit', 'inventory.delete', 'inventory.manage',
            'inventory.items.view', 'inventory.items.create', 'inventory.items.edit', 'inventory.items.delete',
            'inventory.categories.view', 'inventory.categories.create', 'inventory.categories.edit', 'inventory.categories.delete',
            'inventory.suppliers.view', 'inventory.suppliers.create', 'inventory.suppliers.edit', 'inventory.suppliers.delete',

            // Assets Module
            'assets.view', 'assets.create', 'assets.edit', 'assets.delete', 'assets.manage',
            'assets.equipment.view', 'assets.equipment.create', 'assets.equipment.edit', 'assets.equipment.delete',
            'assets.maintenance.view', 'assets.maintenance.create', 'assets.maintenance.edit', 'assets.maintenance.delete',
            'assets.tracking.view', 'assets.tracking.create', 'assets.tracking.edit', 'assets.tracking.delete',

            // Alumni Module
            'alumni.view', 'alumni.create', 'alumni.edit', 'alumni.delete', 'alumni.manage',
            'alumni.profiles.view', 'alumni.profiles.create', 'alumni.profiles.edit', 'alumni.profiles.delete',
            'alumni.events.view', 'alumni.events.create', 'alumni.events.edit', 'alumni.events.delete',
            'alumni.network.view', 'alumni.network.create', 'alumni.network.edit', 'alumni.network.delete',

            // Localization Module
            'localization.view', 'localization.create', 'localization.edit', 'localization.delete', 'localization.manage',
            'localization.languages.view', 'localization.languages.create', 'localization.languages.edit', 'localization.languages.delete',
            'localization.translations.view', 'localization.translations.create', 'localization.translations.edit', 'localization.translations.delete',
            'localization.currencies.view', 'localization.currencies.create', 'localization.currencies.edit', 'localization.currencies.delete',

            // Additional Core Permissions
            'dashboard.view', 'dashboard.create', 'dashboard.edit', 'dashboard.delete',
            'profile.view', 'profile.create', 'profile.edit', 'profile.delete',
            'backup.view', 'backup.create', 'backup.edit', 'backup.delete',
            'logs.view', 'logs.create', 'logs.edit', 'logs.delete',
            'system.view', 'system.create', 'system.edit', 'system.delete',
            'maintenance.view', 'maintenance.create', 'maintenance.edit', 'maintenance.delete',
        ];

        $createdCount = 0;
        $assignedCount = 0;

        foreach ($permissions as $permissionName) {
            // Create permission if it doesn't exist
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            if ($permission->wasRecentlyCreated) {
                $createdCount++;
            }

            // Assign permission to admin role if not already assigned
            if (!$adminRole->hasPermissionTo($permissionName)) {
                $adminRole->givePermissionTo($permissionName);
                $assignedCount++;
            }
        }

        $this->command->info("✅ Created {$createdCount} new permissions");
        $this->command->info("✅ Assigned {$assignedCount} permissions to admin role");
        $this->command->info("🎉 Admin role now has comprehensive permissions across all modules!");
    }
}
