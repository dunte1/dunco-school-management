<?php

namespace Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ModuleManagementController extends Controller
{
    private string $statusFile;

    public function __construct()
    {
        $this->statusFile = base_path('modules_statuses.json');
    }

    public function index()
    {
        $statuses = $this->getStatuses();

        $moduleDescriptions = [
            'Core' => ['Core system functionality - Schools, Users, Roles, Permissions', 'fa-cog'],
            'Academic' => ['Academic management - Students, Classes, Subjects, Attendance', 'fa-graduation-cap'],
            'Examination' => ['Examination system - Exams, Questions, Results, Proctoring', 'fa-file-alt'],
            'Finance' => ['Financial management - Fees, Payments, Invoices, Reports', 'fa-money-bill-wave'],
            'HR' => ['Human Resources - Staff, Leave, Payroll, Contracts, Departments', 'fa-user-tie'],
            'Library' => ['Library management - Books, Members, Borrows, Reports', 'fa-book'],
            'Hostel' => ['Hostel management - Rooms, Allocations, Issues, Visitors', 'fa-hotel'],
            'Transport' => ['Transport management - Vehicles, Routes, Drivers, Trips', 'fa-bus'],
            'Timetable' => ['Timetable management - Schedules, Rooms, Allocations', 'fa-calendar-alt'],
            'Attendance' => ['Advanced attendance - QR, Biometric, Face recognition', 'fa-calendar-check'],
            'Communication' => ['Messaging system - Inbox, Announcements, Broadcasts', 'fa-comments'],
            'Portal' => ['Student/Parent portal - Schedule, Materials, Finance', 'fa-user-friends'],
            'Document' => ['Document management - Upload, Categorize, Share', 'fa-folder-open'],
            'Notification' => ['Notification system - Templates, Logs, Settings', 'fa-bell'],
            'Settings' => ['System settings - Global, Per-School configuration', 'fa-cog'],
            'API' => ['API management - Mobile API, Authentication', 'fa-plug'],
            'ChatBot' => ['AI ChatBot - Conversations, Training, Settings', 'fa-robot'],
            'Nursing' => ['Nursing education - Placements, Logbook, Skills, Clinical', 'fa-heartbeat'],
        ];

        return view('settings::modules.index', compact('statuses', 'moduleDescriptions'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'module' => 'required|string',
        ]);

        $module = $request->module;
        $statuses = $this->getStatuses();

        if (!isset($statuses[$module])) {
            return back()->with('error', "Module '{$module}' not found.");
        }

        $statuses[$module] = !$statuses[$module];
        $this->saveStatuses($statuses);

        $status = $statuses[$module] ? 'enabled' : 'disabled';

        // Clear navigation cache
        \Illuminate\Support\Facades\Cache::forget('nav:modules');

        return back()->with('success', "Module '{$module}' has been {$status}.");
    }

    private function getStatuses(): array
    {
        if (file_exists($this->statusFile)) {
            return json_decode(file_get_contents($this->statusFile), true) ?? [];
        }
        return [];
    }

    private function saveStatuses(array $statuses): void
    {
        file_put_contents($this->statusFile, json_encode($statuses, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
