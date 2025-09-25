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
            // Update environment variables
            $this->updateEnvironmentVariable('OPENAI_API_KEY', $request->input('openai_api_key'));
            $this->updateEnvironmentVariable('OPENAI_MODEL', $request->input('openai_model'));
            $this->updateEnvironmentVariable('OPENAI_MAX_TOKENS', $request->input('openai_max_tokens'));
            $this->updateEnvironmentVariable('OPENAI_TEMPERATURE', $request->input('openai_temperature'));
            $this->updateEnvironmentVariable('CHATBOT_ENABLED', $request->input('chatbot_enabled') ? 'true' : 'false');

            // Update config cache
            Cache::forget('config');
            
            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully',
            ]);
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
     */
    protected function updateEnvironmentVariable($key, $value)
    {
        $path = base_path('.env');
        
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            if (strpos($content, $key . '=') !== false) {
                $content = preg_replace(
                    '/^' . $key . '=.*/m',
                    $key . '=' . $value,
                    $content
                );
            } else {
                $content .= "\n" . $key . '=' . $value;
            }
            
            file_put_contents($path, $content);
        }
    }
} 