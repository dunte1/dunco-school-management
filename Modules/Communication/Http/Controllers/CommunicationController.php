<?php

namespace Modules\Communication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Communication\Models\Message;
use Modules\Communication\Models\MessageRecipient;
use Modules\Communication\Models\Broadcast;
use Modules\Communication\Models\Group;
use App\Models\Setting;
use App\Models\AuditLog;

class CommunicationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $inboxCount = MessageRecipient::where('recipient_id', $userId)->notDeleted()->where('is_read', false)->count();
        $outboxCount = Message::where('sender_id', $userId)->count();
        $broadcastCount = Broadcast::latest()->count();
        $groupCount = Group::whereHas('members', function ($q) use ($userId) {
            $q->where('user_id', $userId)->where('is_active', true);
        })->count();

        return view('communication::index', compact('inboxCount', 'outboxCount', 'broadcastCount', 'groupCount'));
    }

    public function create()
    {
        return view('communication::create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:email,sms,announcement,notice',
            'recipients' => 'required|array',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'subject' => $request->title,
            'body' => $request->body,
            'scheduled_at' => $request->scheduled_at,
        ]);

        foreach ($request->recipients as $recipientId) {
            MessageRecipient::create([
                'message_id' => $message->id,
                'recipient_id' => $recipientId,
            ]);
        }

        if ($request->scheduled_at) {
            AuditLog::log('communication.schedule', "Scheduled message: {$request->title}", null, [
                'message_id' => $message->id,
                'scheduled_at' => $request->scheduled_at,
            ]);
            return redirect()->route('communication.scheduled')->with('success', 'Message scheduled successfully.');
        }

        AuditLog::log('communication.create', "Created message: {$request->title}", null, ['message_id' => $message->id]);
        return redirect()->route('communication.index')->with('success', 'Message created successfully.');
    }

    public function show($id)
    {
        $message = Message::with('recipients.recipient', 'sender')->findOrFail($id);
        return view('communication::show', compact('message'));
    }

    public function edit($id)
    {
        $message = Message::findOrFail($id);
        return view('communication::edit', compact('message'));
    }

    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        $request->validate([
            'subject' => 'sometimes|string|max:255',
            'body' => 'sometimes|string',
        ]);

        $message->update($request->only(['subject', 'body']));

        AuditLog::log('communication.update', "Updated message: {$message->subject}", null, ['message_id' => $id]);
        return redirect()->route('communication.show', $id)->with('success', 'Message updated successfully.');
    }

    public function destroy($id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        AuditLog::log('communication.delete', "Deleted message: {$id}", null, ['message_id' => $id]);
        return redirect()->route('communication.index')->with('success', 'Message deleted successfully.');
    }

    public function templates()
    {
        $templates = Setting::where('key', 'like', 'msg_template_%')
            ->get()
            ->map(function ($s) {
                $data = json_decode($s->value, true);
                return [
                    'id' => $s->id,
                    'name' => $data['name'] ?? str_replace('msg_template_', '', $s->key),
                    'subject' => $data['subject'] ?? '',
                    'body' => $data['body'] ?? '',
                    'type' => $data['type'] ?? 'general',
                    'updated_at' => $s->updated_at,
                ];
            });

        return view('communication::templates', compact('templates'));
    }

    public function storeTemplate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'type' => 'required|in:general,fee_reminder,exam_notice,attendance,emergency',
        ]);

        $key = 'msg_template_' . strtolower(str_replace(' ', '_', $request->name));

        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => json_encode([
                    'name' => $request->name,
                    'subject' => $request->subject,
                    'body' => $request->body,
                    'type' => $request->type,
                ]),
                'type' => 'json',
                'description' => "Communication template: {$request->name}",
            ]
        );

        AuditLog::log('communication.template.create', "Created template: {$request->name}");
        return redirect()->route('communication.templates')->with('success', 'Template saved successfully.');
    }

    public function destroyTemplate($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();

        AuditLog::log('communication.template.delete', "Deleted template: {$setting->key}");
        return back()->with('success', 'Template deleted.');
    }

    public function settings()
    {
        $settings = Setting::whereIn('key', [
            'smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_encryption', 'smtp_from_address',
            'fcm_server_key', 'africastalking_username', 'africastalking_api_key',
            'sms_gateway_url', 'sms_api_key', 'sms_sender_id',
            'enable_email_notifications', 'enable_sms_notifications', 'enable_push_notifications',
        ])->get()->pluck('value', 'key')->toArray();

        return view('communication::settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'smtp_host' => 'nullable|string',
            'smtp_port' => 'nullable|integer',
            'smtp_user' => 'nullable|string',
            'smtp_pass' => 'nullable|string',
            'smtp_encryption' => 'nullable|in:tls,ssl,none',
            'smtp_from_address' => 'nullable|email',
            'enable_email_notifications' => 'nullable|boolean',
            'enable_sms_notifications' => 'nullable|boolean',
            'enable_push_notifications' => 'nullable|boolean',
        ]);

        foreach ($request->except(['_token']) as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => 'string', 'description' => "Communication setting: {$key}"]
            );
        }

        AuditLog::log('communication.settings.update', 'Updated communication settings');
        return back()->with('success', 'Communication settings updated.');
    }

    public function scheduled()
    {
        $userId = Auth::id();
        $scheduledMessages = Message::where('sender_id', $userId)
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '>', now())
            ->with('recipients.recipient')
            ->latest('scheduled_at')
            ->paginate(15);

        return view('communication::scheduled', compact('scheduledMessages'));
    }

    public function cancelScheduled($id)
    {
        $message = Message::where('sender_id', Auth::id())->findOrFail($id);
        $message->update(['scheduled_at' => null]);

        AuditLog::log('communication.schedule.cancel', "Cancelled scheduled message: {$id}");
        return back()->with('success', 'Scheduled message cancelled.');
    }
}
