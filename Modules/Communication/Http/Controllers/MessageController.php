<?php

namespace Modules\Communication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Communication\Models\Message;
use Modules\Communication\Models\MessageRecipient;
use App\Models\User;
use Modules\Settings\App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Modules\Communication\Models\MessageDeliveryLog;
use App\Events\MessageSent;
use Modules\Communication\Models\Group;
use Modules\Communication\Models\GroupMember;
use Modules\Communication\Models\Broadcast;
use Modules\Communication\Models\BroadcastRecipient;
use Modules\Communication\Models\Notification;
use Modules\Communication\Models\UserNotificationPreference;

class MessageController extends Controller
{
    public function inbox(Request $request)
    {
        $userId = Auth::id();
        $query = MessageRecipient::with('message.sender')
            ->where('recipient_id', $userId)
            ->notDeleted();

        // Search and filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('message', function ($q) use ($search) {
                $q->where('subject', 'like', "%$search%")
                  ->orWhere('body', 'like', "%$search%")
                  ->orWhereHas('sender', function ($qs) use ($search) {
                      $qs->where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%") ;
                  });
            });
        }
        if ($request->filled('filter')) {
            if ($request->filter === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->filter === 'starred') {
                $query->where('is_starred', true);
            }
        }
        $messages = $query->latest()->paginate(15);
        $unreadCount = MessageRecipient::where('recipient_id', $userId)->notDeleted()->where('is_read', false)->count();
        return view('communication::messages.inbox', compact('messages', 'unreadCount'));
    }

    public function outbox(Request $request)
    {
        $userId = Auth::id();
        $query = Message::with('recipients.recipient')
            ->where('sender_id', $userId);

        // Search and filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%$search%")
                  ->orWhere('body', 'like', "%$search%")
                  ->orWhereHas('recipients.recipient', function ($qr) use ($search) {
                      $qr->where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%") ;
                  });
            });
        }
        // (Optional) Add status filter if/when implemented
        $messages = $query->latest()->paginate(15);
        return view('communication::messages.outbox', compact('messages'));
    }

    public function compose()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('communication::messages.compose', compact('users'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);
        $message = Message::create([
            'sender_id' => Auth::id(),
            'subject' => $request->subject,
            'body' => $request->body,
        ]);
        foreach ($request->recipients as $recipientId) {
            $recipient = MessageRecipient::create([
                'message_id' => $message->id,
                'recipient_id' => $recipientId,
            ]);
            event(new MessageSent($recipient));
        }

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('attachments', 'public');
                    \Modules\Communication\Models\MessageAttachment::create([
                        'message_id' => $message->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'uploaded_by' => Auth::id(),
                    ]);
                }
            }
        }

        // Fetch global settings
        $settings = Setting::whereIn('key', [
            'smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_encryption', 'smtp_from_address',
            'fcm_server_key', 'africastalking_username', 'africastalking_api_key',
        ])->pluck('value', 'key');

        // Get recipient users
        $recipients = \App\Models\User::whereIn('id', $request->recipients)->get();

        // Send as Email
        if ($request->has('send_email')) {
            foreach ($recipients as $recipient) {
                if ($recipient->notify_email) {
                    try {
                        Mail::raw($request->body, function ($mail) use ($recipient, $request, $settings) {
                            $mail->to($recipient->email)
                                ->subject($request->subject ?? 'New Message')
                                ->from($settings['smtp_from_address'] ?? config('mail.from.address'), config('mail.from.name'));
                        });
                        MessageDeliveryLog::create([
                            'message_id' => $message->id,
                            'recipient_id' => $recipient->id,
                            'channel' => 'email',
                            'status' => 'sent',
                            'response' => null,
                        ]);
                    } catch (\Exception $e) {
                        MessageDeliveryLog::create([
                            'message_id' => $message->id,
                            'recipient_id' => $recipient->id,
                            'channel' => 'email',
                            'status' => 'failed',
                            'response' => $e->getMessage(),
                        ]);
                    }
                }
            }
        }

        // Send as SMS
        if ($request->has('send_sms')) {
            $smsService = new \Modules\Communication\Services\SmsService(
                $settings['africastalking_username'] ?? '',
                $settings['africastalking_api_key'] ?? ''
            );
            foreach ($recipients as $recipient) {
                if ($recipient->phone && $recipient->notify_sms) {
                    $response = $status = null;
                    try {
                        $response = $smsService->send($recipient->phone, $request->body);
                        $status = 'sent';
                    } catch (\Exception $e) {
                        $response = $e->getMessage();
                        $status = 'failed';
                    }
                    MessageDeliveryLog::create([
                        'message_id' => $message->id,
                        'recipient_id' => $recipient->id,
                        'channel' => 'sms',
                        'status' => $status,
                        'response' => $response,
                    ]);
                }
            }
        }

        // Send as Push Notification
        if ($request->has('send_push')) {
            $fcmService = new \Modules\Communication\Services\FcmService($settings['fcm_server_key'] ?? '');
            foreach ($recipients as $recipient) {
                if (($recipient->fcm_token ?? false) && $recipient->notify_push) {
                    $response = $status = null;
                    try {
                        $response = $fcmService->send($recipient->fcm_token, $request->subject ?? 'New Message', $request->body);
                        $status = 'sent';
                    } catch (\Exception $e) {
                        $response = $e->getMessage();
                        $status = 'failed';
                    }
                    MessageDeliveryLog::create([
                        'message_id' => $message->id,
                        'recipient_id' => $recipient->id,
                        'channel' => 'push',
                        'status' => $status,
                        'response' => $response,
                    ]);
                }
            }
        }

        return redirect()->route('communication.outbox')->with('success', 'Message sent successfully.');
    }

    public function show($id)
    {
        $userId = Auth::id();
        $recipient = MessageRecipient::with('message.sender')->where('id', $id)->where('recipient_id', $userId)->firstOrFail();
        $recipient->is_read = true;
        $recipient->read_at = now();
        $recipient->save();
        return view('communication::messages.show', ['recipient' => $recipient]);
    }

    // Mark as read
    public function markAsRead($id)
    {
        $userId = Auth::id();
        $recipient = MessageRecipient::where('id', $id)->where('recipient_id', $userId)->firstOrFail();
        $recipient->is_read = true;
        $recipient->read_at = now();
        $recipient->save();
        return back()->with('success', 'Message marked as read.');
    }

    // Mark as unread
    public function markAsUnread($id)
    {
        $userId = Auth::id();
        $recipient = MessageRecipient::where('id', $id)->where('recipient_id', $userId)->firstOrFail();
        $recipient->is_read = false;
        $recipient->read_at = null;
        $recipient->save();
        return back()->with('success', 'Message marked as unread.');
    }

    // Star/unstar
    public function toggleStar($id)
    {
        $userId = Auth::id();
        $recipient = MessageRecipient::where('id', $id)->where('recipient_id', $userId)->firstOrFail();
        $recipient->is_starred = !$recipient->is_starred;
        $recipient->save();
        return back()->with('success', $recipient->is_starred ? 'Message starred.' : 'Message unstarred.');
    }

    // Soft delete
    public function delete($id)
    {
        $userId = Auth::id();
        $recipient = MessageRecipient::where('id', $id)->where('recipient_id', $userId)->firstOrFail();
        $recipient->is_deleted = true;
        $recipient->save();
        return back()->with('success', 'Message deleted.');
    }

    // Bulk actions
    public function bulkAction(Request $request)
    {
        $userId = Auth::id();
        $action = $request->input('action');
        $ids = $request->input('ids', []);
        $recipients = MessageRecipient::whereIn('id', $ids)->where('recipient_id', $userId)->get();
        foreach ($recipients as $recipient) {
            if ($action === 'read') {
                $recipient->is_read = true;
                $recipient->read_at = now();
            } elseif ($action === 'unread') {
                $recipient->is_read = false;
                $recipient->read_at = null;
            } elseif ($action === 'star') {
                $recipient->is_starred = true;
            } elseif ($action === 'unstar') {
                $recipient->is_starred = false;
            } elseif ($action === 'delete') {
                $recipient->is_deleted = true;
            }
            $recipient->save();
        }
        return back()->with('success', 'Bulk action completed.');
    }

    public function groups()
    {
        $userId = Auth::id();
        $userGroups = Group::whereHas('members', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('is_active', true);
        })->with(['creator', 'activeMembers.user'])->get();
        return view('communication::messages.groups', compact('userGroups'));
    }

    public function createGroup()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('communication::messages.create-group', compact('users'));
    }

    public function storeGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'members' => 'required|array|min:1',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'creator_id' => Auth::id(),
        ]);

        // Add creator as admin
        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => Auth::id(),
            'role' => 'admin',
        ]);

        // Add members
        foreach ($request->members as $memberId) {
            GroupMember::create([
                'group_id' => $group->id,
                'user_id' => $memberId,
                'role' => 'member',
            ]);
        }

        return redirect()->route('communication.groups')->with('success', 'Group created successfully.');
    }

    public function showGroup($id)
    {
        $userId = Auth::id();
        $group = Group::whereHas('members', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('is_active', true);
        })->with(['creator', 'activeMembers.user', 'messages.sender'])->findOrFail($id);
        
        $isAdmin = $group->members()->where('user_id', $userId)->where('role', 'admin')->exists();
        $availableUsers = User::whereNotIn('id', $group->activeMembers->pluck('user_id'))->get();
        
        return view('communication::messages.show-group', compact('group', 'isAdmin', 'availableUsers'));
    }

    public function sendGroupMessage(Request $request, $id)
    {
        $userId = Auth::id();
        $group = Group::whereHas('members', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('is_active', true);
        })->findOrFail($id);

        $request->validate([
            'body' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => $userId,
            'body' => $request->body,
            'is_group' => true,
            'group_id' => $group->id,
        ]);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('attachments', 'public');
                    \Modules\Communication\Models\MessageAttachment::create([
                        'message_id' => $message->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'uploaded_by' => $userId,
                    ]);
                }
            }
        }

        return back()->with('success', 'Message sent to group.');
    }

    public function addGroupMember(Request $request, $id)
    {
        $userId = Auth::id();
        $group = Group::whereHas('members', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('role', 'admin');
        })->findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => $request->user_id,
            'role' => 'member',
        ]);

        return back()->with('success', 'Member added to group.');
    }

    public function removeGroupMember($groupId, $userId)
    {
        $adminId = Auth::id();
        $group = Group::whereHas('members', function ($query) use ($adminId) {
            $query->where('user_id', $adminId)->where('role', 'admin');
        })->findOrFail($groupId);

        $member = GroupMember::where('group_id', $group->id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $member->update(['is_active' => false]);

        return back()->with('success', 'Member removed from group.');
    }

    public function reply($id)
    {
        $userId = Auth::id();
        $recipient = MessageRecipient::with('message.sender')->where('id', $id)->where('recipient_id', $userId)->firstOrFail();
        return view('communication::messages.reply', ['recipient' => $recipient]);
    }

    public function sendReply(Request $request, $id)
    {
        $userId = Auth::id();
        $recipient = MessageRecipient::with('message')->where('id', $id)->where('recipient_id', $userId)->firstOrFail();
        $request->validate([
            'body' => 'required|string',
        ]);
        $reply = Message::create([
            'sender_id' => $userId,
            'subject' => 'Re: ' . ($recipient->message->subject ?? ''),
            'body' => $request->body,
        ]);
        MessageRecipient::create([
            'message_id' => $reply->id,
            'recipient_id' => $recipient->message->sender_id,
        ]);
        // Handle attachments (for replies)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('attachments', 'public');
                    \Modules\Communication\Models\MessageAttachment::create([
                        'message_id' => $reply->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'uploaded_by' => Auth::id(),
                    ]);
                }
            }
        }
        // Optionally, trigger notifications/events here
        return redirect()->route('communication.outbox')->with('success', 'Reply sent successfully.');
    }

    public function broadcasts()
    {
        // Admin only
        if (!auth()->user()->hasRole(['admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }

        $broadcasts = Broadcast::with(['creator', 'recipients'])
            ->latest()
            ->paginate(15);
        
        return view('communication::messages.broadcasts', compact('broadcasts'));
    }

    public function createBroadcast()
    {
        // Admin only
        if (!auth()->user()->hasRole(['admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }

        $users = User::all();
        $groups = Group::all();
        return view('communication::messages.create-broadcast', compact('users', 'groups'));
    }

    public function storeBroadcast(Request $request)
    {
        // Admin only
        if (!auth()->user()->hasRole(['admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:announcement,emergency,reminder,notice',
            'target_type' => 'required|in:all,role,class,group,individual',
            'target_data' => 'nullable|array',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $broadcast = Broadcast::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'target_type' => $request->target_type,
            'target_data' => $request->target_data,
            'created_by' => Auth::id(),
            'scheduled_at' => $request->scheduled_at,
        ]);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file && $file->isValid()) {
                    $path = $file->store('attachments', 'public');
                    \Modules\Communication\Models\MessageAttachment::create([
                        'attachable_type' => Broadcast::class,
                        'attachable_id' => $broadcast->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getClientMimeType(),
                        'uploaded_by' => Auth::id(),
                    ]);
                }
            }
        }

        return redirect()->route('communication.broadcasts')->with('success', 'Broadcast created successfully.');
    }

    public function showBroadcast($id)
    {
        // Admin only
        if (!auth()->user()->hasRole(['admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }

        $broadcast = Broadcast::with(['creator', 'recipients.user'])->findOrFail($id);
        return view('communication::messages.show-broadcast', compact('broadcast'));
    }

    public function sendBroadcast($id)
    {
        // Admin only
        if (!auth()->user()->hasRole(['admin', 'super_admin'])) {
            abort(403, 'Unauthorized');
        }

        $broadcast = Broadcast::findOrFail($id);
        
        // Get target users based on target_type and target_data
        $targetUsers = $this->getTargetUsers($broadcast);
        
        // Create recipients
        foreach ($targetUsers as $user) {
            BroadcastRecipient::create([
                'broadcast_id' => $broadcast->id,
                'user_id' => $user->id,
            ]);
        }
        
        $broadcast->update(['sent_at' => now()]);
        
        return back()->with('success', "Broadcast sent to {$targetUsers->count()} recipients.");
    }

    public function announcements()
    {
        $userId = Auth::id();
        $announcements = BroadcastRecipient::with(['broadcast.creator'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(15);
        
        return view('communication::messages.announcements', compact('announcements'));
    }

    public function showAnnouncement($id)
    {
        $userId = Auth::id();
        $recipient = BroadcastRecipient::with(['broadcast.creator', 'broadcast.attachments'])
            ->where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
        
        // Mark as read
        if (!$recipient->is_read) {
            $recipient->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
        
        return view('communication::messages.show-announcement', compact('recipient'));
    }

    public function markAnnouncementRead($id)
    {
        $userId = Auth::id();
        $recipient = BroadcastRecipient::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
        
        $recipient->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        
        return back()->with('success', 'Announcement marked as read.');
    }

    public function notifications(Request $request)
    {
        $userId = Auth::id();
        $query = Notification::forUser($userId);

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        // Filter by read status
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->unread();
            } elseif ($request->status === 'read') {
                $query->read();
            }
        }

        $notifications = $query->latest()->paginate(20);
        $unreadCount = Notification::forUser($userId)->unread()->count();
        
        return view('communication::messages.notifications', compact('notifications', 'unreadCount'));
    }

    public function notificationSettings()
    {
        $userId = Auth::id();
        $preferences = UserNotificationPreference::forUser($userId)->get();
        
        // Get all possible combinations
        $channels = ['email', 'sms', 'push', 'in_app'];
        $categories = ['academic', 'finance', 'events', 'system', 'communication'];
        
        $settings = [];
        foreach ($channels as $channel) {
            foreach ($categories as $category) {
                $pref = $preferences->where('channel', $channel)->where('category', $category)->first();
                if (!$pref) {
                    $pref = UserNotificationPreference::create([
                        'user_id' => $userId,
                        'channel' => $channel,
                        'category' => $category,
                        'enabled' => true,
                        'sound' => true,
                        'vibration' => true,
                    ]);
                }
                $settings[$channel][$category] = $pref;
            }
        }
        
        return view('communication::messages.notification-settings', compact('settings', 'channels', 'categories'));
    }

    public function updateNotificationSettings(Request $request)
    {
        $userId = Auth::id();
        
        foreach ($request->input('preferences', []) as $channel => $categories) {
            foreach ($categories as $category => $settings) {
                UserNotificationPreference::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'channel' => $channel,
                        'category' => $category,
                    ],
                    [
                        'enabled' => isset($settings['enabled']),
                        'sound' => isset($settings['sound']),
                        'vibration' => isset($settings['vibration']),
                    ]
                );
            }
        }
        
        return back()->with('success', 'Notification settings updated successfully.');
    }

    public function markNotificationRead($id)
    {
        $userId = Auth::id();
        $notification = Notification::forUser($userId)->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllNotificationsRead()
    {
        $userId = Auth::id();
        Notification::forUser($userId)->unread()->update(['read_at' => now()]);
        
        return back()->with('success', 'All notifications marked as read.');
    }

    public function getNotificationCount()
    {
        $userId = Auth::id();
        $count = Notification::forUser($userId)->unread()->count();
        
        return response()->json(['count' => $count]);
    }

    // Helper method to send notifications
    public static function sendNotification($userId, $type, $title, $data = [])
    {
        // Check user preferences
        $preferences = UserNotificationPreference::forUser($userId)
            ->byCategory($type)
            ->enabled()
            ->get();

        if ($preferences->isEmpty()) {
            return false;
        }

        // Create in-app notification
        $notification = Notification::create([
            'type' => $type,
            'title' => $title,
            'data' => $data,
            'notifiable_id' => $userId,
            'notifiable_type' => \App\Models\User::class,
        ]);

        // Send via enabled channels
        foreach ($preferences as $pref) {
            switch ($pref->channel) {
                case 'email':
                    // Send email notification
                    break;
                case 'sms':
                    // Send SMS notification
                    break;
                case 'push':
                    // Send push notification
                    break;
            }
        }

        return $notification;
    }

    private function getTargetUsers($broadcast)
    {
        switch ($broadcast->target_type) {
            case 'all':
                return User::all();
            case 'role':
                return User::whereIn('role', $broadcast->target_data)->get();
            case 'class':
                // Assuming you have a classes relationship
                return User::whereHas('classes', function ($query) use ($broadcast) {
                    $query->whereIn('class_id', $broadcast->target_data);
                })->get();
            case 'group':
                return User::whereHas('groupMembers', function ($query) use ($broadcast) {
                    $query->whereIn('group_id', $broadcast->target_data);
                })->get();
            case 'individual':
                return User::whereIn('id', $broadcast->target_data)->get();
            default:
                return collect();
        }
    }
} 