<?php

namespace App\Http\Controllers;

use App\Models\WebhookEndpoint;
use Illuminate\Http\Request;

class WebhookReceiverController extends Controller
{
    public function receive(Request $request, string $prefix)
    {
        $endpoint = WebhookEndpoint::where('is_active', true)->where('prefix', $prefix)->first();
        if (!$endpoint) {
            return response()->json(['error' => 'Not found'], 404);
        }

        // Optional: verify signature header if secret set
        if (!empty($endpoint->secret)) {
            $sig = $request->header('X-Webhook-Signature');
            $calc = hash_hmac('sha256', $request->getContent(), $endpoint->secret);
            if (!$sig || !hash_equals($calc, $sig)) {
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        }

        // Dispatch to internal dispatcher/service
        try {
            app(\App\Services\Webhooks\WebhookDispatcher::class)->dispatch($endpoint, $request->all());
        } catch (\Throwable $e) {
            // log and continue
        }

        return response()->json(['status' => 'ok']);
    }
}


