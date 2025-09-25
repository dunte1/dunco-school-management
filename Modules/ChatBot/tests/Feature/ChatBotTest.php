<?php

namespace Modules\ChatBot\tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Modules\ChatBot\app\Models\ChatBotConversation;
use Modules\ChatBot\app\Models\ChatBotMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatBotTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_chatbot_index_page_loads()
    {
        $response = $this->actingAs($this->user)
            ->get('/chatbot');

        $response->assertStatus(200);
        $response->assertViewIs('chatbot::index');
    }

    public function test_can_send_message_to_chatbot()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/chatbot/api/send-message', [
                'message' => 'Hello',
                'session_id' => 'test-session-123',
                'is_voice' => false
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'message',
                'intent',
                'confidence',
                'is_premium',
                'session_id',
                'suggestions'
            ]
        ]);

        $this->assertTrue($response->json('success'));
        $this->assertNotEmpty($response->json('data.message'));
    }

    public function test_can_get_conversation_history()
    {
        // Create a conversation and messages
        $conversation = ChatBotConversation::create([
            'user_id' => $this->user->id,
            'session_id' => 'test-session-123',
            'user_type' => 'student',
            'language' => 'en',
            'status' => 'active'
        ]);

        ChatBotMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'user',
            'message' => 'Hello',
            'intent' => 'greeting'
        ]);

        ChatBotMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'bot',
            'message' => 'Hi there! How can I help you?',
            'intent' => 'greeting'
        ]);

        $response = $this->actingAs($this->user)
            ->getJson('/chatbot/api/history?session_id=test-session-123');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'messages' => [
                    '*' => [
                        'id',
                        'message',
                        'sender_type',
                        'timestamp',
                        'is_voice'
                    ]
                ]
            ]
        ]);

        $this->assertTrue($response->json('success'));
        $this->assertCount(2, $response->json('data.messages'));
    }

    public function test_can_get_suggestions()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/chatbot/api/suggestions?category=academic');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'suggestions',
                'category'
            ]
        ]);

        $this->assertTrue($response->json('success'));
        $this->assertNotEmpty($response->json('data.suggestions'));
    }

    public function test_can_provide_feedback()
    {
        // Create a conversation and message
        $conversation = ChatBotConversation::create([
            'user_id' => $this->user->id,
            'session_id' => 'test-session-123',
            'user_type' => 'student',
            'language' => 'en',
            'status' => 'active'
        ]);

        $message = ChatBotMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'bot',
            'message' => 'Test response',
            'intent' => 'test',
            'metadata' => ['intent' => 'test']
        ]);

        $response = $this->actingAs($this->user)
            ->postJson('/chatbot/api/feedback', [
                'conversation_id' => $conversation->id,
                'message_id' => $message->id,
                'was_helpful' => true
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message'
        ]);

        $this->assertTrue($response->json('success'));
    }

    public function test_can_change_language()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/chatbot/api/change-language', [
                'language' => 'es',
                'session_id' => 'test-session-123'
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message'
        ]);

        $this->assertTrue($response->json('success'));
    }

    public function test_validates_message_input()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/chatbot/api/send-message', [
                'message' => '', // Empty message
                'session_id' => 'test-session-123'
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);
    }

    public function test_validates_session_id()
    {
        $response = $this->actingAs($this->user)
            ->postJson('/chatbot/api/send-message', [
                'message' => 'Hello',
                // Missing session_id
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['session_id']);
    }

    public function test_requires_authentication()
    {
        $response = $this->get('/chatbot');
        $response->assertRedirect('/login');

        $response = $this->postJson('/chatbot/api/send-message', [
            'message' => 'Hello',
            'session_id' => 'test-session-123'
        ]);
        $response->assertStatus(401);
    }
} 