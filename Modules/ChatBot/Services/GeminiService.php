<?php

namespace Modules\ChatBot\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GeminiService
{
    protected $apiKey;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';
    protected $model = 'gemini-1.5-flash';
    protected $maxTokens = 1000;
    protected $temperature = 0.7;

    public function __construct()
    {
        // No hardcoded fallback: configure GEMINI_API_KEY in the environment.
        $this->apiKey = env('GEMINI_API_KEY');
    }

    /**
     * Check if the Gemini API key is configured and service is available
     */
    public function isAvailable(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Generate AI response using Gemini API
     */
    public function generateResponse(string $message, array $context = []): array
    {
        if (!$this->apiKey) {
            return $this->getFallbackResponse($message);
        }

        try {
            $messages = $this->buildMessages($message, $context);
            
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => true,
                'timeout' => 30,
            ])->post($this->baseUrl . '/' . $this->model . ':generateContent?key=' . $this->apiKey, [
                'contents' => $messages,
                'generationConfig' => [
                    'maxOutputTokens' => $this->maxTokens,
                    'temperature' => $this->temperature,
                    'topP' => 0.8,
                    'topK' => 40
                ],
                'safetySettings' => [
                    [
                        'category' => 'HARM_CATEGORY_HARASSMENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_HATE_SPEECH',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                        'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                return [
                    'message' => $this->cleanResponse($aiResponse),
                    'intent' => $this->detectIntent($message),
                    'confidence' => 0.9,
                    'is_premium' => true,
                    'provider' => 'gemini',
                    'tokens_used' => $data['usageMetadata']['totalTokenCount'] ?? 0
                ];
            }

            Log::error('Gemini API error', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return $this->getFallbackResponse($message);

        } catch (\Exception $e) {
            Log::error('Gemini service error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->getFallbackResponse($message);
        }
    }

    /**
     * Build conversation messages for Gemini
     */
    protected function buildMessages(string $userMessage, array $context = []): array
    {
        $messages = [];

        // Add system prompt as first message
        $messages[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $this->getSystemPrompt($context)]
            ]
        ];

        $messages[] = [
            'role' => 'model',
            'parts' => [
                ['text' => 'I understand. I am an AI School Assistant for Dunco School Management System. I will help with academic, financial, and administrative questions. How can I assist you today?']
            ]
        ];

        // Add conversation history if available
        if (isset($context['history']) && is_array($context['history'])) {
            foreach ($context['history'] as $msg) {
                $messages[] = [
                    'role' => $msg['sender_type'] === 'user' ? 'user' : 'model',
                    'parts' => [
                        ['text' => $msg['message']]
                    ]
                ];
            }
        }

        // Add current user message
        $messages[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $userMessage]
            ]
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

        $prompt = "You are DuncoAI, the intelligent assistant agent for the Dunco School Management System (Dunco SMS). ";
        $prompt .= "You serve two main purposes: (1) Act as an advanced support and automation agent for Dunco SMS — able to access, update, and query all school-related data such as student records, exams, finance, library, HR, and more — and (2) Function as a powerful general-purpose AI with ChatGPT-level reasoning, capable of answering any academic, technical, or general question accurately and helpfully. ";
        $prompt .= "Always be accurate, polite, and concise unless asked for a detailed explanation. ";
        $prompt .= "You are connected to Google's Gemini model through the API. ";
        $prompt .= "When responding to school management related questions, you may use relevant database functions, APIs, or system commands that exist in Dunco SMS. ";
        $prompt .= "When responding to non-school-related questions, behave exactly like ChatGPT with updated knowledge, logical reasoning, and well-structured explanations. ";
        $prompt .= "Do not make up data when it can be looked up or calculated. If it requires step-by-step solving, show reasoning clearly. ";
        $prompt .= "You help {$userType}s with academic, financial, and administrative questions. ";
        $prompt .= "Always respond in {$language} language. ";

        return $prompt;
    }

    /**
     * Clean and format AI response
     */
    protected function cleanResponse(string $response): string
    {
        $response = trim($response);
        $response = str_replace(['```', '`'], '', $response);
        return $response;
    }

    /**
     * Detect user intent from message
     */
    protected function detectIntent(string $message): string
    {
        $message = strtolower(trim($message));
        
        // Math calculations
        if (preg_match('/\d+\s*[\+\-\*\/]\s*\d+/', $message)) {
            return 'math_calculation';
        }
        
        // User count queries
        if (preg_match('/(how many|count|total).*user/', $message)) {
            return 'user_count';
        }
        
        // Greetings
        if (preg_match('/(hello|hi|hey|good morning|good afternoon|good evening)/', $message)) {
            return 'greeting';
        }
        
        // Help requests
        if (preg_match('/(help|assist|support)/', $message)) {
            return 'help_request';
        }
        
        // Grade queries
        if (preg_match('/(grade|score|mark|result)/', $message)) {
            return 'grade_inquiry';
        }
        
        // Schedule queries
        if (preg_match('/(schedule|timetable|class|lesson)/', $message)) {
            return 'schedule_inquiry';
        }
        
        // Fee queries
        if (preg_match('/(fee|payment|cost|price|bill)/', $message)) {
            return 'fee_inquiry';
        }
        
        // Attendance queries
        if (preg_match('/(attendance|present|absent|late)/', $message)) {
            return 'attendance_inquiry';
        }
        
        // Time queries
        if (preg_match('/(time|when|what time)/', $message)) {
            return 'time_inquiry';
        }
        
        // Weather queries
        if (preg_match('/(weather|temperature|forecast)/', $message)) {
            return 'weather_inquiry';
        }
        
        // Health and medical queries
        if (preg_match('/(headache|pain|sick|ill|health|medical|doctor|medicine)/', $message)) {
            return 'health_inquiry';
        }
        
        // Academic subject queries
        if (preg_match('/(anatomy|biology|chemistry|physics|math|history|geography|literature|english|science)/', $message)) {
            return 'academic_subject';
        }
        
        // General knowledge questions
        if (preg_match('/(what is|who is|when is|where is|how to|explain|define|meaning of)/', $message)) {
            return 'general_knowledge';
        }
        
        return 'general_query';
    }

    /**
     * Get fallback response when API is unavailable
     */
    protected function getFallbackResponse(string $message): array
    {
        $message = strtolower(trim($message));
        
        // Math calculations
        if (preg_match('/(\d+)\s*\+\s*(\d+)/', $message, $matches)) {
            $result = intval($matches[1]) + intval($matches[2]);
            return [
                'message' => "The result of {$matches[1]} + {$matches[2]} = {$result}",
                'intent' => 'math_calculation',
                'confidence' => 0.9,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        if (preg_match('/(\d+)\s*\-\s*(\d+)/', $message, $matches)) {
            $result = intval($matches[1]) - intval($matches[2]);
            return [
                'message' => "The result of {$matches[1]} - {$matches[2]} = {$result}",
                'intent' => 'math_calculation',
                'confidence' => 0.9,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        if (preg_match('/(\d+)\s*\*\s*(\d+)/', $message, $matches)) {
            $result = intval($matches[1]) * intval($matches[2]);
            return [
                'message' => "The result of {$matches[1]} × {$matches[2]} = {$result}",
                'intent' => 'math_calculation',
                'confidence' => 0.9,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        if (preg_match('/(\d+)\s*\/\s*(\d+)/', $message, $matches)) {
            if (intval($matches[2]) === 0) {
                return [
                    'message' => "Cannot divide by zero!",
                    'intent' => 'math_calculation',
                    'confidence' => 0.9,
                    'is_premium' => false,
                    'provider' => 'fallback'
                ];
            }
            $result = intval($matches[1]) / intval($matches[2]);
            return [
                'message' => "The result of {$matches[1]} ÷ {$matches[2]} = " . round($result, 2),
                'intent' => 'math_calculation',
                'confidence' => 0.9,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        // User count queries
        if (preg_match('/(how many|count|total).*user/', $message)) {
            return [
                'message' => "I can help you check the user count! Please visit the admin dashboard or contact your system administrator to view detailed user statistics. You can also check the Users section in the Core module.",
                'intent' => 'user_count',
                'confidence' => 0.8,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        // Greetings
        if (preg_match('/(hello|hi|hey|good morning|good afternoon|good evening)/', $message)) {
            return [
                'message' => "Hello! I'm your AI assistant. How can I help you today?",
                'intent' => 'greeting',
                'confidence' => 0.9,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        // Help requests
        if (preg_match('/(help|assist|support)/', $message)) {
            return [
                'message' => "I'm here to help! You can ask me about:\n\n• Academic subjects and study help\n• School management questions\n• Mathematical calculations\n• General knowledge questions\n• Health and wellness information\n• Document upload and analysis\n\nWhat would you like to know?",
                'intent' => 'help_request',
                'confidence' => 0.8,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        // Grade queries
        if (preg_match('/(grade|score|mark|result)/', $message)) {
            return [
                'message' => "I can help you check your grades! Please visit the Grades module or contact your teachers for detailed academic information.",
                'intent' => 'grade_inquiry',
                'confidence' => 0.8,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        // Schedule queries
        if (preg_match('/(schedule|timetable|class|lesson)/', $message)) {
            return [
                'message' => "I can help you check your class schedule! Please visit the Schedule module or contact your teachers for the latest timetable information.",
                'intent' => 'schedule_inquiry',
                'confidence' => 0.8,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        // Fee queries
        if (preg_match('/(fee|payment|cost|price|bill)/', $message)) {
            return [
                'message' => "I can help you with fee-related questions! Please visit the Finance module or contact the administration office for detailed payment information.",
                'intent' => 'fee_inquiry',
                'confidence' => 0.8,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        // Attendance queries
        if (preg_match('/(attendance|present|absent|late)/', $message)) {
            return [
                'message' => "I can help you check your attendance records! Please visit the Attendance module or contact your teachers for detailed attendance information.",
                'intent' => 'attendance_inquiry',
                'confidence' => 0.8,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        // Time queries
        if (preg_match('/(time|when|what time)/', $message)) {
            $currentTime = date('H:i:s');
            $currentDate = date('l, F j, Y');
            return [
                'message' => "The current time is {$currentTime} on {$currentDate}.",
                'intent' => 'time_inquiry',
                'confidence' => 0.9,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        // Weather queries
        if (preg_match('/(weather|temperature|forecast)/', $message)) {
            return [
                'message' => "I can't provide real-time weather information, but you can check your local weather service or weather app for current conditions and forecasts.",
                'intent' => 'weather_inquiry',
                'confidence' => 0.7,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        // Health and medical queries
        if (preg_match('/(headache|pain|sick|ill|health|medical|doctor|medicine)/', $message)) {
            return [
                'message' => "I'm not a medical professional, but I can help you with:\n\n• School health policies and procedures\n• Contact information for the school nurse\n• Health-related absences and documentation\n• First aid information\n\nFor medical advice, please consult a healthcare professional or visit the school nurse.",
                'intent' => 'health_inquiry',
                'confidence' => 0.8,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        // Academic subject queries
        if (preg_match('/(anatomy|biology|chemistry|physics|math|history|geography|literature|english|science)/', $message)) {
            return [
                'message' => "I can help you with academic subjects! Here's what I can assist with:\n\n• Finding study materials and resources\n• Explaining concepts and topics\n• Providing study tips and strategies\n• Connecting you with subject teachers\n• Accessing library resources\n\nWhat specific topic would you like help with?",
                'intent' => 'academic_subject',
                'confidence' => 0.8,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        // General knowledge questions
        if (preg_match('/(what is|who is|when is|where is|how to|explain|define|meaning of)/', $message)) {
            return [
                'message' => "I can help you with general knowledge questions! I can:\n\n• Explain concepts and definitions\n• Provide information on various topics\n• Help with research and study questions\n• Connect you with relevant resources\n• Assist with homework and assignments\n\nPlease ask your specific question and I'll do my best to help!",
                'intent' => 'general_knowledge',
                'confidence' => 0.7,
                'is_premium' => false,
                'provider' => 'fallback'
            ];
        }
        
        // Default response for unrecognized queries
        return [
            'message' => "I'm here to help with school management questions! You can ask me about:\n\n• Grades and academic performance\n• Class schedules and timetables\n• Fees and payments\n• Attendance records\n• School events and policies\n• Academic subjects and study help\n• General knowledge questions\n• Health and wellness information\n\nFor more complex questions, please visit the specific modules or contact your teachers/administrators.",
            'intent' => 'general_query',
            'confidence' => 0.5,
            'is_premium' => false,
            'provider' => 'fallback'
        ];
    }
}
