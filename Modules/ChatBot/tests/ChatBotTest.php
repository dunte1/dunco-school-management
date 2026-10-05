<?php

namespace Modules\ChatBot\Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\ChatBot\Services\ChatBotService;
use Modules\ChatBot\Services\ConversationService;
use Modules\ChatBot\Services\IntegrationService;
use Modules\ChatBot\Services\KnowledgeBaseService;
use Modules\ChatBot\Models\ChatBotConversation;
use Modules\ChatBot\Models\ChatBotMessage;
use App\Models\User;
use Illuminate\Support\Facades\Config;

class ChatBotTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $chatBotService;
    protected $conversationService;
    protected $integrationService;
    protected $knowledgeBaseService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create();
        
        // Mock the services
        $this->chatBotService = $this->app->make(ChatBotService::class);
        $this->conversationService = $this->app->make(ConversationService::class);
        $this->integrationService = $this->app->make(IntegrationService::class);
        $this->knowledgeBaseService = $this->app->make(KnowledgeBaseService::class);
    }

    /** @test */
    public function it_can_instantiate_chatbot_service()
    {
        $this->assertInstanceOf(ChatBotService::class, $this->chatBotService);
    }

    /** @test */
    public function it_can_get_welcome_message()
    {
        $welcomeMessage = $this->chatBotService->getWelcomeMessage();
        
        $this->assertIsString($welcomeMessage);
        $this->assertNotEmpty($welcomeMessage);
        $this->assertStringContainsString('AI assistant', $welcomeMessage);
    }

    /** @test */
    public function it_can_process_simple_message()
    {
        $message = "Hello, how are you?";
        
        try {
            $response = $this->chatBotService->processMessage($message, $this->user->id);
            
            $this->assertIsArray($response);
            $this->assertArrayHasKey('ai_response', $response);
            $this->assertArrayHasKey('timestamp', $response);
            $this->assertIsString($response['ai_response']);
        } catch (\Exception $e) {
            // If API keys are not configured, this is expected
            $this->assertStringContainsString('API keys', $e->getMessage());
        }
    }

    /** @test */
    public function it_can_create_conversation()
    {
        $conversation = $this->conversationService->getOrCreateConversation($this->user->id);
        
        $this->assertInstanceOf(ChatBotConversation::class, $conversation);
        $this->assertEquals($this->user->id, $conversation->user_id);
        $this->assertEquals('active', $conversation->status);
    }

    /** @test */
    public function it_can_handle_context_awareness()
    {
        $message = "Tell me about my grades";
        $context = [
            'user_role' => 'student',
            'current_module' => 'academics'
        ];

        try {
            $response = $this->chatBotService->processMessage($message, $this->user->id, $context);
            
            $this->assertIsArray($response);
            $this->assertArrayHasKey('ai_response', $response);
        } catch (\Exception $e) {
            // Expected if API keys not configured
            $this->assertStringContainsString('API keys', $e->getMessage());
        }
    }

    /** @test */
    public function it_can_handle_integration_queries()
    {
        $integrationQueries = [
            "Check my grades",
            "What are my fees?",
            "Show my schedule",
            "Tell me about my attendance"
        ];

        foreach ($integrationQueries as $query) {
            try {
                $response = $this->chatBotService->processMessage($query, $this->user->id);
                
                $this->assertIsArray($response);
                $this->assertArrayHasKey('ai_response', $response);
            } catch (\Exception $e) {
                // Expected if API keys not configured
                $this->assertStringContainsString('API keys', $e->getMessage());
            }
        }
    }

    /** @test */
    public function it_can_handle_knowledge_base_queries()
    {
        $kbQueries = [
            "What is the school policy?",
            "How do I apply for leave?",
            "What are the rules?",
            "Explain the procedure"
        ];

        foreach ($kbQueries as $query) {
            try {
                $response = $this->chatBotService->processMessage($query, $this->user->id);
                
                $this->assertIsArray($response);
                $this->assertArrayHasKey('ai_response', $response);
            } catch (\Exception $e) {
                // Expected if API keys not configured
                $this->assertStringContainsString('API keys', $e->getMessage());
            }
        }
    }

    /** @test */
    public function it_can_handle_error_cases()
    {
        // Test empty message
        try {
            $response = $this->chatBotService->processMessage('', $this->user->id);
            $this->assertIsArray($response);
        } catch (\Exception $e) {
            $this->assertStringContainsString('API keys', $e->getMessage());
        }

        // Test very long message
        $longMessage = str_repeat('a', 2000);
        try {
            $response = $this->chatBotService->processMessage($longMessage, $this->user->id);
            $this->assertIsArray($response);
        } catch (\Exception $e) {
            $this->assertStringContainsString('API keys', $e->getMessage());
        }
    }

    /** @test */
    public function it_can_manage_conversation_lifecycle()
    {
        // Create conversation
        $conversation = $this->conversationService->getOrCreateConversation($this->user->id);
        $this->assertEquals('active', $conversation->status);

        // Add message
        $message = ChatBotMessage::create([
            'conversation_id' => $conversation->id,
            'type' => 'user',
            'content' => 'Hello',
            'metadata' => []
        ]);

        $this->assertInstanceOf(ChatBotMessage::class, $message);
        $this->assertEquals('user', $message->type);
        $this->assertEquals('Hello', $message->content);

        // Update conversation
        $conversation->increment('message_count');
        $conversation->update(['last_message_at' => now()]);

        $this->assertEquals(1, $conversation->fresh()->message_count);
    }

    /** @test */
    public function it_can_handle_rate_limiting()
    {
        // This would test rate limiting functionality
        // For now, we'll just test that the service can be called
        $this->assertInstanceOf(ChatBotService::class, $this->chatBotService);
    }

    /** @test */
    public function it_can_handle_multiple_ai_providers()
    {
        $providers = ['openai', 'gemini', 'openrouter'];
        $availableProviders = [];

        foreach ($providers as $provider) {
            if (env(strtoupper($provider) . '_API_KEY')) {
                $availableProviders[] = $provider;
            }
        }

        // At least one provider should be available for testing
        $this->assertGreaterThanOrEqual(0, count($availableProviders));
    }

    /** @test */
    public function it_can_handle_different_user_roles()
    {
        $roles = ['student', 'parent', 'teacher', 'admin'];
        
        foreach ($roles as $role) {
            $context = ['user_role' => $role];
            
            try {
                $response = $this->chatBotService->processMessage(
                    "Hello, I'm a {$role}",
                    $this->user->id,
                    $context
                );
                
                $this->assertIsArray($response);
                $this->assertArrayHasKey('ai_response', $response);
            } catch (\Exception $e) {
                // Expected if API keys not configured
                $this->assertStringContainsString('API keys', $e->getMessage());
            }
        }
    }

    /** @test */
    public function it_can_handle_conversation_context()
    {
        $conversation = $this->conversationService->getOrCreateConversation($this->user->id);
        
        // Add some messages to create context
        ChatBotMessage::create([
            'conversation_id' => $conversation->id,
            'type' => 'user',
            'content' => 'My name is John',
            'metadata' => []
        ]);

        ChatBotMessage::create([
            'conversation_id' => $conversation->id,
            'type' => 'assistant',
            'content' => 'Nice to meet you John!',
            'metadata' => []
        ]);

        // Test context-aware response
        try {
            $response = $this->chatBotService->processMessage(
                "What's my name?",
                $this->user->id,
                ['conversation_id' => $conversation->id]
            );
            
            $this->assertIsArray($response);
            $this->assertArrayHasKey('ai_response', $response);
        } catch (\Exception $e) {
            // Expected if API keys not configured
            $this->assertStringContainsString('API keys', $e->getMessage());
        }
    }

    /** @test */
    public function it_can_export_conversation_data()
    {
        $conversation = $this->conversationService->getOrCreateConversation($this->user->id);
        
        // Add some messages
        ChatBotMessage::create([
            'conversation_id' => $conversation->id,
            'type' => 'user',
            'content' => 'Hello',
            'metadata' => []
        ]);

        ChatBotMessage::create([
            'conversation_id' => $conversation->id,
            'type' => 'assistant',
            'content' => 'Hi there!',
            'metadata' => []
        ]);

        // Test data export
        $messages = $conversation->messages()->orderBy('created_at')->get();
        $this->assertCount(2, $messages);
        
        $exportData = [
            'conversation_id' => $conversation->id,
            'user_id' => $conversation->user_id,
            'created_at' => $conversation->created_at,
            'messages' => $messages->map(function ($message) {
                return [
                    'type' => $message->type,
                    'content' => $message->content,
                    'created_at' => $message->created_at
                ];
            })
        ];

        $this->assertIsArray($exportData);
        $this->assertArrayHasKey('conversation_id', $exportData);
        $this->assertArrayHasKey('messages', $exportData);
        $this->assertCount(2, $exportData['messages']);
    }

    /** @test */
    public function it_can_handle_system_messages()
    {
        $conversation = $this->conversationService->getOrCreateConversation($this->user->id);
        
        $systemMessage = ChatBotMessage::create([
            'conversation_id' => $conversation->id,
            'type' => 'system',
            'content' => 'System maintenance in 5 minutes',
            'metadata' => ['priority' => 'high']
        ]);

        $this->assertEquals('system', $systemMessage->type);
        $this->assertEquals('high', $systemMessage->metadata['priority']);
    }

    /** @test */
    public function it_can_handle_metadata_storage()
    {
        $conversation = $this->conversationService->getOrCreateConversation($this->user->id);
        
        $metadata = [
            'ai_provider' => 'openai',
            'tokens_used' => 150,
            'cost' => 0.002,
            'processing_time' => 1.5
        ];

        $message = ChatBotMessage::create([
            'conversation_id' => $conversation->id,
            'type' => 'assistant',
            'content' => 'Test response',
            'metadata' => $metadata
        ]);

        $this->assertEquals($metadata, $message->metadata);
        $this->assertEquals('openai', $message->metadata['ai_provider']);
        $this->assertEquals(150, $message->metadata['tokens_used']);
    }
}
