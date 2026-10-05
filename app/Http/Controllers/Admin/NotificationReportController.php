<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationLog;

class NotificationReportController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('admin');
        $query = NotificationLog::with('template')->orderByDesc('sent_at');
        if ($channel = $request->get('channel')) {
            $query->where('channel', $channel);
        }
        if ($recipient = $request->get('recipient')) {
            $query->where('recipient', 'like', "%{$recipient}%");
        }
        $logs = $query->paginate(50);
        return view('admin.notifications.reports.index', compact('logs'));
    }
}


