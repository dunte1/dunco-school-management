<?php

namespace Modules\ChatBot\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenRouterService
{
    private $apiKey;
    private $model;

    public function __construct()
    {
        $this->apiKey = env('OPENROUTER_API_KEY');
        $this->model = env('OPENROUTER_MODEL', 'x-ai/grok-beta');
    }

    /**
     * Generate a response using OpenRouter API
     */
    public function generateResponse($message)
    {
        if (!$this->apiKey) {
            throw new \Exception('OpenRouter API key not configured');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful AI assistant for a school management system. Help users with academic, administrative, and general school-related questions.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'I apologize, but I could not generate a response.';
            } else {
                throw new \Exception('OpenRouter API request failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('OpenRouter API Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Generate response with context
     */
    public function generateResponseWithContext($message, $context = [])
    {
        if (!$this->apiKey) {
            throw new \Exception('OpenRouter API key not configured');
        }

        try {
            $systemMessage = 'You are a helpful AI assistant for a school management system. Help users with academic, administrative, and general school-related questions.';
            
            // Add context to system message
            if (!empty($context)) {
                $contextString = $this->formatContext($context);
                $systemMessage .= "\n\nContext: " . $contextString;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemMessage
                    ],
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ],
                'max_tokens' => 1000,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'I apologize, but I could not generate a response.';
            } else {
                throw new \Exception('OpenRouter API request failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('OpenRouter API Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Format context for AI
     */
    private function formatContext($context)
    {
        $formatted = [];
        
        if (isset($context['user_role'])) {
            $formatted[] = "User role: " . $context['user_role'];
        }
        
        if (isset($context['current_module'])) {
            $formatted[] = "Current module: " . $context['current_module'];
        }
        
        if (isset($context['integration_data'])) {
            $formatted[] = "Student data: " . json_encode($context['integration_data']);
        }
        
        if (isset($context['knowledge_base_results'])) {
            $formatted[] = "Knowledge base: " . json_encode($context['knowledge_base_results']);
        }
        
        return implode(', ', $formatted);
    }
}