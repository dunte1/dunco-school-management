<?php

namespace Modules\ChatBot\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class OpenAIService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openai.com/v1';
    protected $model = 'gpt-3.5-turbo';
    protected $maxTokens = 1000;
    protected $temperature = 0.7;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    /**
     * Generate AI response using OpenAI API
     */
    public function generateResponse(string $message, array $context = []): array
    {
        if (!$this->apiKey) {
            return $this->getFallbackResponse($message);
        }

        try {
            $messages = $this->buildMessages($message, $context);
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false, // Disable SSL verification for development
                'timeout' => 30,
            ])->post($this->baseUrl . '/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => $this->maxTokens,
                'temperature' => $this->temperature,
                'stream' => false
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $data['choices'][0]['message']['content'] ?? '';
                
                return [
                    'message' => $this->cleanResponse($aiResponse),
                    'intent' => $this->detectIntent($message),
                    'confidence' => 0.9,
                    'is_premium' => true,
                    'tokens_used' => $data['usage']['total_tokens'] ?? 0
                ];
            }

            Log::error('OpenAI API error', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return $this->getFallbackResponse($message);

        } catch (\Exception $e) {
            Log::error('OpenAI service error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->getFallbackResponse($message);
        }
    }

    /**
     * Build conversation messages for OpenAI
     */
    protected function buildMessages(string $userMessage, array $context = []): array
    {
        $messages = [
            [
                'role' => 'system',
                'content' => $this->getSystemPrompt($context)
            ]
        ];

        // Add conversation history if available
        if (isset($context['history']) && is_array($context['history'])) {
            foreach ($context['history'] as $msg) {
                $messages[] = [
                    'role' => $msg['sender_type'] === 'user' ? 'user' : 'assistant',
                    'content' => $msg['message']
                ];
            }
        }

        // Add current user message
        $messages[] = [
            'role' => 'user',
            'content' => $userMessage
        ];

        return $messages;
    }

    /**
     * Get system prompt for the AI
     */
    protected function getSystemPrompt(array $context = []): string
    {
        $userType = $context['user_type'] ?? 'student';
        $language = $context['language'] ?? 'en';

        $prompt = "You are an AI School Assistant for Dunco School Management System. ";
        $prompt .= "You help {$userType}s with academic, financial, and administrative questions. ";
        $prompt .= "Always be helpful, professional, and concise. ";
        $prompt .= "If you don't know something specific about the school, suggest contacting the appropriate department. ";
        $prompt .= "Keep responses under 200 words unless detailed explanation is needed. ";
        $prompt .= "Always respond in {$language} language. ";

        // Add specific context based on user type
        switch ($userType) {
            case 'student':
                $prompt .= "Focus on academic queries, grades, schedules, homework, and student services.";
                break;
            case 'parent':
                $prompt .= "Focus on child's progress, fees, attendance, and parent-related concerns.";
                break;
            case 'teacher':
                $prompt .= "Focus on teaching resources, student management, and administrative tasks.";
                break;
            case 'admin':
                $prompt .= "Focus on system management, reports, and administrative functions.";
                break;
        }

        return $prompt;
    }

    /**
     * Clean and format AI response
     */
    protected function cleanResponse(string $response): string
    {
        $response = trim($response);
        
        // Remove any markdown formatting if present
        $response = preg_replace('/\*\*(.*?)\*\*/', '$1', $response);
        $response = preg_replace('/\*(.*?)\*/', '$1', $response);
        
        // Ensure proper punctuation
        if (!preg_match('/[.!?]$/', $response)) {
            $response .= '.';
        }

        return $response;
    }

    /**
     * Detect intent from user message
     */
    protected function detectIntent(string $message): string
    {
        $message = strtolower($message);
        
        if (preg_match('/(grade|score|mark|result)/', $message)) {
            return 'academic_grades';
        }
        
        if (preg_match('/(schedule|timetable|class|lesson)/', $message)) {
            return 'academic_schedule';
        }
        
        if (preg_match('/(fee|payment|money|cost|bill)/', $message)) {
            return 'financial_fees';
        }
        
        if (preg_match('/(attendance|present|absent)/', $message)) {
            return 'administrative_attendance';
        }
        
        if (preg_match('/(help|support|assist)/', $message)) {
            return 'general_help';
        }
        
        return 'general_query';
    }

    /**
     * Get fallback response when OpenAI is not available
     */
    protected function getFallbackResponse(string $message): array
    {
        $message = strtolower(trim($message));
        
        // Mathematical calculations
        if (preg_match('/(\d+)\s*[\+\-\*\/]\s*(\d+)/', $message, $matches)) {
            $num1 = (int)$matches[1];
            $num2 = (int)$matches[2];
            $operator = $matches[0][strpos($matches[0], $matches[1]) + strlen($matches[1])];
            
            $result = match($operator) {
                '+' => $num1 + $num2,
                '-' => $num1 - $num2,
                '*' => $num1 * $num2,
                '/' => $num2 != 0 ? $num1 / $num2 : 'undefined (division by zero)',
                default => 'invalid operation'
            };
            
            return [
                'message' => "The result of $num1 $operator $num2 = $result",
                'intent' => 'calculation',
                'confidence' => 0.9,
                'is_premium' => false
            ];
        }
        
        // User count question
        if (preg_match('/(how many|count|total).*user/', $message)) {
            return [
                'message' => "I can help you check the user count! Please visit the admin dashboard or contact your system administrator to view detailed user statistics. You can also check the Users section in the Core module.",
                'intent' => 'user_inquiry',
                'confidence' => 0.8,
                'is_premium' => false
            ];
        }
        
        // Greetings
        if (preg_match('/(hello|hi|hey|good morning|good afternoon|good evening)/', $message)) {
            $greetings = [
                'Hello! How can I help you today?',
                'Hi there! I\'m here to assist you with school-related questions.',
                'Hello! What would you like to know about your school management system?',
                'Hi! I can help with academic, financial, and administrative questions.'
            ];
            
            return [
                'message' => $greetings[array_rand($greetings)],
                'intent' => 'greeting',
                'confidence' => 0.9,
                'is_premium' => false
            ];
        }
        
        // Help requests
        if (preg_match('/(help|support|assist|what can you do)/', $message)) {
            return [
                'message' => "I can help you with:\n\n📚 **Academic**: Grades, schedules, homework, exams\n💰 **Financial**: Fees, payments, scholarships\n📋 **Administrative**: Attendance, transport, events\n🎓 **General**: School policies, contact info\n\nWhat would you like to know about?",
                'intent' => 'help_request',
                'confidence' => 0.9,
                'is_premium' => false
            ];
        }
        
        // Grade inquiries
        if (preg_match('/(grade|score|mark|result|performance)/', $message)) {
            return [
                'message' => "To check your grades:\n\n1. Visit the Academic module\n2. Go to 'My Grades' section\n3. Select the term/semester\n4. View detailed results\n\nYou can also contact your teacher for specific grade inquiries.",
                'intent' => 'academic_grades',
                'confidence' => 0.8,
                'is_premium' => false
            ];
        }
        
        // Schedule inquiries
        if (preg_match('/(schedule|timetable|class|lesson|when is)/', $message)) {
            return [
                'message' => "To view your schedule:\n\n1. Go to the Academic module\n2. Click on 'My Schedule'\n3. Select the day/week view\n4. Check for any updates\n\nYou can also sync your schedule with your calendar!",
                'intent' => 'academic_schedule',
                'confidence' => 0.8,
                'is_premium' => false
            ];
        }
        
        // Fee inquiries
        if (preg_match('/(fee|payment|money|cost|bill|balance)/', $message)) {
            return [
                'message' => "For fee-related queries:\n\n1. Visit the Finance module\n2. Check 'My Fees' section\n3. View payment history\n4. See upcoming payments\n\nContact the accounts department for detailed information.",
                'intent' => 'financial_fees',
                'confidence' => 0.8,
                'is_premium' => false
            ];
        }
        
        // Attendance inquiries
        if (preg_match('/(attendance|present|absent|attendance record)/', $message)) {
            return [
                'message' => "To check your attendance:\n\n1. Go to the Attendance module\n2. View 'My Attendance' report\n3. Check monthly/yearly statistics\n4. Contact your class teacher for any discrepancies",
                'intent' => 'administrative_attendance',
                'confidence' => 0.8,
                'is_premium' => false
            ];
        }
        
        // Time and date
        if (preg_match('/(what time|current time|today|date)/', $message)) {
            $currentTime = now()->format('l, F j, Y \a\t g:i A');
                return [
                'message' => "The current time is: $currentTime",
                'intent' => 'time_inquiry',
                'confidence' => 0.9,
                    'is_premium' => false
                ];
            }
        
        // Weather (basic response)
        if (preg_match('/(weather|temperature|hot|cold)/', $message)) {
            return [
                'message' => "I can't provide real-time weather information, but you can check your local weather app or visit a weather website for current conditions.",
                'intent' => 'weather_inquiry',
                'confidence' => 0.7,
                'is_premium' => false
            ];
        }
        
        // Health and medical queries
        if (preg_match('/(headache|pain|sick|ill|health|medical|doctor|medicine)/', $message)) {
            return [
                'message' => "I'm not a medical professional, but I can help you with:\n\n• School health policies and procedures\n• Contact information for the school nurse\n• Health-related absences and documentation\n• First aid information\n\nFor medical advice, please consult a healthcare professional or visit the school nurse.",
                'intent' => 'health_inquiry',
                'confidence' => 0.8,
                'is_premium' => false
            ];
        }
        
        // Academic subject queries
        if (preg_match('/(anatomy|biology|chemistry|physics|math|history|geography|literature|english|science)/', $message)) {
            return [
                'message' => "I can help you with academic subjects! Here's what I can assist with:\n\n• Finding study materials and resources\n• Explaining concepts and topics\n• Providing study tips and strategies\n• Connecting you with subject teachers\n• Accessing library resources\n\nWhat specific topic would you like help with?",
                'intent' => 'academic_subject',
                'confidence' => 0.8,
                'is_premium' => false
            ];
        }
        
        // General knowledge questions
        if (preg_match('/(what is|who is|when is|where is|how to|explain|define|meaning of)/', $message)) {
            return [
                'message' => "I can help you with general knowledge questions! I can:\n\n• Explain concepts and definitions\n• Provide information on various topics\n• Help with research and study questions\n• Connect you with relevant resources\n• Assist with homework and assignments\n\nPlease ask your specific question and I'll do my best to help!",
                'intent' => 'general_knowledge',
                'confidence' => 0.7,
                'is_premium' => false
            ];
        }
        
        // Default response for unrecognized queries
        return [
            'message' => "I'm here to help with school management questions! You can ask me about:\n\n• Grades and academic performance\n• Class schedules and timetables\n• Fees and payments\n• Attendance records\n• School events and policies\n• Academic subjects and study help\n• General knowledge questions\n• Health and wellness information\n\nFor more complex questions, please visit the specific modules or contact your teachers/administrators.",
            'intent' => 'general_query',
            'confidence' => 0.5,
            'is_premium' => false
        ];
    }

    /**
     * Check if OpenAI is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Check if OpenAI is available (configured and connected)
     */
    public function isAvailable(): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->withOptions([
                'verify' => false, // Disable SSL verification for development
                'timeout' => 10,
            ])->get($this->baseUrl . '/models');

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Test OpenAI connection
     */
    public function testConnection(): bool
    {
        return $this->isAvailable();
    }

    /**
     * Get available models
     */
    public function getModels(): array
    {
        if (!$this->isConfigured()) {
            return [];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->withOptions([
                'verify' => false, // Disable SSL verification for development
                'timeout' => 10,
            ])->get($this->baseUrl . '/models');

            if ($response->successful()) {
                $data = $response->json();
                return $data['data'] ?? [];
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get usage statistics
     */
    public function getUsage(string $date = null): array
    {
        if (!$this->isConfigured()) {
            return [];
        }

        $date = $date ?? now()->format('Y-m-d');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->withOptions([
                'verify' => false, // Disable SSL verification for development
                'timeout' => 10,
            ])->get($this->baseUrl . '/usage', [
                'date' => $date
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get API status
     */
    public function getStatus(): array
    {
        if (!$this->isConfigured()) {
            return [
                'configured' => false,
                'message' => 'OpenAI API key not configured'
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->withOptions([
                'verify' => false, // Disable SSL verification for development
                'timeout' => 10,
            ])->get($this->baseUrl . '/models');

            return [
                'configured' => true,
                'status' => $response->successful() ? 'connected' : 'error',
                'message' => $response->successful() ? 'API connected' : 'API connection failed'
            ];
        } catch (\Exception $e) {
            return [
                'configured' => true,
                'status' => 'error',
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }
} 