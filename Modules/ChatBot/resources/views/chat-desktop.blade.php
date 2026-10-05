<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Chat Assistant - Dunco School Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html, body {
            font-family: 'Inter', sans-serif !important;
            height: 100vh !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .chat-container {
            height: 100vh !important;
            display: grid !important;
            grid-template-columns: 300px 1fr !important;
        }
        .sidebar {
            background: linear-gradient(135deg, #1e40af 0%, #3730a3 100%);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        .main-chat {
            display: flex !important;
            flex-direction: column !important;
            height: 100vh !important;
        }
        .messages-area {
            flex: 1 !important;
            overflow-y: auto !important;
            padding: 2rem !important;
        }
        .input-area {
            padding: 1.5rem !important;
            background: rgba(30, 64, 175, 0.05) !important;
            border-top: 1px solid rgba(30, 64, 175, 0.1) !important;
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .message-content {
            line-height: 1.6;
            word-wrap: break-word;
        }
        .message-content strong {
            font-weight: 600;
        }
        .message-content em {
            font-style: italic;
        }
        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .typing-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: rgba(59, 130, 246, 0.6);
            animation: typingAnimation 1.4s infinite ease-in-out;
        }
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        .typing-dot:nth-child(3) { animation-delay: 0s; }
        @keyframes typingAnimation {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }
        .conversation-item:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 3px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="chat-container">
        <!-- Sidebar -->
        <div class="sidebar text-white">
            <!-- Header -->
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-robot text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">AI Assistant</h1>
                        <p class="text-blue-200 text-sm">Dunco School Management</p>
                    </div>
                </div>
            </div>

            <!-- New Chat Button -->
            <div class="p-4">
                <button id="newChatBtn" class="w-full bg-white/10 hover:bg-white/20 rounded-lg p-3 flex items-center space-x-3 transition-all">
                    <i class="fas fa-plus"></i>
                    <span>New Chat</span>
                </button>
            </div>

            <!-- Chat History -->
            <div class="flex-1 overflow-y-auto scrollbar-thin">
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-blue-200 mb-3">Recent Conversations</h3>
                    <div id="conversationsList" class="space-y-2">
                        <!-- Conversations will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="p-4 border-t border-white/10">
                <button id="settingsBtn" class="w-full text-left text-blue-200 hover:text-white p-3 rounded-lg hover:bg-white/10 transition-all flex items-center space-x-3">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </button>
                <button id="clearAllBtn" class="w-full text-left text-blue-200 hover:text-white p-3 rounded-lg hover:bg-white/10 transition-all flex items-center space-x-3">
                    <i class="fas fa-trash"></i>
                    <span>Clear All</span>
                </button>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="main-chat bg-white">
            <!-- Chat Header -->
            <header class="bg-white border-b border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-robot text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">AI Assistant</h2>
                            <p class="text-sm text-green-600 flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                Online
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button id="voiceBtn" class="p-2 text-gray-500 hover:text-blue-600 rounded-full hover:bg-gray-100 transition-all">
                            <i class="fas fa-microphone"></i>
                        </button>
                        <button id="fileBtn" class="p-2 text-gray-500 hover:text-blue-600 rounded-full hover:bg-gray-100 transition-all">
                            <i class="fas fa-paperclip"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Messages Area -->
            <div class="messages-area scrollbar-thin" id="chatMessages">
                <div class="max-w-4xl mx-auto">
                    <!-- Welcome Message -->
                    <div class="flex items-start space-x-4 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-robot text-white"></i>
                        </div>
                        <div class="bg-gray-100 rounded-2xl rounded-tl-none p-4 max-w-2xl">
                            <div class="message-content text-gray-800">
                                {{ $welcomeMessage ?? 'Hello! I\'m your AI assistant for Dunco School Management System. I can help you with academic queries, administrative tasks, and general school information. How can I assist you today?' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-2">{{ now()->format('H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="input-area">
                <div class="max-w-4xl mx-auto">
                    <!-- Suggested Prompts -->
                    <div class="flex gap-3 mb-4 overflow-x-auto pb-2">
                        <button class="prompt-chip bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-full px-4 py-2 text-sm font-medium whitespace-nowrap transition-all">
                            📚 Check my grades
                        </button>
                        <button class="prompt-chip bg-green-100 hover:bg-green-200 text-green-700 rounded-full px-4 py-2 text-sm font-medium whitespace-nowrap transition-all">
                            💰 View fee status
                        </button>
                        <button class="prompt-chip bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-full px-4 py-2 text-sm font-medium whitespace-nowrap transition-all">
                            📅 Today's schedule
                        </button>
                        <button class="prompt-chip bg-orange-100 hover:bg-orange-200 text-orange-700 rounded-full px-4 py-2 text-sm font-medium whitespace-nowrap transition-all">
                            📊 Attendance record
                        </button>
                    </div>
                    
                    <!-- Input Row -->
                    <div class="flex items-end space-x-4">
                        <div class="flex-1 relative">
                            <textarea 
                                id="messageInput"
                                placeholder="Type your message..." 
                                class="w-full bg-white border border-gray-300 rounded-2xl px-4 py-3 pr-12 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                rows="1"
                                style="max-height: 120px;"
                            ></textarea>
                        </div>
                        
                        <button 
                            id="sendButton"
                            class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-2xl p-3 transition-all duration-200 shadow-lg disabled:opacity-50"
                        >
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden File Input -->
    <input type="file" id="fileInput" class="hidden" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">

    <script>
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Elements
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const chatMessages = document.getElementById('chatMessages');
        const fileBtn = document.getElementById('fileBtn');
        const fileInput = document.getElementById('fileInput');
        const newChatBtn = document.getElementById('newChatBtn');
        const clearAllBtn = document.getElementById('clearAllBtn');
        
        // Auto-resize textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
        
        // Send Message Function
        function sendMessage() {
            const message = messageInput.value.trim();
            if (!message) return;
            
            // Add user message to chat
            addMessageToChat(message, 'user');
            
            // Clear input
            messageInput.value = '';
            messageInput.style.height = 'auto';
            
            // Disable send button
            sendButton.disabled = true;
            
            // Show typing indicator
            showTypingIndicator();
            
            // Send to server
            fetch('/chatbot/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: message })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success && data.data) {
                    let aiResponse = '';
                    
                    if (typeof data.data === 'string') {
                        aiResponse = data.data;
                    } else if (data.data.ai_response) {
                        aiResponse = data.data.ai_response;
                    } else if (data.data.message) {
                        aiResponse = data.data.message;
                    } else if (data.data.response) {
                        aiResponse = data.data.response;
                    } else {
                        aiResponse = JSON.stringify(data.data);
                    }
                    
                    if (typeof aiResponse !== 'string') {
                        aiResponse = String(aiResponse);
                    }
                    
                    addMessageToChat(aiResponse, 'ai');
                } else if (data.message) {
                    addMessageToChat(data.message, 'ai');
                } else {
                    addMessageToChat('Sorry, I encountered an error processing your request. Please try again.', 'ai');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                addMessageToChat('Sorry, I encountered an error. Please try again.', 'ai');
            })
            .finally(() => {
                sendButton.disabled = false;
                hideTypingIndicator();
            });
        }
        
        // Add Message to Chat
        function addMessageToChat(message, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex items-start space-x-4 mb-6 animate-fade-in';
            
            const sanitizedMessage = sanitizeMessage(message);
            const timestamp = new Date().toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'});
            
            if (sender === 'user') {
                messageDiv.innerHTML = `
                    <div class="flex-1"></div>
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl rounded-tr-none p-4 max-w-2xl text-white">
                        <div class="message-content">${sanitizedMessage}</div>
                        <div class="text-xs text-blue-200 mt-2">${timestamp}</div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-white"></i>
                    </div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white"></i>
                    </div>
                    <div class="bg-gray-100 rounded-2xl rounded-tl-none p-4 max-w-2xl">
                        <div class="message-content text-gray-800">${sanitizedMessage}</div>
                        <div class="text-xs text-gray-500 mt-2">${timestamp}</div>
                    </div>
                `;
            }
            
            const container = chatMessages.querySelector('.max-w-4xl');
            container.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Sanitize message content
        function sanitizeMessage(message) {
            if (typeof message !== 'string') {
                message = String(message);
            }
            
            message = message.replace(/\n/g, '<br>');
            message = message.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            message = message.replace(/\*(.*?)\*/g, '<em>$1</em>');
            
            const urlRegex = /(https?:\/\/[^\s]+)/g;
            message = message.replace(urlRegex, '<a href="$1" target="_blank" class="text-blue-600 underline">$1</a>');
            
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = message;
            
            return tempDiv.innerHTML;
        }
        
        // Show typing indicator
        function showTypingIndicator() {
            const typingDiv = document.createElement('div');
            typingDiv.id = 'typingIndicator';
            typingDiv.className = 'flex items-start space-x-4 mb-6 animate-fade-in';
            typingDiv.innerHTML = `
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white"></i>
                </div>
                <div class="bg-gray-100 rounded-2xl rounded-tl-none p-4 max-w-2xl">
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            `;
            
            const container = chatMessages.querySelector('.max-w-4xl');
            container.appendChild(typingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Hide typing indicator
        function hideTypingIndicator() {
            const typingIndicator = document.getElementById('typingIndicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }
        
        // Event Listeners
        sendButton.addEventListener('click', sendMessage);
        
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        fileBtn.addEventListener('click', function() {
            fileInput.click();
        });
        
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                uploadFile(file);
            }
        });
        
        // Upload file function
        function uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);
            
            addMessageToChat(`📎 Uploading: ${file.name}`, 'user');
            showTypingIndicator();
            
            fetch('/chatbot/upload-document', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const message = `✅ File uploaded successfully!\n\n**${data.filename}** (${data.file_size})\n\n${data.content_preview || 'File processed and ready for analysis.'}`;
                    addMessageToChat(message, 'ai');
                } else {
                    addMessageToChat(`❌ Upload failed: ${data.message}`, 'ai');
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                addMessageToChat('❌ Failed to upload file. Please try again.', 'ai');
            })
            .finally(() => {
                hideTypingIndicator();
                fileInput.value = '';
            });
        }
        
        // Suggested Prompts
        document.querySelectorAll('.prompt-chip').forEach(chip => {
            chip.addEventListener('click', function() {
                const text = this.textContent.trim();
                messageInput.value = text;
                sendMessage();
            });
        });
        
        // New Chat
        newChatBtn.addEventListener('click', function() {
            const container = chatMessages.querySelector('.max-w-4xl');
            container.innerHTML = `
                <div class="flex items-start space-x-4 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white"></i>
                    </div>
                    <div class="bg-gray-100 rounded-2xl rounded-tl-none p-4 max-w-2xl">
                        <div class="message-content text-gray-800">Hello! How can I help you today?</div>
                        <div class="text-xs text-gray-500 mt-2">${new Date().toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'})}</div>
                    </div>
                </div>
            `;
        });
        
        // Clear All
        clearAllBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear all chat history?')) {
                newChatBtn.click();
            }
        });
        
        // Focus input on load
        messageInput.focus();
    </script>
</body>
</html>
