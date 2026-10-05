<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;

class NotificationTemplateController extends Controller
{
    public function index()
    {
        $this->authorize('admin');
        $templates = NotificationTemplate::latest()->paginate(15);
        return view('admin.notifications.templates.index', compact('templates'));
    }

    public function create()
    {
        $this->authorize('admin');
        return view('admin.notifications.templates.form');
    }

    public function store(Request $request)
    {
        $this->authorize('admin');
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'channel' => ['required','in:email,sms,whatsapp'],
            'subject' => ['nullable','string','max:255'],
            'body' => ['required','string'],
            'variables' => ['nullable'],
            'is_active' => ['sometimes','boolean']
        ]);
        if (isset($data['variables']) && is_string($data['variables'])) {
            $decoded = json_decode($data['variables'], true);
            $data['variables'] = $decoded ?: null;
        }
        NotificationTemplate::create($data);
        return redirect()->route('admin.notifications.templates.index')->with('success','Template created');
    }

    public function edit(NotificationTemplate $template)
    {
        $this->authorize('admin');
        return view('admin.notifications.templates.form', compact('template'));
    }

    public function update(Request $request, NotificationTemplate $template)
    {
        $this->authorize('admin');
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'channel' => ['required','in:email,sms,whatsapp'],
            'subject' => ['nullable','string','max:255'],
            'body' => ['required','string'],
            'variables' => ['nullable'],
            'is_active' => ['sometimes','boolean']
        ]);
        if (isset($data['variables']) && is_string($data['variables'])) {
            $decoded = json_decode($data['variables'], true);
            $data['variables'] = $decoded ?: null;
        }
        $template->update($data);
        return redirect()->route('admin.notifications.templates.index')->with('success','Template updated');
    }

    public function testSend(Request $request, NotificationTemplate $template)
    {
        $this->authorize('admin');
        $data = $request->validate([
            'recipient' => ['required','string'],
            'channel' => ['required','in:email,sms,whatsapp'],
            'payload' => ['nullable'],
        ]);
        if (isset($data['payload']) && is_string($data['payload'])) {
            $decoded = json_decode($data['payload'], true);
            $data['payload'] = is_array($decoded) ? $decoded : [];
        }
        $dispatcher = app(\App\Services\Notifications\NotificationDispatcher::class);
        $dispatcher->queue(
            templateName: $template->name,
            channel: $data['channel'],
            recipient: $data['recipient'],
            payload: $data['payload'] ?? []
        );
        return back()->with('success', 'Test message queued.');
    }
}


