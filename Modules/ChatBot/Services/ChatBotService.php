<?php

namespace Modules\ChatBot\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Modules\ChatBot\Models\Conversation;
use Modules\ChatBot\Models\Message;
use Modules\ChatBot\Exceptions\ChatBotException;

class ChatBotService
{
    protected $geminiService;
    protected $conversationService;

    public function __construct(GeminiService $geminiService, ConversationService $conversationService)
    {
        $this->geminiService = $geminiService;
        $this->conversationService = $conversationService;
    }

    /**
     * Process a user message and generate a response
     */
    public function processMessage(string $userMessage, $userId = null, array $context = [])
    {
        try {
            // Rate limiting check
            $this->checkRateLimit($userId);

            // Get or create conversation
            $conversation = $this->conversationService->getOrCreateConversation($userId);

            // Add user message to conversation
            $userMessageModel = $this->conversationService->addMessage($conversation, $userMessage, 'user');

            // Generate AI response
            $aiResponse = $this->generateAIResponse($userMessage, $conversation, $context);

            // Add AI response to conversation
            $aiMessageModel = $this->conversationService->addMessage($conversation, $aiResponse, 'assistant');

            // Update conversation
            $conversation->touch();

            return [
                'conversation_id' => $conversation->id,
                'user_message' => $userMessage,
                'ai_response' => $aiResponse,
                'message_id' => $aiMessageModel->id,
                'timestamp' => now(),
            ];
        } catch (\Exception $e) {
            Log::error('ChatBot Error: ' . $e->getMessage());
            throw new ChatBotException('Failed to process message: ' . $e->getMessage());
        }
    }

    /**
     * Generate AI response using configured provider
     */
    protected function generateAIResponse(string $userMessage, Conversation $conversation, array $context = [])
    {
        // Add conversation history to context
        $history = $this->buildConversationHistory($conversation);
        $context['history'] = $history;

        try {
            $response = $this->geminiService->generateResponse($userMessage, $context);
            return $response['message'] ?? 'I apologize, but I encountered an error processing your request.';
        } catch (\Exception $e) {
            Log::error("Gemini Response Error: " . $e->getMessage());
            return 'I apologize, but I\'m currently experiencing technical difficulties. Please try again later.';
        }
    }

    /**
     * Build conversation history for context
     */
    protected function buildConversationHistory(Conversation $conversation)
    {
        $messages = [];
        $recentMessages = $conversation->messages()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->reverse();

        foreach ($recentMessages as $message) {
            $messages[] = [
                'role' => $message->role,
                'content' => $message->content
            ];
        }

        return $messages;
    }

    /**
     * Build context information
     */
    protected function buildContext(array $context = [])
    {
        $contextData = [];

        if (Auth::check()) {
            $user = Auth::user();
            $contextData[] = "User: " . $user->name;
            $contextData[] = "Role: " . ($user->role ?? 'Student');
        }

        if (!empty($context['school_info'])) {
            $contextData[] = "School: " . $context['school_info'];
        }

        if (!empty($context['current_module'])) {
            $contextData[] = "Current Module: " . $context['current_module'];
        }

        return !empty($contextData) ? implode("\n", $contextData) : '';
    }

    /**
     * Check rate limiting
     */
    protected function checkRateLimit($userId = null)
    {
        $key = 'chatbot_rate_limit_' . ($userId ?? 'guest');
        $requests = Cache::get($key, 0);

        $maxRequests = config('chatbot.chatbot.rate_limit.requests_per_minute', 60);

        if ($requests >= $maxRequests) {
            throw new ChatBotException('Rate limit exceeded. Please wait before sending another message.');
        }

        Cache::put($key, $requests + 1, 60);
    }

    /**
     * Get conversation history
     */
    public function getConversationHistory($conversationId, $limit = 50)
    {
        $conversation = Conversation::findOrFail($conversationId);
        
        return $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Clear conversation history
     */
    public function clearConversation($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        $conversation->messages()->delete();
        $conversation->delete();

        return true;
    }

    /**
     * Get chatbot statistics
     */
    public function getStatistics()
    {
        $totalConversations = Conversation::count();
        $totalMessages = Message::count();
        $todayConversations = Conversation::whereDate('created_at', today())->count();
        $todayMessages = Message::whereDate('created_at', today())->count();

        return [
            'total_conversations' => $totalConversations,
            'total_messages' => $totalMessages,
            'today_conversations' => $todayConversations,
            'today_messages' => $todayMessages,
            'ai_provider' => config('chatbot.chatbot.provider', 'gemini'),
            'ai_available' => $this->geminiService->isAvailable(),
        ];
    }

    /**
     * Test chatbot functionality
     */
    public function testChatBot()
    {
        try {
            $testMessage = "Hello, this is a test message.";
            $response = $this->processMessage($testMessage, null, ['test' => true]);

            return [
                'success' => true,
                'response' => $response['ai_response'],
                'ai_provider' => config('chatbot.chatbot.provider', 'gemini'),
                'ai_available' => $this->geminiService->isAvailable(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'ai_provider' => config('chatbot.chatbot.provider', 'gemini'),
                'ai_available' => false,
            ];
        }
    }

    /**
     * Get welcome message
     */
    public function getWelcomeMessage()
    {
        return config('chatbot.chatbot.welcome_message', 'Hello! I\'m your AI assistant. How can I help you today?');
    }
} 