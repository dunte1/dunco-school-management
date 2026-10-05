<?php

namespace Modules\ChatBot\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ChatBot\Services\WebhookService;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $webhookService;

    public function __construct(WebhookService $webhookService)
    {
        $this->webhookService = $webhookService;
    }

    /**
     * Handle incoming webhook
     *
     * @param Request $request
     * @param string $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleWebhook(Request $request, $event)
    {
        try {
            // Get the payload
            $payload = $request->all();
            
            // Handle the webhook
            $this->webhookService->handleIncomingWebhook($event, $payload);
            
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Error handling chatbot webhook: ' . $e->getMessage());
            
            return response()->json(['status' => 'error', 'message' => 'Failed to process webhook'], 500);
        }
    }
    
    /**
     * Handle chatbot-specific events
     *
     * @param Request $request
     * @param string $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleChatBotEvent(Request $request, $event)
    {
        try {
            // Get the payload
            $payload = $request->all();
            
            // Add event to payload
            $payload['event'] = $event;
            
            // Handle the webhook
            $this->webhookService->handleIncomingWebhook('chatbot.' . $event, $payload);
            
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Error handling chatbot event: ' . $e->getMessage());
            
            return response()->json(['status' => 'error', 'message' => 'Failed to process event'], 500);
        }
    }
}