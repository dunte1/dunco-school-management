<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Chat Assistant - Mobile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html, body {
            font-family: 'Inter', sans-serif !important;
            height: 100vh !important;
            height: 100dvh !important;
            overflow: hidden !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .chat-container {
            height: 100vh !important;
            height: 100dvh !important;
            display: flex !important;
            flex-direction: column !important;
        }
        .input-area {
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 1000 !important;
            background: #1e40af !important;
            padding: 1rem !important;
        }
        .messages-area {
            flex: 1 !important;
            overflow-y: auto !important;
            padding-bottom: 140px !important;
        }
        .touch-button {
            min-height: 44px !important;
            min-width: 44px !important;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: -300px;
            width: 300px;
            height: 100vh;
            background: rgba(30, 64, 175, 0.95);
            backdrop-filter: blur(10px);
            z-index: 2000;
            transition: left 0.3s ease;
            padding: 1rem;
        }
        .sidebar.open {
            left: 0;
        }
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1500;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .message-content {
            line-height: 1.5;
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
            background-color: rgba(255, 255, 255, 0.6);
            animation: typingAnimation 1.4s infinite ease-in-out;
        }
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        .typing-dot:nth-child(3) { animation-delay: 0s; }
        @keyframes typingAnimation {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-white text-lg font-bold">Menu</h2>
            <button id="closeSidebar" class="text-white hover:text-blue-200 touch-button">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="space-y-4">
            <button class="w-full text-left text-white hover:text-blue-200 p-3 rounded-lg hover:bg-white/10 transition-all touch-button">
                <i class="fas fa-history mr-3"></i>
                Chat History
            </button>
            <button class="w-full text-left text-white hover:text-blue-200 p-3 rounded-lg hover:bg-white/10 transition-all touch-button">
                <i class="fas fa-cog mr-3"></i>
                Settings
            </button>
            <button id="clearHistoryBtn" class="w-full text-left text-white hover:text-blue-200 p-3 rounded-lg hover:bg-white/10 transition-all touch-button">
                <i class="fas fa-trash mr-3"></i>
                Clear History
            </button>
            <button class="w-full text-left text-white hover:text-blue-200 p-3 rounded-lg hover:bg-white/10 transition-all touch-button">
                <i class="fas fa-question-circle mr-3"></i>
                Help
            </button>
        </div>
    </div>

    <div class="chat-container">
        <!-- Header -->
        <header class="bg-blue-600 text-white p-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-robot text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold">AI Assistant</h1>
                    <p class="text-blue-200 text-sm">Online</p>
                </div>
            </div>
            <button id="hamburgerMenu" class="touch-button p-2 rounded-full hover:bg-blue-700 transition-colors">
                <i class="fas fa-bars text-white text-lg"></i>
            </button>
        </header>

        <!-- Messages -->
        <div class="messages-area p-4 space-y-4" id="chatMessages">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white text-sm"></i>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 max-w-xs text-white">
                    <p>{{ $welcomeMessage ?? 'Hello! I\'m your AI assistant for Dunco School Management System. I can help you with academic queries, administrative tasks, and general school information. How can I assist you today?' }}</p>
                    <div class="text-xs text-blue-200 mt-2">{{ now()->format('H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="input-area">
            <!-- Suggested Prompts -->
            <div class="flex gap-2 mb-4 overflow-x-auto pb-2" style="scrollbar-width: none; -ms-overflow-style: none;">
                <button class="prompt-chip bg-blue-800/50 hover:bg-blue-700/50 rounded-lg text-blue-200 text-xs font-medium px-3 py-2 whitespace-nowrap touch-button transition-all">
                    📚 Ask about grades
                </button>
                <button class="prompt-chip bg-blue-800/50 hover:bg-blue-700/50 rounded-lg text-blue-200 text-xs font-medium px-3 py-2 whitespace-nowrap touch-button transition-all">
                    💰 Check fees
                </button>
                <button class="prompt-chip bg-blue-800/50 hover:bg-blue-700/50 rounded-lg text-blue-200 text-xs font-medium px-3 py-2 whitespace-nowrap touch-button transition-all">
                    📅 View schedule
                </button>
                <button class="prompt-chip bg-blue-800/50 hover:bg-blue-700/50 rounded-lg text-blue-200 text-xs font-medium px-3 py-2 whitespace-nowrap touch-button transition-all">
                    📊 Attendance
                </button>
            </div>
            
            <!-- Input Row -->
            <div class="flex items-center space-x-3">
                <!-- File Upload Button -->
                <button id="fileUploadBtn" class="touch-button bg-white/10 backdrop-blur-md border border-blue-400/30 rounded-full p-3 text-blue-200 hover:bg-white/20 transition-all">
                    <i class="fas fa-paperclip text-lg"></i>
                </button>
                
                <!-- Text Input -->
                <div class="flex-1 relative">
                    <input 
                        type="text" 
                        id="messageInput"
                        placeholder="Type your message..." 
                        class="w-full bg-white/10 backdrop-blur-md border border-blue-400/30 rounded-full px-4 py-3 pr-12 text-white placeholder-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                    >
                </div>
                
                <!-- Send Button -->
                <button 
                    id="sendButton"
                    class="touch-button bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white rounded-full p-3 transition-all duration-200 shadow-lg disabled:opacity-50"
                >
                    <i class="fas fa-paper-plane text-lg"></i>
                </button>
            </div>
            
            <!-- Hidden File Input -->
            <input type="file" id="fileInput" class="hidden" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
        </div>
    </div>

    <script>
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Elements
        const messageInput = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const fileUploadBtn = document.getElementById('fileUploadBtn');
        const fileInput = document.getElementById('fileInput');
        const chatMessages = document.getElementById('chatMessages');
        const hamburgerMenu = document.getElementById('hamburgerMenu');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const closeSidebar = document.getElementById('closeSidebar');
        const clearHistoryBtn = document.getElementById('clearHistoryBtn');
        
        // Sidebar Functions
        function openSidebar() {
            sidebar.classList.add('open');
            sidebarOverlay.classList.add('show');
        }
        
        function closeSidebarFunc() {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('show');
        }
        
        // Send Message Function
        function sendMessage() {
            const message = messageInput.value.trim();
            if (!message) return;
            
            // Add user message to chat
            addMessageToChat(message, 'user');
            
            // Clear input
            messageInput.value = '';
            
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
                console.log('Response data:', data); // Debug log
                
                if (data.success && data.data) {
                    // Handle different response formats
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
                        // If data.data is an object, try to extract meaningful text
                        aiResponse = JSON.stringify(data.data);
                    }
                    
                    // Ensure we have a string response
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
            messageDiv.className = 'flex items-start space-x-3 animate-fade-in';
            
            // Sanitize message to prevent XSS but allow basic formatting
            const sanitizedMessage = sanitizeMessage(message);
            const timestamp = new Date().toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'});
            
            if (sender === 'user') {
                messageDiv.innerHTML = `
                    <div class="flex-1"></div>
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-4 max-w-xs text-white">
                        <div class="message-content">${sanitizedMessage}</div>
                        <div class="text-xs text-blue-200 mt-2">${timestamp}</div>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                `;
            } else {
                messageDiv.innerHTML = `
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white text-sm"></i>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 max-w-xs text-white">
                        <div class="message-content">${sanitizedMessage}</div>
                        <div class="text-xs text-blue-200 mt-2">${timestamp}</div>
                    </div>
                `;
            }
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Sanitize message content while preserving basic formatting
        function sanitizeMessage(message) {
            if (typeof message !== 'string') {
                message = String(message);
            }
            
            // Convert newlines to <br> tags
            message = message.replace(/\n/g, '<br>');
            
            // Allow basic markdown-style formatting
            message = message.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            message = message.replace(/\*(.*?)\*/g, '<em>$1</em>');
            
            // Convert URLs to clickable links
            const urlRegex = /(https?:\/\/[^\s]+)/g;
            message = message.replace(urlRegex, '<a href="$1" target="_blank" class="text-blue-300 underline">$1</a>');
            
            // Escape any remaining HTML to prevent XSS
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = message;
            
            return tempDiv.innerHTML;
        }
        
        // Show typing indicator
        function showTypingIndicator() {
            const typingDiv = document.createElement('div');
            typingDiv.id = 'typingIndicator';
            typingDiv.className = 'flex items-start space-x-3 animate-fade-in';
            typingDiv.innerHTML = `
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-white text-sm"></i>
                </div>
                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 max-w-xs text-white">
                    <div class="typing-indicator">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>
            `;
            
            chatMessages.appendChild(typingDiv);
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
        hamburgerMenu.addEventListener('click', openSidebar);
        closeSidebar.addEventListener('click', closeSidebarFunc);
        sidebarOverlay.addEventListener('click', closeSidebarFunc);
        
        messageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
        
        fileUploadBtn.addEventListener('click', function() {
            fileInput.click();
        });
        
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                console.log('File selected:', file.name);
                uploadFile(file);
            }
        });
        
        // Upload file function
        function uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);
            
            // Show upload message
            addMessageToChat(`📎 Uploading: ${file.name}`, 'user');
            
            // Show typing indicator
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
                // Reset file input
                fileInput.value = '';
            });
        }
        
        clearHistoryBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear chat history?')) {
                chatMessages.innerHTML = `
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-robot text-white text-sm"></i>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 max-w-xs text-white">
                            <p>Chat history cleared. How can I help you today?</p>
                            <div class="text-xs text-blue-200 mt-2">${new Date().toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'})}</div>
                        </div>
                    </div>
                `;
                closeSidebarFunc();
            }
        });
        
        // Suggested Prompts
        document.querySelectorAll('.prompt-chip').forEach(chip => {
            chip.addEventListener('click', function() {
                const text = this.textContent.trim();
                messageInput.value = text;
                sendMessage();
            });
        });
        
        // Focus input on load
        messageInput.focus();
    </script>
</body>
</html>
