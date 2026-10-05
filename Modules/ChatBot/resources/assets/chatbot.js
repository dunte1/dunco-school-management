/**
 * ChatBot Module JavaScript
 * Handles chatbot UI interactions and API communication
 */

class ChatBot {
    constructor() {
        this.isOpen = false;
        this.messages = [];
        this.init();
    }

    init() {
        this.createToggleButton();
        this.createChatContainer();
        this.bindEvents();
    }

    createToggleButton() {
        this.toggleBtn = document.createElement('button');
        this.toggleBtn.className = 'chatbot-toggle';
        this.toggleBtn.innerHTML = '💬';
        this.toggleBtn.onclick = () => this.toggleChat();
        document.body.appendChild(this.toggleBtn);
    }

    createChatContainer() {
        this.container = document.createElement('div');
        this.container.className = 'chatbot-container';
        this.container.innerHTML = `
            <div class="chatbot-header">
                <h4>Dunco AI Assistant</h4>
            </div>
            <div class="chatbot-messages" id="chatbotMessages"></div>
            <div class="chatbot-input-container">
                <input type="text" class="chatbot-input" placeholder="Type your message..." id="chatbotInput">
            </div>
        `;
        document.body.appendChild(this.container);
    }

    bindEvents() {
        const input = document.getElementById('chatbotInput');
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.sendMessage();
            }
        });
    }

    toggleChat() {
        this.isOpen = !this.isOpen;
        this.container.style.display = this.isOpen ? 'block' : 'none';
    }

    sendMessage() {
        const input = document.getElementById('chatbotInput');
        const message = input.value.trim();

        if (message) {
            this.addMessage('user', message);
            input.value = '';

            // Here you would typically send the message to your API
            // For now, we'll just show a placeholder response
            setTimeout(() => {
                this.addMessage('bot', 'I\'m here to help! This is a placeholder response.');
            }, 1000);
        }
    }

    addMessage(sender, message) {
        const messagesContainer = document.getElementById('chatbotMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        messageDiv.innerHTML = `<p>${message}</p>`;
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
}

// Initialize ChatBot when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ChatBot();
});
