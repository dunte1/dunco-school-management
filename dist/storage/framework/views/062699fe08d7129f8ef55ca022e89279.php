

<?php $__env->startSection('title', 'AI Chat Assistant'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-3 d-none d-md-block">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-comments"></i> Conversations
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="conversation-list" id="conversationList">
                        <!-- Conversations will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Chat Area -->
        <div class="col-md-9 col-lg-9">
            <div class="card chat-container">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="fas fa-robot"></i> AI Assistant
                        </h5>
                        <small class="text-muted" id="connectionStatus">Connecting...</small>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-sm btn-outline-secondary" id="clearChat">
                            <i class="fas fa-trash"></i> Clear
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" id="newChat">
                            <i class="fas fa-plus"></i> New Chat
                        </button>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <div class="chat-messages" id="chatMessages">
                        <!-- Welcome message -->
                        <div class="message assistant-message">
                            <div class="message-content">
                                <div class="message-text" id="welcomeMessage">
                                    <?php echo e($welcomeMessage ?? 'Hello! I\'m your AI assistant. How can I help you today?'); ?>

                                </div>
                                <div class="message-time"><?php echo e(now()->format('H:i')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <form id="chatForm" class="chat-input-form">
                        <div class="input-group">
                            <textarea 
                                class="form-control" 
                                id="messageInput" 
                                placeholder="Type your message here..."
                                rows="1"
                                maxlength="1000"
                            ></textarea>
                            <button class="btn btn-primary" type="submit" id="sendButton">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-muted">
                                <span id="charCount">0</span>/1000 characters
                            </small>
                            <small class="text-muted">
                                Press Ctrl+Enter to send
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 mb-0">AI is thinking...</p>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.chat-container {
    height: calc(100vh - 200px);
    display: flex;
    flex-direction: column;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    background: #f8f9fa;
}

.message {
    margin-bottom: 1rem;
    display: flex;
}

.user-message {
    justify-content: flex-end;
}

.assistant-message {
    justify-content: flex-start;
}

.message-content {
    max-width: 70%;
    padding: 0.75rem 1rem;
    border-radius: 1rem;
    position: relative;
}

.user-message .message-content {
    background: #007bff;
    color: white;
    border-bottom-right-radius: 0.25rem;
}

.assistant-message .message-content {
    background: white;
    border: 1px solid #dee2e6;
    border-bottom-left-radius: 0.25rem;
}

.message-text {
    margin-bottom: 0.25rem;
    word-wrap: break-word;
}

.message-time {
    font-size: 0.75rem;
    opacity: 0.7;
}

.chat-input-form {
    padding: 1rem;
}

.conversation-list {
    max-height: 400px;
    overflow-y: auto;
}

.conversation-item {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #dee2e6;
    cursor: pointer;
    transition: background-color 0.2s;
}

.conversation-item:hover {
    background-color: #f8f9fa;
}

.conversation-item.active {
    background-color: #e3f2fd;
    border-left: 3px solid #007bff;
}

.conversation-title {
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.conversation-preview {
    font-size: 0.875rem;
    color: #6c757d;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.typing-indicator {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.typing-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #6c757d;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dot:nth-child(1) { animation-delay: -0.32s; }
.typing-dot:nth-child(2) { animation-delay: -0.16s; }

@keyframes typing {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    .chat-container {
        height: calc(100vh - 150px);
    }
    
    .message-content {
        max-width: 85%;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
class ChatBot {
    constructor() {
        this.currentConversationId = null;
        this.isTyping = false;
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadConversations();
        this.updateConnectionStatus();
        this.setupAutoResize();
    }

    bindEvents() {
        const form = document.getElementById('chatForm');
        const input = document.getElementById('messageInput');
        const sendButton = document.getElementById('sendButton');
        const clearButton = document.getElementById('clearChat');
        const newChatButton = document.getElementById('newChat');

        form.addEventListener('submit', (e) => this.handleSubmit(e));
        input.addEventListener('keydown', (e) => this.handleKeydown(e));
        input.addEventListener('input', (e) => this.updateCharCount(e));
        clearButton.addEventListener('click', () => this.clearChat());
        newChatButton.addEventListener('click', () => this.newChat());
    }

    async handleSubmit(e) {
        e.preventDefault();
        const input = document.getElementById('messageInput');
        const message = input.value.trim();

        if (!message || this.isTyping) return;

        this.addMessage(message, 'user');
        input.value = '';
        this.updateCharCount();

        this.isTyping = true;
        this.showTypingIndicator();

        try {
            const response = await this.sendMessage(message);
            this.hideTypingIndicator();
            this.addMessage(response.ai_response, 'assistant');
            this.currentConversationId = response.conversation_id;
            this.loadConversations();
        } catch (error) {
            this.hideTypingIndicator();
            this.addMessage('Sorry, I encountered an error. Please try again.', 'assistant');
            console.error('Chat error:', error);
        }

        this.isTyping = false;
    }

    handleKeydown(e) {
        if (e.key === 'Enter' && e.ctrlKey) {
            e.preventDefault();
            document.getElementById('chatForm').dispatchEvent(new Event('submit'));
        }
    }

    updateCharCount() {
        const input = document.getElementById('messageInput');
        const charCount = document.getElementById('charCount');
        charCount.textContent = input.value.length;
    }

    setupAutoResize() {
        const textarea = document.getElementById('messageInput');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    }

    async sendMessage(message) {
        const response = await fetch('/chatbot/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                message: message,
                conversation_id: this.currentConversationId
            })
        });

        if (!response.ok) {
            throw new Error('Failed to send message');
        }

        const data = await response.json();
        return data.data;
    }

    addMessage(content, role) {
        const messagesContainer = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${role}-message`;
        
        const time = new Date().toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });

        messageDiv.innerHTML = `
            <div class="message-content">
                <div class="message-text">${this.escapeHtml(content)}</div>
                <div class="message-time">${time}</div>
            </div>
        `;

        messagesContainer.appendChild(messageDiv);
        this.scrollToBottom();
    }

    showTypingIndicator() {
        const messagesContainer = document.getElementById('chatMessages');
        const typingDiv = document.createElement('div');
        typingDiv.className = 'message assistant-message';
        typingDiv.id = 'typingIndicator';
        
        typingDiv.innerHTML = `
            <div class="message-content">
                <div class="typing-indicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        `;

        messagesContainer.appendChild(typingDiv);
        this.scrollToBottom();
    }

    hideTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    scrollToBottom() {
        const messagesContainer = document.getElementById('chatMessages');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    async loadConversations() {
        try {
            const response = await fetch('/chatbot/conversations');
            const data = await response.json();
            
            if (data.success) {
                this.renderConversations(data.data);
            }
        } catch (error) {
            console.error('Failed to load conversations:', error);
        }
    }

    renderConversations(conversations) {
        const container = document.getElementById('conversationList');
        container.innerHTML = '';

        conversations.forEach(conversation => {
            const div = document.createElement('div');
            div.className = 'conversation-item';
            div.dataset.id = conversation.id;
            
            div.innerHTML = `
                <div class="conversation-title">${conversation.title || 'New Conversation'}</div>
                <div class="conversation-preview">${conversation.summary || 'No messages'}</div>
            `;

            div.addEventListener('click', () => this.loadConversation(conversation.id));
            container.appendChild(div);
        });
    }

    async loadConversation(conversationId) {
        try {
            const response = await fetch(`/chatbot/conversation/${conversationId}`);
            const data = await response.json();
            
            if (data.success) {
                this.currentConversationId = conversationId;
                this.displayConversation(data.data);
            }
        } catch (error) {
            console.error('Failed to load conversation:', error);
        }
    }

    displayConversation(conversation) {
        const messagesContainer = document.getElementById('chatMessages');
        messagesContainer.innerHTML = '';

        conversation.messages.forEach(message => {
            this.addMessage(message.content, message.role);
        });
    }

    async clearChat() {
        if (!confirm('Are you sure you want to clear this conversation?')) return;

        if (this.currentConversationId) {
            try {
                await fetch(`/chatbot/conversation/${this.currentConversationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
            } catch (error) {
                console.error('Failed to clear conversation:', error);
            }
        }

        this.currentConversationId = null;
        document.getElementById('chatMessages').innerHTML = '';
        this.addMessage(document.getElementById('welcomeMessage').textContent, 'assistant');
        this.loadConversations();
    }

    newChat() {
        this.currentConversationId = null;
        document.getElementById('chatMessages').innerHTML = '';
        this.addMessage(document.getElementById('welcomeMessage').textContent, 'assistant');
    }

    async updateConnectionStatus() {
        try {
            const response = await fetch('/chatbot/test');
            const data = await response.json();
            
            const statusElement = document.getElementById('connectionStatus');
            if (data.success && data.openai_available) {
                statusElement.textContent = 'Connected';
                statusElement.className = 'text-success';
            } else {
                statusElement.textContent = 'Disconnected';
                statusElement.className = 'text-danger';
            }
        } catch (error) {
            document.getElementById('connectionStatus').textContent = 'Error';
            document.getElementById('connectionStatus').className = 'text-danger';
        }
    }
}

// Initialize chatbot when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ChatBot();
});
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\ChatBot\resources\views\chat.blade.php ENDPATH**/ ?>