<script>
function toggleChatBot() {
    const container = document.getElementById('chatbotContainer');
    const toggle = document.getElementById('chatbotToggle');

    if (container.style.display === 'none' || container.style.display === '') {
        container.style.display = 'block';
        toggle.innerHTML = '<i class="fa fa-times"></i>';
    } else {
        container.style.display = 'none';
        toggle.innerHTML = '<i class="fa fa-comments"></i>';
    }
}

function sendChatBotMessage() {
    const input = document.getElementById('chatbotInput');
    const messages = document.getElementById('chatbotMessages');
    const message = input.value.trim();

    if (message) {
        // Add user message
        const userMessage = document.createElement('div');
        userMessage.className = 'chatbot-message user-message';
        userMessage.innerHTML = '<p>' + message + '</p>';
        messages.appendChild(userMessage);

        input.value = '';

        // Scroll to bottom
        messages.scrollTop = messages.scrollHeight;

        // Here you would typically send the message to your Laravel backend
        // For now, we'll simulate a response
        setTimeout(function() {
            const botMessage = document.createElement('div');
            botMessage.className = 'chatbot-message bot-message';
            botMessage.innerHTML = '<p>I\'m here to help! This is a placeholder response from the ChatBot.</p>';
            messages.appendChild(botMessage);
            messages.scrollTop = messages.scrollHeight;
        }, 1000);
    }
}

// Allow Enter key to send messages
document.getElementById('chatbotInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendChatBotMessage();
    }
});
</script>
