<?php

namespace Modules\ChatBot\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ChatBot\Services\OpenAIService;
use Modules\ChatBot\Services\ChatBotService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class AdminController extends Controller
{
    protected $openAIService;
    protected $chatBotService;

    public function __construct(OpenAIService $openAIService, ChatBotService $chatBotService)
    {
        $this->openAIService = $openAIService;
        $this->chatBotService = $chatBotService;
    }

    /**
     * Display admin dashboard
     */
    public function index()
    {
        $statistics = $this->chatBotService->getStatistics();
        $openAIAvailable = $this->openAIService->isAvailable();
        $models = $this->openAIService->getModels();
        
        return view('chatbot::admin.dashboard', compact('statistics', 'openAIAvailable', 'models'));
    }

    /**
     * Display settings page
     */
    public function settings()
    {
        $config = config('chatbot');
        
        return view('chatbot::admin.settings', compact('config'));
    }

    /**
     * Update chatbot settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'openai_api_key' => 'nullable|string',
            'openai_model' => 'required|string',
            'openai_max_tokens' => 'required|integer|min:1|max:4000',
            'openai_temperature' => 'required|numeric|min:0|max:2',
            'chatbot_enabled' => 'boolean',
            'welcome_message' => 'required|string|max:500',
            'max_conversation_length' => 'required|integer|min:10|max:100',
            'session_timeout' => 'required|integer|min:5|max:120',
        ]);

        try {
            // Runtime .env writes were disabled for security (Phase 0).
            // ChatBot configuration must be managed via environment/config and redeployed.
            \Log::warning('Attempted runtime ChatBot settings update; disabled for security.', [
                'user_id' => optional($request->user())->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Runtime settings updates are disabled. Update the environment configuration and redeploy.',
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update settings: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test OpenAI connection
     */
    public function testOpenAI()
    {
        try {
            $isConnected = $this->openAIService->testConnection();
            
            return response()->json([
                'success' => true,
                'connected' => $isConnected,
                'message' => $isConnected ? 'OpenAI connection successful' : 'OpenAI connection failed',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'connected' => false,
                'message' => 'OpenAI connection failed: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Get OpenAI usage statistics
     */
    public function getUsage(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $usage = $this->openAIService->getUsage($date);
        
        return response()->json([
            'success' => true,
            'data' => $usage,
        ]);
    }

    /**
     * Get available OpenAI models
     */
    public function getModels()
    {
        $models = $this->openAIService->getModels();
        
        return response()->json([
            'success' => true,
            'data' => $models,
        ]);
    }

    /**
     * Clear chatbot cache
     */
    public function clearCache()
    {
        try {
            Cache::flush();
            
            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get system health status
     */
    public function getHealth()
    {
        $health = [
            'openai_available' => $this->openAIService->isAvailable(),
            'openai_connected' => $this->openAIService->testConnection(),
            'cache_working' => Cache::has('health_check'),
            'config_loaded' => !empty(config('chatbot')),
        ];

        // Set cache for health check
        Cache::put('health_check', true, 60);

        return response()->json([
            'success' => true,
            'data' => $health,
        ]);
    }

    /**
     * Update environment variable
     *
     * Disabled for security (Phase 0): runtime writes to .env allow arbitrary
     * configuration/secret tampering. Manage configuration via environment and deploy.
     */
    protected function updateEnvironmentVariable($key, $value)
    {
        \Log::warning('Blocked attempt to write environment variable at runtime.', [
            'key' => $key,
        ]);
    }
} 