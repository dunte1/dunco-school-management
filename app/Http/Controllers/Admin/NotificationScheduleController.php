<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationSchedule;
use App\Models\NotificationTemplate;

class NotificationScheduleController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $schedules = NotificationSchedule::with('template')->latest()->paginate(20);
        return view('admin.notifications.schedules.index', compact('schedules'));
    }

    public function create()
    {
        $this->authorize('admin');
        $templates = NotificationTemplate::where('is_active', true)->pluck('name','id');
        return view('admin.notifications.schedules.form', compact('templates'));
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $data = $request->validate([
            'template_id' => ['required','exists:notification_templates,id'],
            'channel' => ['required','in:email,sms,whatsapp'],
            'audience' => ['nullable'],
            'cron' => ['required','string','max:255'],
            'is_active' => ['sometimes','boolean'],
        ]);
        if (isset($data['audience']) && is_string($data['audience'])) {
            $decoded = json_decode($data['audience'], true);
            $data['audience'] = is_array($decoded) ? $decoded : null;
        }
        NotificationSchedule::create($data);
        return redirect()->route('admin.notifications.schedules.index')->with('success','Schedule created');
    }

    public function edit(NotificationSchedule $schedule)
    {
        $this->authorize('admin');
        $templates = NotificationTemplate::where('is_active', true)->pluck('name','id');
        return view('admin.notifications.schedules.form', compact('schedule','templates'));
    }

    public function update(Request $request, NotificationSchedule $schedule)
    {
        $this->authorize('admin');
        $data = $request->validate([
            'template_id' => ['required','exists:notification_templates,id'],
            'channel' => ['required','in:email,sms,whatsapp'],
            'audience' => ['nullable'],
            'cron' => ['required','string','max:255'],
            'is_active' => ['sometimes','boolean'],
        ]);
        if (isset($data['audience']) && is_string($data['audience'])) {
            $decoded = json_decode($data['audience'], true);
            $data['audience'] = is_array($decoded) ? $decoded : null;
        }
        $schedule->update($data);
        return redirect()->route('admin.notifications.schedules.index')->with('success','Schedule updated');
    }

    public function destroy(NotificationSchedule $schedule)
    {
        $this->authorize('admin');
        $schedule->delete();
        return redirect()->route('admin.notifications.schedules.index')->with('success','Schedule deleted');
    }
}


