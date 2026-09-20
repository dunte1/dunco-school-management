@extends('chatbot::layouts.master')

@section('content')
<div class="chat-container">
    <div class="chat-header">
        <div class="status-indicator"></div>
        <h2>AI Assistant</h2>
        <p>Your intelligent school management assistant</p>
    </div>
    <div class="chat-messages" id="chatMessages">
        <div class="message bot-message">
            <div class="welcome">{!! htmlspecialchars($welcomeMessage) !!}</div>
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
        <input type="text" id="messageInput" placeholder="Ask me anything about school management..." autofocus>
        <button class="file-upload-btn" onclick="document.getElementById('fileInput').click()">
            Upload
        </button>
        <input type="file" id="fileInput" class="file-input" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.bmp,.webp,.ppt,.pptx">
        <button id="sendButton">Send</button>
    </div>
    <div class="upload-progress" id="uploadProgress">
        <div>Uploading document...</div>
    </div>
</div>

@push('styles')
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
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
    .chat-header h2 { font-size: 24px; margin-bottom: 5px; font-weight: 600; }
    .chat-header p { font-size: 14px; opacity: 0.9; }
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
    .chat-input input:focus { border-color: #667eea; }
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
    .chat-input button:hover { transform: translateY(-2px); }
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
    .file-upload-btn:hover { transform: translateY(-2px); }
    .file-input { display: none; }
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
    .typing-dots { display: flex; gap: 4px; }
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
@endpush

@push('scripts')
<script src="/chatbot/assets/js/chatbot.js"></script>
@endpush
@endsection
