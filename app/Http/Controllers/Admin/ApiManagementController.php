<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiKey;
use App\Models\WebhookEndpoint;
use Illuminate\Http\Request;

class ApiManagementController extends Controller
{
    public function index()
    {
        return view('admin.api.index', [
            'keys' => ApiKey::orderByDesc('id')->paginate(20),
            'endpoints' => WebhookEndpoint::orderByDesc('id')->paginate(20),
        ]);
    }

    public function createKey(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        $prefix = substr(bin2hex(random_bytes(4)),0,8);
        $secret = bin2hex(random_bytes(24));
        $key = $prefix.'.'.$secret;
        ApiKey::create([
            'user_id' => $request->user()->id,
            'name' => $data['name'],
            'prefix' => $prefix,
            'key' => $key,
        ]);
        return back()->with('success','API key created');
    }

    public function revokeKey(ApiKey $key)
    {
        $key->is_active = false;
        $key->save();
        return back()->with('success','API key revoked');
    }

    public function createWebhook(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'events' => 'nullable|array',
            'secret' => 'nullable|string|max:255',
        ]);
        WebhookEndpoint::create([
            'user_id' => $request->user()->id,
            'name' => $data['name'],
            'url' => $data['url'],
            'events' => $data['events'] ?? null,
            'secret' => $data['secret'] ?? null,
        ]);
        return back()->with('success','Webhook endpoint created');
    }
}


