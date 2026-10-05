<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Modules\Communication\Models\Contact;
use Modules\Communication\Models\Group;
use Modules\Communication\Models\Template;
use Modules\Communication\Models\Announcement;
use Modules\Communication\Models\Schedule;

class ReportController extends Controller
{
    public function index()
    {
        // Get basic statistics
        $stats = [
            'total_contacts' => Contact::count(),
            'active_contacts' => Contact::active()->count(),
            'total_groups' => Group::count(),
            'active_groups' => Group::active()->count(),
            'total_templates' => Template::count(),
            'active_templates' => Template::active()->count(),
            'total_announcements' => Announcement::count(),
            'published_announcements' => Announcement::where('is_published', true)->count(),
            'total_schedules' => Schedule::count(),
            'active_schedules' => Schedule::active()->count(),
        ];

        // Get contacts by category
        $contactsByCategory = Contact::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get();

        // Get groups by type
        $groupsByType = Group::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        // Get templates by type
        $templatesByType = Template::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        // Get recent activity
        $recentContacts = Contact::with('creator')->latest()->take(5)->get();
        $recentGroups = Group::with('creator')->latest()->take(5)->get();
        $recentAnnouncements = Announcement::with('creator')->latest()->take(5)->get();

        return view('communication::reports.index', compact(
            'stats',
            'contactsByCategory',
            'groupsByType',
            'templatesByType',
            'recentContacts',
            'recentGroups',
            'recentAnnouncements'
        ));
    }

    public function contacts(Request $request)
    {
        $query = Contact::with(['creator', 'groups']);

        // Apply filters
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $contacts = $query->latest()->paginate(20);

        // Get summary statistics
        $summary = [
            'total' => Contact::count(),
            'active' => Contact::active()->count(),
            'inactive' => Contact::where('is_active', false)->count(),
            'by_category' => Contact::select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->get()
        ];

        return view('communication::reports.contacts', compact('contacts', 'summary'));
    }

    public function groups(Request $request)
    {
        $query = Group::with(['creator', 'members']);

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $groups = $query->latest()->paginate(20);

        // Get summary statistics
        $summary = [
            'total' => Group::count(),
            'active' => Group::active()->count(),
            'inactive' => Group::where('is_active', false)->count(),
            'by_type' => Group::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->get(),
            'total_members' => DB::table('group_members')->count()
        ];

        return view('communication::reports.groups', compact('groups', 'summary'));
    }

    public function templates(Request $request)
    {
        $query = Template::with('creator');

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $templates = $query->latest()->paginate(20);

        // Get summary statistics
        $summary = [
            'total' => Template::count(),
            'active' => Template::active()->count(),
            'inactive' => Template::where('is_active', false)->count(),
            'by_type' => Template::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->get()
        ];

        return view('communication::reports.templates', compact('templates', 'summary'));
    }

    public function announcements(Request $request)
    {
        $query = Announcement::with('creator');

        // Apply filters
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $announcements = $query->latest()->paginate(20);

        // Get summary statistics
        $summary = [
            'total' => Announcement::count(),
            'published' => Announcement::where('is_published', true)->count(),
            'draft' => Announcement::where('is_published', false)->count(),
            'by_priority' => Announcement::select('priority', DB::raw('count(*) as count'))
                ->groupBy('priority')
                ->get()
        ];

        return view('communication::reports.announcements', compact('announcements', 'summary'));
    }

    public function schedules(Request $request)
    {
        $query = Schedule::with(['creator', 'template']);

        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('frequency')) {
            $query->where('frequency', $request->frequency);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $schedules = $query->latest()->paginate(20);

        // Get summary statistics
        $summary = [
            'total' => Schedule::count(),
            'active' => Schedule::active()->count(),
            'inactive' => Schedule::where('is_active', false)->count(),
            'by_type' => Schedule::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->get(),
            'by_frequency' => Schedule::select('frequency', DB::raw('count(*) as count'))
                ->groupBy('frequency')
                ->get()
        ];

        return view('communication::reports.schedules', compact('schedules', 'summary'));
    }

    public function export($type, Request $request)
    {
        switch ($type) {
            case 'contacts':
                return $this->exportContacts($request);
            case 'groups':
                return $this->exportGroups($request);
            case 'templates':
                return $this->exportTemplates($request);
            case 'announcements':
                return $this->exportAnnouncements($request);
            case 'schedules':
                return $this->exportSchedules($request);
            default:
                abort(404);
        }
    }

    private function exportContacts(Request $request)
    {
        $contacts = Contact::with(['creator', 'groups'])->get();
        
        $filename = 'contacts_report_' . date('Y-m-d_H-i-s') . '.csv';
        $content = "Name,Email,Phone,Organization,Position,Category,Status,Created By,Created At,Groups\n";
        
        foreach ($contacts as $contact) {
            $groups = $contact->groups->pluck('name')->implode('; ');
            $content .= "\"{$contact->name}\",\"{$contact->email}\",\"{$contact->phone}\",\"{$contact->organization}\",\"{$contact->position}\",\"{$contact->category}\",\"{$contact->is_active}\",\"{$contact->creator->name}\",\"{$contact->created_at}\",\"{$groups}\"\n";
        }
        
        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }

    private function exportGroups(Request $request)
    {
        $groups = Group::with(['creator', 'members'])->get();
        
        $filename = 'groups_report_' . date('Y-m-d_H-i-s') . '.csv';
        $content = "Name,Description,Type,Status,Members,Created By,Created At\n";
        
        foreach ($groups as $group) {
            $memberCount = $group->members->count();
            $content .= "\"{$group->name}\",\"{$group->description}\",\"{$group->type}\",\"{$group->is_active}\",\"{$memberCount}\",\"{$group->creator->name}\",\"{$group->created_at}\"\n";
        }
        
        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }

    private function exportTemplates(Request $request)
    {
        $templates = Template::with('creator')->get();
        
        $filename = 'templates_report_' . date('Y-m-d_H-i-s') . '.csv';
        $content = "Name,Subject,Type,Status,Created By,Created At\n";
        
        foreach ($templates as $template) {
            $content .= "\"{$template->name}\",\"{$template->subject}\",\"{$template->type}\",\"{$template->is_active}\",\"{$template->creator->name}\",\"{$template->created_at}\"\n";
        }
        
        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }

    private function exportAnnouncements(Request $request)
    {
        $announcements = Announcement::with('creator')->get();
        
        $filename = 'announcements_report_' . date('Y-m-d_H-i-s') . '.csv';
        $content = "Title,Content,Priority,Published,Status,Created By,Created At\n";
        
        foreach ($announcements as $announcement) {
            $content .= "\"{$announcement->title}\",\"{$announcement->content}\",\"{$announcement->priority}\",\"{$announcement->is_published}\",\"{$announcement->is_active}\",\"{$announcement->creator->name}\",\"{$announcement->created_at}\"\n";
        }
        
        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }

    private function exportSchedules(Request $request)
    {
        $schedules = Schedule::with(['creator', 'template'])->get();
        
        $filename = 'schedules_report_' . date('Y-m-d_H-i-s') . '.csv';
        $content = "Title,Type,Frequency,Start Date,End Date,Time,Status,Created By,Created At\n";
        
        foreach ($schedules as $schedule) {
            $content .= "\"{$schedule->title}\",\"{$schedule->type}\",\"{$schedule->frequency}\",\"{$schedule->start_date}\",\"{$schedule->end_date}\",\"{$schedule->time}\",\"{$schedule->is_active}\",\"{$schedule->creator->name}\",\"{$schedule->created_at}\"\n";
        }
        
        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }
}