<?php

namespace Modules\ChatBot\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ChatBot\Services\ChatBotService;
use Modules\ChatBot\Services\ConversationService;
use Modules\ChatBot\Services\DocumentService;
use Modules\ChatBot\Models\Conversation;
use Modules\ChatBot\Models\Message;
use Modules\ChatBot\Models\Document;

class ChatBotController extends Controller
{
    protected $chatBotService;
    protected $conversationService;
    protected $documentService;

    public function __construct(ChatBotService $chatBotService, ConversationService $conversationService, DocumentService $documentService)
    {
        $this->chatBotService = $chatBotService;
        $this->conversationService = $conversationService;
        $this->documentService = $documentService;
    }

    /**
     * Display the chat interface
     */
    public function index()
    {
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return redirect()->route('login');
            }
            
            // Get recent conversations (handle gracefully if table doesn't exist)
            try {
            $conversations = $this->conversationService->getRecentConversations();
            } catch (\Exception $e) {
                $conversations = collect([]);
            }
            
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
            align-items: center;
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
        .file-upload-btn {
            padding: 15px 20px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: transform 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .file-upload-btn:hover {
            transform: translateY(-2px);
        }
        .file-input {
            display: none;
        }
        .upload-progress {
            display: none;
            padding: 10px;
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 10px;
            margin: 10px 0;
            text-align: center;
            color: #0369a1;
        }
        .document-message {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
        }
        .document-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .document-icon {
            font-size: 24px;
        }
        .document-details h4 {
            margin: 0;
            color: #374151;
            font-size: 14px;
        }
        .document-details p {
            margin: 0;
            color: #6b7280;
            font-size: 12px;
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
            <button class="file-upload-btn" onclick="document.getElementById(\'fileInput\').click()">
                📎 Upload
            </button>
            <input type="file" id="fileInput" class="file-input" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.bmp,.webp,.ppt,.pptx">
            <button id="sendButton">Send</button>
        </div>
        <div class="upload-progress" id="uploadProgress">
            <div>📤 Uploading document...</div>
        </div>
    </div>
    
    <script src="/chatbot/assets/js/chatbot.js"></script>
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
        
        // Health and medical queries
        if (preg_match('/(headache|pain|sick|ill|health|medical|doctor|medicine)/', $msg)) {
            return "I'm not a medical professional, but I can help you with:\n\n• School health policies and procedures\n• Contact information for the school nurse\n• Health-related absences and documentation\n• First aid information\n\nFor medical advice, please consult a healthcare professional or visit the school nurse.";
        }
        
        // Academic subject queries
        if (preg_match('/(anatomy|biology|chemistry|physics|math|history|geography|literature|english|science)/', $msg)) {
            return "I can help you with academic subjects! Here's what I can assist with:\n\n• Finding study materials and resources\n• Explaining concepts and topics\n• Providing study tips and strategies\n• Connecting you with subject teachers\n• Accessing library resources\n\nWhat specific topic would you like help with?";
        }
        
        // General knowledge questions
        if (preg_match('/(what is|who is|when is|where is|how to|explain|define|meaning of)/', $msg)) {
            return "I can help you with general knowledge questions! I can:\n\n• Explain concepts and definitions\n• Provide information on various topics\n• Help with research and study questions\n• Connect you with relevant resources\n• Assist with homework and assignments\n\nPlease ask your specific question and I'll do my best to help!";
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
        
        // Default enhanced response
        return "I'm here to help with school management questions! You can ask me about:\n\n• Grades and academic performance\n• Class schedules and timetables\n• Fees and payments\n• Attendance records\n• School events and policies\n• Academic subjects and study help\n• General knowledge questions\n• Health and wellness information\n\nFor more complex questions, please visit the specific modules or contact your teachers/administrators.";
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

    /**
     * Get chat history
     */
    public function getHistory()
    {
        try {
            $conversations = $this->conversationService->getRecentConversations();
            
            return response()->json([
                'success' => true,
                'data' => $conversations,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a specific message
     */
    public function deleteMessage($id)
    {
        try {
            $message = Message::findOrFail($id);
            
            // Check if user owns this message
            if ($message->conversation->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }
            
            $message->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Message deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear chat history
     */
    public function clearHistory()
    {
        try {
            $userId = auth()->id();
            $conversations = Conversation::where('user_id', $userId)->get();
            
            foreach ($conversations as $conversation) {
                $conversation->messages()->delete();
                $conversation->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Chat history cleared successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Upload document for analysis
     */
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document' => 'required|file|max:10240', // 10MB max
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $file = $request->file('document');
            $userId = auth()->id();
            $conversationId = $request->input('conversation_id');
            $description = $request->input('description');

            $document = $this->documentService->uploadDocument($file, $userId, $conversationId, $description);

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully',
                'data' => [
                    'document_id' => $document->id,
                    'filename' => $document->original_filename,
                    'file_type' => $document->document_type,
                    'file_size' => $document->formatted_size,
                    'is_processed' => $document->is_processed,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload document: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ask questions about uploaded documents
     */
    public function askDocument(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'document_id' => 'required|integer|exists:chatbot_documents,id',
        ]);

        try {
            $document = Document::where('id', $request->input('document_id'))
                ->where('user_id', auth()->id())
                ->firstOrFail();

            if (!$document->is_processed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Document is still being processed. Please wait a moment and try again.',
                ], 400);
            }

            // Create context with document content
            $context = [
                'user_role' => auth()->user()->role ?? 'Student',
                'current_module' => 'Document Analysis',
                'document_content' => $document->content_text,
                'document_name' => $document->original_filename,
                'document_type' => $document->document_type,
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
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user's uploaded documents
     */
    public function getDocuments()
    {
        try {
            $documents = $this->documentService->getUserDocuments(auth()->id());
            
            return response()->json([
                'success' => true,
                'data' => $documents,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a document
     */
    public function deleteDocument($documentId)
    {
        try {
            $this->documentService->deleteDocument($documentId, auth()->id());
            
            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Search documents
     */
    public function searchDocuments(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2|max:100',
        ]);

        try {
            $documents = $this->documentService->searchDocuments(
                auth()->id(),
                $request->input('query')
            );
            
            return response()->json([
                'success' => true,
                'data' => $documents,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
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



    /**

     * Get chat history

     */

    public function getHistory()

    {

        try {

            $conversations = $this->conversationService->getRecentConversations();

            

            return response()->json([

                'success' => true,

                'data' => $conversations,

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

            ], 500);

        }

    }



    /**

     * Delete a specific message

     */

    public function deleteMessage($id)

    {

        try {

            $message = Message::findOrFail($id);

            

            // Check if user owns this message

            if ($message->conversation->user_id !== auth()->id()) {

                return response()->json([

                    'success' => false,

                    'message' => 'Unauthorized',

                ], 403);

            }

            

            $message->delete();

            

            return response()->json([

                'success' => true,

                'message' => 'Message deleted successfully',

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

            ], 500);

        }

    }



    /**

     * Clear chat history

     */

    public function clearHistory()

    {

        try {

            $userId = auth()->id();

            $conversations = Conversation::where('user_id', $userId)->get();

            

            foreach ($conversations as $conversation) {

                $conversation->messages()->delete();

                $conversation->delete();

            }

            

            return response()->json([

                'success' => true,

                'message' => 'Chat history cleared successfully',

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

            ], 500);

        }

    }



    /**

     * Upload document for analysis

     */

    public function uploadDocument(Request $request)

    {

        $request->validate([

            'document' => 'required|file|max:10240', // 10MB max

            'description' => 'nullable|string|max:500',

        ]);



        try {

            $file = $request->file('document');

            $userId = auth()->id();

            $conversationId = $request->input('conversation_id');

            $description = $request->input('description');



            $document = $this->documentService->uploadDocument($file, $userId, $conversationId, $description);



            return response()->json([

                'success' => true,

                'message' => 'Document uploaded successfully',

                'data' => [

                    'document_id' => $document->id,

                    'filename' => $document->original_filename,

                    'file_type' => $document->document_type,

                    'file_size' => $document->formatted_size,

                    'is_processed' => $document->is_processed,

                ]

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => 'Failed to upload document: ' . $e->getMessage(),

            ], 500);

        }

    }



    /**

     * Ask questions about uploaded documents

     */

    public function askDocument(Request $request)

    {

        $request->validate([

            'message' => 'required|string|max:1000',

            'document_id' => 'required|integer|exists:chatbot_documents,id',

        ]);



        try {

            $document = Document::where('id', $request->input('document_id'))

                ->where('user_id', auth()->id())

                ->firstOrFail();



            if (!$document->is_processed) {

                return response()->json([

                    'success' => false,

                    'message' => 'Document is still being processed. Please wait a moment and try again.',

                ], 400);

            }



            // Create context with document content

            $context = [

                'user_role' => auth()->user()->role ?? 'Student',

                'current_module' => 'Document Analysis',

                'document_content' => $document->content_text,

                'document_name' => $document->original_filename,

                'document_type' => $document->document_type,

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

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

            ], 500);

        }

    }



    /**

     * Get user's uploaded documents

     */

    public function getDocuments()

    {

        try {

            $documents = $this->documentService->getUserDocuments(auth()->id());

            

            return response()->json([

                'success' => true,

                'data' => $documents,

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

            ], 500);

        }

    }



    /**

     * Delete a document

     */

    public function deleteDocument($documentId)

    {

        try {

            $this->documentService->deleteDocument($documentId, auth()->id());

            

            return response()->json([

                'success' => true,

                'message' => 'Document deleted successfully',

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

            ], 500);

        }

    }



    /**

     * Search documents

     */

    public function searchDocuments(Request $request)

    {

        $request->validate([

            'query' => 'required|string|min:2|max:100',

        ]);



        try {

            $documents = $this->documentService->searchDocuments(

                auth()->id(),

                $request->input('query')

            );

            

            return response()->json([

                'success' => true,

                'data' => $documents,

            ]);

        } catch (\Exception $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

            ], 500);

        }

    }

} 
