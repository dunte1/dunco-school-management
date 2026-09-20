<?php

namespace Modules\Notification\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Notification\Models\NotificationTemplate;
use Modules\Notification\Models\NotificationLog;

class NotificationController extends Controller
{
    /**
     * Display listing of notification logs.
     */
    public function index(Request $request)
    {
        $query = NotificationLog::with(['template', 'recipient'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('channel')) {
            $query->where('channel', $request->channel);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('recipient_email', 'like', "%{$search}%");
            });
        }

        $notifications = $query->paginate(20);
        $stats = [
            'total' => NotificationLog::count(),
            'sent' => NotificationLog::sent()->count(),
            'failed' => NotificationLog::failed()->count(),
            'pending' => NotificationLog::pending()->count(),
        ];

        return view('notification::index', compact('notifications', 'stats'));
    }

    /**
     * Show form for creating a new notification.
     */
    public function create()
    {
        $templates = NotificationTemplate::active()->get();
        return view('notification::create', compact('templates'));
    }

    /**
     * Store a new notification and send it.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_email' => 'required|email',
            'recipient_phone' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'channel' => 'required|in:email,sms,both',
            'template_id' => 'nullable|integer|exists:notification_templates,id',
        ]);

        $log = NotificationLog::create([
            'template_id' => $validated['template_id'] ?? null,
            'recipient_email' => $validated['recipient_email'],
            'recipient_phone' => $validated['recipient_phone'] ?? null,
            'channel' => $validated['channel'],
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'status' => 'pending',
            'school_id' => auth()->user()->school_id ?? null,
        ]);

        try {
            if (in_array($validated['channel'], ['email', 'both'])) {
                \Mail::raw($validated['body'], function ($message) use ($validated) {
                    $message->to($validated['recipient_email'])
                            ->subject($validated['subject']);
                });
            }

            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }

        return redirect()->route('notification.index')->with('success', 'Notification sent successfully.');
    }

    /**
     * Display the specified notification log.
     */
    public function show($id)
    {
        $notification = NotificationLog::with(['template', 'recipient'])->findOrFail($id);
        return view('notification::show', compact('notification'));
    }

    /**
     * Show form for editing a notification.
     */
    public function edit($id)
    {
        $notification = NotificationLog::findOrFail($id);
        $templates = NotificationTemplate::active()->get();
        return view('notification::edit', compact('notification', 'templates'));
    }

    /**
     * Update the specified notification.
     */
    public function update(Request $request, $id)
    {
        $notification = NotificationLog::findOrFail($id);

        $validated = $request->validate([
            'recipient_email' => 'required|email',
            'recipient_phone' => 'nullable|string',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'channel' => 'required|in:email,sms,both',
        ]);

        $notification->update($validated);

        return redirect()->route('notification.show', $id)->with('success', 'Notification updated successfully.');
    }

    /**
     * Remove the specified notification.
     */
    public function destroy($id)
    {
        NotificationLog::findOrFail($id)->delete();
        return redirect()->route('notification.index')->with('success', 'Notification deleted successfully.');
    }

    /**
     * Send/retry a notification.
     */
    public function send($id)
    {
        $log = NotificationLog::findOrFail($id);

        try {
            if (in_array($log->channel, ['email', 'both'])) {
                \Mail::raw($log->body, function ($message) use ($log) {
                    $message->to($log->recipient_email)
                            ->subject($log->subject);
                });
            }

            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            return redirect()->route('notification.index')->with('success', 'Notification sent successfully.');
        } catch (\Exception $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->route('notification.index')->with('error', 'Failed to send: ' . $e->getMessage());
        }
    }

    /**
     * Display notification templates.
     */
    public function templates(Request $request)
    {
        $query = NotificationTemplate::orderByDesc('created_at');

        if ($request->filled('type')) {
            $query->ofType($request->type);
        }
        if ($request->filled('channel')) {
            $query->ofChannel($request->channel);
        }

        $templates = $query->paginate(20);
        return view('notification::templates', compact('templates'));
    }

    /**
     * Store a new template.
     */
    public function storeTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:notification_templates,slug',
            'type' => 'required|string',
            'channel' => 'required|in:email,sms,both',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'variables' => 'nullable|string',
        ]);

        $variables = null;
        if (!empty($validated['variables'])) {
            $variables = array_map('trim', explode(',', $validated['variables']));
        }

        NotificationTemplate::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'type' => $validated['type'],
            'channel' => $validated['channel'],
            'subject' => $validated['subject'] ?? null,
            'body' => $validated['body'],
            'variables' => $variables,
            'is_active' => true,
            'school_id' => auth()->user()->school_id ?? null,
        ]);

        return redirect()->route('notification.templates')->with('success', 'Template created successfully.');
    }

    /**
     * Settings page.
     */
    public function settings()
    {
        $stats = [
            'total_sent' => NotificationLog::sent()->count(),
            'total_failed' => NotificationLog::failed()->count(),
            'total_pending' => NotificationLog::pending()->count(),
            'email_count' => NotificationLog::ofChannel('email')->count(),
            'sms_count' => NotificationLog::ofChannel('sms')->count(),
            'templates_count' => NotificationTemplate::count(),
        ];

        return view('notification::settings', compact('stats'));
    }

    /**
     * Manage page alias.
     */
    public function manage()
    {
        return redirect()->route('notification.index');
    }
}
