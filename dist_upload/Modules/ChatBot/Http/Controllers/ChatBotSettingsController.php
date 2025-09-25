<?php

namespace Modules\ChatBot\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\ChatBot\Services\ChatBotService;
use Modules\ChatBot\Services\OpenAIService;
use Illuminate\Support\Facades\Cache;

class ChatBotSettingsController extends Controller
{
    protected $chatBotService;
    protected $openAIService;

    public function __construct(ChatBotService $chatBotService, OpenAIService $openAIService)
    {
        $this->chatBotService = $chatBotService;
        $this->openAIService = $openAIService;
    }

    /**
     * Display chatbot settings page
     */
    public function index()
    {
        $aiStatus = $this->openAIService->getStatus();
        $config = config('chatbot');
        
        return view('chatbot::settings.index', compact('aiStatus', 'config'));
    }

    /**
     * Update OpenAI configuration
     */
    public function updateOpenAI(Request $request): JsonResponse
    {
        $request->validate([
            'api_key' => 'required|string|min:20',
            'model' => 'nullable|string|in:gpt-3.5-turbo,gpt-4,gpt-4-turbo',
            'max_tokens' => 'nullable|integer|min:100|max:4000',
            'temperature' => 'nullable|numeric|min:0|max:2'
        ]);

        try {
            // Update environment variables (you might want to use a proper settings system)
            $this->updateEnvironmentVariable('OPENAI_API_KEY', $request->api_key);
            
            if ($request->has('model')) {
                $this->updateEnvironmentVariable('OPENAI_MODEL', $request->model);
            }
            
            if ($request->has('max_tokens')) {
                $this->updateEnvironmentVariable('OPENAI_MAX_TOKENS', $request->max_tokens);
            }
            
            if ($request->has('temperature')) {
                $this->updateEnvironmentVariable('OPENAI_TEMPERATURE', $request->temperature);
            }

            // Clear config cache
            Cache::forget('config');
            
            // Test the new configuration
            $status = $this->openAIService->getStatus();

            return response()->json([
                'success' => true,
                'message' => 'OpenAI configuration updated successfully.',
                'data' => [
                    'ai_status' => $status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update OpenAI configuration.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update chatbot features configuration
     */
    public function updateFeatures(Request $request): JsonResponse
    {
        $request->validate([
            'voice_to_text' => 'boolean',
            'multi_language' => 'boolean',
            'analytics' => 'boolean',
            'premium_features' => 'boolean',
            'theme' => 'string|in:light,dark,auto',
            'animations' => 'boolean',
            'sound_enabled' => 'boolean'
        ]);

        try {
            // Update feature settings
            $this->updateEnvironmentVariable('CHATBOT_VOICE_TO_TEXT', $request->boolean('voice_to_text', true));
            $this->updateEnvironmentVariable('CHATBOT_MULTI_LANGUAGE', $request->boolean('multi_language', true));
            $this->updateEnvironmentVariable('CHATBOT_ANALYTICS', $request->boolean('analytics', true));
            $this->updateEnvironmentVariable('CHATBOT_PREMIUM_FEATURES', $request->boolean('premium_features', true));
            $this->updateEnvironmentVariable('CHATBOT_THEME', $request->get('theme', 'light'));
            $this->updateEnvironmentVariable('CHATBOT_ANIMATIONS', $request->boolean('animations', true));
            $this->updateEnvironmentVariable('CHATBOT_SOUND_ENABLED', $request->boolean('sound_enabled', true));

            // Clear config cache
            Cache::forget('config');

            return response()->json([
                'success' => true,
                'message' => 'ChatBot features updated successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update ChatBot features.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Test OpenAI connection
     */
    public function testOpenAI(): JsonResponse
    {
        try {
            $status = $this->openAIService->getStatus();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'ai_status' => $status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to test OpenAI connection.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current configuration
     */
    public function getConfig(): JsonResponse
    {
        try {
            $config = config('chatbot');
            $aiStatus = $this->openAIService->getStatus();

            return response()->json([
                'success' => true,
                'data' => [
                    'config' => $config,
                    'ai_status' => $aiStatus
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get configuration.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update environment variable in .env file
     */
    protected function updateEnvironmentVariable(string $key, $value): void
    {
        $envFile = base_path('.env');
        
        if (!file_exists($envFile)) {
            throw new \Exception('.env file not found');
        }

        $envContent = file_get_contents($envFile);
        
        // Escape the value if it contains special characters
        $escapedValue = is_string($value) ? '"' . addslashes($value) . '"' : $value;
        
        // Check if the key already exists
        if (preg_match("/^{$key}=/m", $envContent)) {
            // Update existing key
            $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$escapedValue}", $envContent);
        } else {
            // Add new key
            $envContent .= "\n{$key}={$escapedValue}";
        }
        
        file_put_contents($envFile, $envContent);
    }
} 