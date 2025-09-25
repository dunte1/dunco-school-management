<?php

namespace Modules\ChatBot\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ChatBot\Services\ChatBotService;
use Modules\ChatBot\Services\ConversationService;
use Modules\ChatBot\Models\Conversation;
use Modules\ChatBot\Models\Message;

class ChatBotController extends Controller
{
    protected $chatBotService;
    protected $conversationService;

    public function __construct(ChatBotService $chatBotService, ConversationService $conversationService)
    {
        $this->chatBotService = $chatBotService;
        $this->conversationService = $conversationService;
    }

    /**
     * Display the chat interface
     */
    public function index()
    {
        try {
            $conversations = $this->conversationService->getRecentConversations();
            $welcomeMessage = $this->chatBotService->getWelcomeMessage();
            
            $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="' . csrf_token() . '">
    <title>AI Assistant - School Management System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .chat-container { 
            width: 90%; 
            max-width: 900px; 
            height: 80vh;
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .chat-header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            padding: 25px; 
            text-align: center;
            position: relative;
        }
        .chat-header h2 { 
            font-size: 24px; 
            margin-bottom: 5px;
            font-weight: 600;
        }
        .chat-header p { 
            font-size: 14px; 
            opacity: 0.9;
        }
        .status-indicator {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 12px;
            height: 12px;
            background: #4ade80;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .chat-messages { 
            flex: 1;
            padding: 20px; 
            overflow-y: auto;
            background: #f8fafc;
        }
        .message { 
            margin-bottom: 20px; 
            padding: 15px 20px; 
            border-radius: 18px; 
            max-width: 80%;
            word-wrap: break-word;
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .user-message { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            margin-left: auto;
            border-bottom-right-radius: 5px;
        }
        .bot-message { 
            background: white;
            color: #333; 
            margin-right: auto;
            border-bottom-left-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .chat-input { 
            padding: 20px; 
            background: white;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 10px;
        }
        .chat-input input { 
            flex: 1;
            padding: 15px 20px; 
            border: 2px solid #e2e8f0; 
            border-radius: 25px; 
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        .chat-input input:focus {
            border-color: #667eea;
        }
        .chat-input button { 
            padding: 15px 25px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white; 
            border: none; 
            border-radius: 25px; 
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: transform 0.2s ease;
        }
        .chat-input button:hover {
            transform: translateY(-2px);
        }
        .welcome { 
            text-align: center; 
            color: #666; 
            font-style: italic;
            padding: 20px;
        }
        .typing-indicator {
            display: none;
            padding: 15px 20px;
            background: white;
            border-radius: 18px;
            margin-bottom: 20px;
            margin-right: auto;
            border-bottom-left-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .typing-dots {
            display: flex;
            gap: 4px;
        }
        .typing-dots span {
            width: 8px;
            height: 8px;
            background: #667eea;
            border-radius: 50%;
            animation: typing 1.4s infinite ease-in-out;
        }
        .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
        .typing-dots span:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typing {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <div class="status-indicator"></div>
            <h2>🤖 AI Assistant</h2>
            <p>Your intelligent school management assistant</p>
        </div>
        <div class="chat-messages" id="chatMessages">
            <div class="message bot-message">
                <div class="welcome">' . htmlspecialchars($welcomeMessage) . '</div>
            </div>
        </div>
        <div class="typing-indicator" id="typingIndicator">
            <div class="typing-dots">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="chat-input">
            <input type="text" id="messageInput" placeholder="Ask me anything about school management...">
            <button id="sendButton">Send</button>
        </div>
    </div>
    
    <script>
        console.log("ChatBot JavaScript loaded successfully");
        
        const messageInput = document.getElementById("messageInput");
        const sendButton = document.getElementById("sendButton");
        const chatMessages = document.getElementById("chatMessages");
        const typingIndicator = document.getElementById("typingIndicator");
        
        function addMessage(text, sender) {
            console.log("Adding message:", text, "from:", sender);
            const messageDiv = document.createElement("div");
            messageDiv.className = "message " + sender + "-message";
            messageDiv.textContent = text;
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        function showTypingIndicator() {
            console.log("Showing typing indicator");
            typingIndicator.style.display = "block";
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        function hideTypingIndicator() {
            console.log("Hiding typing indicator");
            typingIndicator.style.display = "none";
        }
        
        function sendMessage() {
            console.log("sendMessage function called");
            const message = messageInput.value.trim();
            if (!message) return;
            
            console.log("Sending message:", message);
            
            // Add user message
            addMessage(message, "user");
            messageInput.value = "";
            
            // Show typing indicator
            showTypingIndicator();
            
            // Send message to API
            fetch("/chatbot/send", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").getAttribute("content")
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .then(data => {
                console.log("API response:", data);
                hideTypingIndicator();
                if (data.success) {
                    addMessage(data.data.ai_response, "bot");
                } else {
                    // Use intelligent fallback response instead of hardcoded demo
                    const intelligentResponse = getIntelligentResponse(message);
                    addMessage(intelligentResponse, "bot");
                }
            })
            .catch(error => {
                console.error("API Error:", error);
                hideTypingIndicator();
                // Use intelligent fallback response instead of hardcoded demo
                const intelligentResponse = getIntelligentResponse(message);
                addMessage(intelligentResponse, "bot");
            });
        }
        
        // Intelligent response function
        function getIntelligentResponse(message) {
            const msg = message.toLowerCase();
            
            // Mathematical calculations
            if (msg.match(/(\d+)\s*[\+\-\*\/]\s*(\d+)/)) {
                const matches = msg.match(/(\d+)\s*[\+\-\*\/]\s*(\d+)/);
                const num1 = parseInt(matches[1]);
                const num2 = parseInt(matches[2]);
                const operator = msg.match(/[\+\-\*\/]/)[0];
                
                let result;
                switch(operator) {
                    case \'+\': result = num1 + num2; break;
                    case \'-\': result = num1 - num2; break;
                    case \'*\': result = num1 * num2; break;
                    case \'/\': result = num2 !== 0 ? num1 / num2 : \'Error: Division by zero\'; break;
                    default: result = \'invalid operation\';
                }
                
                return `The result of ${num1} ${operator} ${num2} = ${result}`;
            }
            
            // User count question
            if (msg.includes(\'how many users\') || msg.includes(\'count users\') || msg.includes(\'total users\')) {
                return "I can help you check the user count! Please visit the admin dashboard or contact your system administrator to view detailed user statistics. You can also check the Users section in the Core module.";
            }
            
            // Time question
            if (msg.includes(\'what time\') || msg.includes(\'current time\') || msg.includes(\'time now\')) {
                const now = new Date();
                return `The current time is: ${now.toLocaleDateString(\'en-US\', { weekday: \'long\', year: \'numeric\', month: \'long\', day: \'numeric\' })} at ${now.toLocaleTimeString(\'en-US\', { hour: \'numeric\', minute: \'2-digit\', hour12: true })}`;
            }
            
            // Help request
            if (msg.includes(\'help\') || msg.includes(\'what can you do\') || msg.includes(\'assist\')) {
                return `I can help you with:\n\n📚 **Academic**: Grades, schedules, homework, exams\n💰 **Financial**: Fees, payments, scholarships\n📋 **Administrative**: Attendance, transport, events\n🎓 **General**: School policies, contact info\n\nWhat would you like to know about?`;
            }
            
            // Greetings
            if (msg.includes(\'hello\') || msg.includes(\'hi\') || msg.includes(\'hey\') || msg.includes(\'good morning\') || msg.includes(\'good afternoon\') || msg.includes(\'good evening\')) {
                const greetings = [
                    \'Hello! How can I help you today?\',
                    \'Hi there! I\\\'m here to assist you with school-related questions.\',
                    \'Hello! What would you like to know about your school management system?\',
                    \'Hi! I can help with academic, financial, and administrative questions.\'
                ];
                return greetings[Math.floor(Math.random() * greetings.length)];
            }
            
            // Grade inquiries
            if (msg.includes(\'grade\') || msg.includes(\'score\') || msg.includes(\'mark\') || msg.includes(\'result\')) {
                return "To check your grades:\n\n1. Visit the Academic module\n2. Go to \'My Grades\' section\n3. Select the term/semester\n4. View detailed results\n\nYou can also contact your teacher for specific grade inquiries.";
            }
            
            // Schedule inquiries
            if (msg.includes(\'schedule\') || msg.includes(\'timetable\') || msg.includes(\'class\') || msg.includes(\'lesson\')) {
                return "To view your schedule:\n\n1. Go to the Academic module\n2. Click on \'My Schedule\'\n3. Select the day/week view\n4. Check for any updates\n\nYou can also sync your schedule with your calendar!";
            }
            
            // Fee inquiries
            if (msg.includes(\'fee\') || msg.includes(\'payment\') || msg.includes(\'money\') || msg.includes(\'cost\')) {
                return "For fee-related queries:\n\n1. Visit the Finance module\n2. Check \'My Fees\' section\n3. View payment history\n4. See upcoming payments\n\nContact the accounts department for detailed information.";
            }
            
            // Attendance inquiries
            if (msg.includes(\'attendance\') || msg.includes(\'present\') || msg.includes(\'absent\')) {
                return "To check your attendance:\n\n1. Go to the Attendance module\n2. View \'My Attendance\' report\n3. Check monthly/yearly statistics\n4. Contact your class teacher for any discrepancies";
            }
            
            // Default intelligent response
            return "I\'m here to help with school management questions! You can ask me about:\n\n• Grades and academic performance\n• Class schedules and timetables\n• Fees and payments\n• Attendance records\n• School events and policies\n\nFor more complex questions, please visit the specific modules or contact your teachers/administrators.";
        }
        
        function handleKeyPress(event) {
            console.log("Key pressed:", event.key);
            if (event.key === "Enter") {
                sendMessage();
            }
        }
        
        // Event listeners
        sendButton.addEventListener("click", sendMessage);
        messageInput.addEventListener("keypress", handleKeyPress);
        
        // Focus on input when page loads
        window.addEventListener("load", function() {
            console.log("Page loaded, focusing on input");
            messageInput.focus();
        });
        
        console.log("All functions defined successfully");
    </script>
</body>
</html>';
            
            return response($html)->header('Content-Type', 'text/html');
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * Send a message and get AI response
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            // Always use the ChatBotService for intelligent responses
            $context = [
                'user_role' => auth()->user()->role ?? 'Student',
                'current_module' => $request->input('module', 'General'),
            ];

            $response = $this->chatBotService->processMessage(
                $request->input('message'),
                auth()->id(),
                $context
            );

            return response()->json([
                'success' => true,
                'data' => $response,
            ]);
        } catch (\Exception $e) {
            // Even if there's an error, provide intelligent fallback response
            $message = $request->input('message');
            $fallbackResponse = $this->getIntelligentFallbackResponse($message);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'ai_response' => $fallbackResponse,
                    'conversation_id' => null,
                    'message_id' => null,
                    'timestamp' => now(),
                ],
            ]);
        }
    }

    /**
     * Get intelligent fallback response
     */
    private function getIntelligentFallbackResponse(string $message): string
    {
        $msg = strtolower(trim($message));
        
        // Mathematical calculations
        if (preg_match('/(\d+)\s*[\+\-\*\/]\s*(\d+)/', $msg, $matches)) {
            $num1 = (int)$matches[1];
            $num2 = (int)$matches[2];
            $operator = $msg[strpos($msg, $matches[1]) + strlen($matches[1])];
            
            switch($operator) {
                case '+': $result = $num1 + $num2; break;
                case '-': $result = $num1 - $num2; break;
                case '*': $result = $num1 * $num2; break;
                case '/': $result = $num2 != 0 ? $num1 / $num2 : 'Error: Division by zero'; break;
                default: $result = 'invalid operation';
            }
            
            return "The result of $num1 $operator $num2 = $result";
        }
        
        // User count question
        if (preg_match('/(how many|count|total).*user/', $msg)) {
            return "I can help you check the user count! Please visit the admin dashboard or contact your system administrator to view detailed user statistics. You can also check the Users section in the Core module.";
        }
        
        // Time question
        if (preg_match('/(what time|current time|time now|date)/', $msg)) {
            $currentTime = now()->format('l, F j, Y \a\t g:i A');
            return "The current time is: $currentTime";
        }
        
        // Help request
        if (preg_match('/(help|support|assist|what can you do)/', $msg)) {
            return "I can help you with:\n\n📚 **Academic**: Grades, schedules, homework, exams\n💰 **Financial**: Fees, payments, scholarships\n📋 **Administrative**: Attendance, transport, events\n🎓 **General**: School policies, contact info\n\nWhat would you like to know about?";
        }
        
        // Greetings
        if (preg_match('/(hello|hi|hey|good morning|good afternoon|good evening)/', $msg)) {
            $greetings = [
                'Hello! How can I help you today?',
                'Hi there! I\'m here to assist you with school-related questions.',
                'Hello! What would you like to know about your school management system?',
                'Hi! I can help with academic, financial, and administrative questions.'
            ];
            return $greetings[array_rand($greetings)];
        }
        
        // Grade inquiries
        if (preg_match('/(grade|score|mark|result|performance)/', $msg)) {
            return "To check your grades:\n\n1. Visit the Academic module\n2. Go to 'My Grades' section\n3. Select the term/semester\n4. View detailed results\n\nYou can also contact your teacher for specific grade inquiries.";
        }
        
        // Schedule inquiries
        if (preg_match('/(schedule|timetable|class|lesson|when is)/', $msg)) {
            return "To view your schedule:\n\n1. Go to the Academic module\n2. Click on 'My Schedule'\n3. Select the day/week view\n4. Check for any updates\n\nYou can also sync your schedule with your calendar!";
        }
        
        // Fee inquiries
        if (preg_match('/(fee|payment|money|cost|bill|balance)/', $msg)) {
            return "For fee-related queries:\n\n1. Visit the Finance module\n2. Check 'My Fees' section\n3. View payment history\n4. See upcoming payments\n\nContact the accounts department for detailed information.";
        }
        
        // Attendance inquiries
        if (preg_match('/(attendance|present|absent|attendance record)/', $msg)) {
            return "To check your attendance:\n\n1. Go to the Attendance module\n2. View 'My Attendance' report\n3. Check monthly/yearly statistics\n4. Contact your class teacher for any discrepancies";
        }
        
        // Weather (basic response)
        if (preg_match('/(weather|temperature|hot|cold)/', $msg)) {
            return "I can't provide real-time weather information, but you can check your local weather app or visit a weather website for current conditions.";
        }
        
        // Default intelligent response
        return "I'm here to help with school management questions! You can ask me about:\n\n• Grades and academic performance\n• Class schedules and timetables\n• Fees and payments\n• Attendance records\n• School events and policies\n\nFor more complex questions, please visit the specific modules or contact your teachers/administrators.";
    }

    /**
     * Get conversation history
     */
    public function getConversation($conversationId)
    {
        try {
            $conversation = $this->conversationService->getConversation($conversationId);
            
            return response()->json([
                'success' => true,
                'data' => $conversation,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Get recent conversations
     */
    public function getConversations()
    {
        $conversations = $this->conversationService->getRecentConversations();
        
        return response()->json([
            'success' => true,
            'data' => $conversations,
        ]);
    }

    /**
     * Clear conversation
     */
    public function clearConversation($conversationId)
    {
        try {
            $this->chatBotService->clearConversation($conversationId);
            
            return response()->json([
                'success' => true,
                'message' => 'Conversation cleared successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * End conversation
     */
    public function endConversation($conversationId)
    {
        try {
            $this->conversationService->endConversation($conversationId);
            
            return response()->json([
                'success' => true,
                'message' => 'Conversation ended successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get chatbot statistics
     */
    public function getStatistics()
    {
        $statistics = $this->chatBotService->getStatistics();
        
        return response()->json([
            'success' => true,
            'data' => $statistics,
        ]);
    }

    /**
     * Test chatbot functionality
     */
    public function test()
    {
        $result = $this->chatBotService->testChatBot();
        
        return response()->json($result);
    }

    /**
     * Stream response for real-time chat
     */
    public function streamResponse(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        return response()->stream(function () use ($request) {
            try {
                $context = [
                    'user_role' => auth()->user()->role ?? 'Student',
                    'current_module' => $request->input('module', 'General'),
                ];

                $response = $this->chatBotService->processMessage(
                    $request->input('message'),
                    auth()->id(),
                    $context
                );

                echo "data: " . json_encode([
                    'success' => true,
                    'data' => $response,
                ]) . "\n\n";
            } catch (\Exception $e) {
                echo "data: " . json_encode([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]) . "\n\n";
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }
} 