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
use Modules\Communication\Models\Message;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Get date range for analytics (last 30 days by default)
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(30);

        // Override with request parameters if provided
        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date);
        }
        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date);
        }

        // Message analytics
        $messageStats = $this->getMessageAnalytics($startDate, $endDate);
        
        // Contact analytics
        $contactStats = $this->getContactAnalytics($startDate, $endDate);
        
        // Group analytics
        $groupStats = $this->getGroupAnalytics($startDate, $endDate);
        
        // Template analytics
        $templateStats = $this->getTemplateAnalytics($startDate, $endDate);
        
        // Announcement analytics
        $announcementStats = $this->getAnnouncementAnalytics($startDate, $endDate);
        
        // Schedule analytics
        $scheduleStats = $this->getScheduleAnalytics($startDate, $endDate);

        // Daily activity chart data
        $dailyActivity = $this->getDailyActivityData($startDate, $endDate);

        // Top performing content
        $topContent = $this->getTopPerformingContent($startDate, $endDate);

        return view('communication::analytics.index', compact(
            'messageStats',
            'contactStats', 
            'groupStats',
            'templateStats',
            'announcementStats',
            'scheduleStats',
            'dailyActivity',
            'topContent',
            'startDate',
            'endDate'
        ));
    }

    private function getMessageAnalytics($startDate, $endDate)
    {
        $totalMessages = Message::whereBetween('created_at', [$startDate, $endDate])->count();
        
        // Get message statistics based on actual model structure
        $sentMessages = Message::whereBetween('created_at', [$startDate, $endDate])->count(); // All messages are considered sent
        $failedMessages = 0; // No failure tracking in current model
        $pendingMessages = Message::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '>', now())
            ->count();

        // Get messages by type (group vs individual)
        $messagesByType = Message::select(DB::raw('CASE WHEN is_group = 1 THEN "Group" ELSE "Individual" END as type'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('is_group')
            ->get();

        $messagesByDay = Message::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total' => $totalMessages,
            'sent' => $sentMessages,
            'failed' => $failedMessages,
            'pending' => $pendingMessages,
            'success_rate' => $totalMessages > 0 ? 100 : 0, // Assuming all messages are successful
            'by_type' => $messagesByType,
            'by_day' => $messagesByDay
        ];
    }

    private function getContactAnalytics($startDate, $endDate)
    {
        $totalContacts = Contact::count();
        $newContacts = Contact::whereBetween('created_at', [$startDate, $endDate])->count();
        $activeContacts = Contact::active()->count();
        $inactiveContacts = Contact::where('is_active', false)->count();

        $contactsByCategory = Contact::select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->get();

        $contactsByDay = Contact::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total' => $totalContacts,
            'new' => $newContacts,
            'active' => $activeContacts,
            'inactive' => $inactiveContacts,
            'growth_rate' => $totalContacts > 0 ? round(($newContacts / $totalContacts) * 100, 2) : 0,
            'by_category' => $contactsByCategory,
            'by_day' => $contactsByDay
        ];
    }

    private function getGroupAnalytics($startDate, $endDate)
    {
        $totalGroups = Group::count();
        $newGroups = Group::whereBetween('created_at', [$startDate, $endDate])->count();
        $activeGroups = Group::active()->count();
        $inactiveGroups = Group::where('is_active', false)->count();

        $groupsByType = Group::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        $groupsByDay = Group::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total' => $totalGroups,
            'new' => $newGroups,
            'active' => $activeGroups,
            'inactive' => $inactiveGroups,
            'by_type' => $groupsByType,
            'by_day' => $groupsByDay
        ];
    }

    private function getTemplateAnalytics($startDate, $endDate)
    {
        $totalTemplates = Template::count();
        $newTemplates = Template::whereBetween('created_at', [$startDate, $endDate])->count();
        $activeTemplates = Template::active()->count();
        $inactiveTemplates = Template::where('is_active', false)->count();

        $templatesByType = Template::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        $templatesByDay = Template::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total' => $totalTemplates,
            'new' => $newTemplates,
            'active' => $activeTemplates,
            'inactive' => $inactiveTemplates,
            'by_type' => $templatesByType,
            'by_day' => $templatesByDay
        ];
    }

    private function getAnnouncementAnalytics($startDate, $endDate)
    {
        $totalAnnouncements = Announcement::count();
        $newAnnouncements = Announcement::whereBetween('created_at', [$startDate, $endDate])->count();
        $publishedAnnouncements = Announcement::where('is_published', true)->count();
        $draftAnnouncements = Announcement::where('is_published', false)->count();

        $announcementsByType = Announcement::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        $announcementsByDay = Announcement::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total' => $totalAnnouncements,
            'new' => $newAnnouncements,
            'published' => $publishedAnnouncements,
            'draft' => $draftAnnouncements,
            'by_type' => $announcementsByType,
            'by_day' => $announcementsByDay
        ];
    }

    private function getScheduleAnalytics($startDate, $endDate)
    {
        $totalSchedules = Schedule::count();
        $newSchedules = Schedule::whereBetween('created_at', [$startDate, $endDate])->count();
        $activeSchedules = Schedule::active()->count();
        $inactiveSchedules = Schedule::where('is_active', false)->count();

        $schedulesByType = Schedule::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        $schedulesByDay = Schedule::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'total' => $totalSchedules,
            'new' => $newSchedules,
            'active' => $activeSchedules,
            'inactive' => $inactiveSchedules,
            'by_type' => $schedulesByType,
            'by_day' => $schedulesByDay
        ];
    }

    private function getDailyActivityData($startDate, $endDate)
    {
        $dates = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        $activityData = [];
        foreach ($dates as $date) {
            $activityData[$date] = [
                'messages' => Message::whereDate('created_at', $date)->count(),
                'contacts' => Contact::whereDate('created_at', $date)->count(),
                'groups' => Group::whereDate('created_at', $date)->count(),
                'announcements' => Announcement::whereDate('created_at', $date)->count(),
                'schedules' => Schedule::whereDate('created_at', $date)->count(),
            ];
        }

        return $activityData;
    }

    private function getTopPerformingContent($startDate, $endDate)
    {
        // Top templates by usage (simplified since no direct relationship exists)
        $topTemplates = Template::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Top groups by member count
        $topGroups = Group::withCount('members')
            ->orderBy('members_count', 'desc')
            ->take(5)
            ->get();

        // Top announcements by creation date
        $topAnnouncements = Announcement::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return [
            'templates' => $topTemplates,
            'groups' => $topGroups,
            'announcements' => $topAnnouncements
        ];
    }
}
