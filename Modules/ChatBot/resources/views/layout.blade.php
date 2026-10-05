<div class="chatbot-container" id="chatbotContainer" style="display: none;">
    <div class="chatbot-header">
        <h4>{{ config('chatbot.chatbot.name', 'Dunco AI Assistant') }}</h4>
        <button class="chatbot-close" onclick="toggleChatBot()">&times;</button>
    </div>
    <div class="chatbot-messages" id="chatbotMessages">
        <div class="chatbot-message bot-message">
            <p>{{ config('chatbot.chatbot.settings.welcome_message') }}</p>
        </div>
    </div>
    <div class="chatbot-input-container">
        <div class="chatbot-input-wrapper">
            <input type="text" class="chatbot-input" id="chatbotInput" placeholder="Type your message...">
            <button class="chatbot-send" onclick="sendChatBotMessage()">
                <i class="fa fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<button class="chatbot-toggle" id="chatbotToggle" onclick="toggleChatBot()">
    <i class="fa fa-comments"></i>
</button>
