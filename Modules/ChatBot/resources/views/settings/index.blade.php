@extends('chatbot::layouts.chatbot')

@section('title', 'ChatBot Settings')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold mb-6">ChatBot Settings</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- OpenAI Configuration -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">OpenAI Configuration</h2>
                
                <div id="ai-status" class="mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        Checking status...
                    </span>
                </div>

                <form id="openai-form">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                        <input type="password" id="api_key" name="api_key" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="sk-..." required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                        <select id="model" name="model" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="gpt-3.5-turbo">GPT-3.5 Turbo</option>
                            <option value="gpt-4">GPT-4</option>
                            <option value="gpt-4-turbo">GPT-4 Turbo</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Tokens</label>
                        <input type="number" id="max_tokens" name="max_tokens" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="1000" min="100" max="4000">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Temperature</label>
                        <input type="number" id="temperature" name="temperature" class="w-full px-3 py-2 border border-gray-300 rounded-md" value="0.7" min="0" max="2" step="0.1">
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Save Configuration
                        </button>
                        <button type="button" id="test-openai" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                            Test Connection
                        </button>
                    </div>
                </form>
            </div>

            <!-- Features Configuration -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Features Configuration</h2>

                <form id="features-form">
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="voice_to_text" name="voice_to_text" class="mr-2" checked>
                            <span class="text-sm font-medium text-gray-700">Voice-to-Text</span>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="multi_language" name="multi_language" class="mr-2" checked>
                            <span class="text-sm font-medium text-gray-700">Multi-language Support</span>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="analytics" name="analytics" class="mr-2" checked>
                            <span class="text-sm font-medium text-gray-700">Analytics</span>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="premium_features" name="premium_features" class="mr-2" checked>
                            <span class="text-sm font-medium text-gray-700">Premium Features</span>
                        </label>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Theme</label>
                        <select id="theme" name="theme" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            <option value="light">Light</option>
                            <option value="dark">Dark</option>
                            <option value="auto">Auto</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Save Features
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadConfig();
    checkAIStatus();

    document.getElementById('openai-form').addEventListener('submit', function(e) {
        e.preventDefault();
        saveOpenAIConfig();
    });

    document.getElementById('features-form').addEventListener('submit', function(e) {
        e.preventDefault();
        saveFeaturesConfig();
    });

    document.getElementById('test-openai').addEventListener('click', function() {
        testOpenAIConnection();
    });
});

async function loadConfig() {
    try {
        const response = await fetch('/chatbot/settings/config');
        const data = await response.json();
        
        if (data.success) {
            const config = data.data.config;
            
            document.getElementById('api_key').value = config.openai.api_key || '';
            document.getElementById('model').value = config.openai.model || 'gpt-3.5-turbo';
            document.getElementById('max_tokens').value = config.openai.max_tokens || 1000;
            document.getElementById('temperature').value = config.openai.temperature || 0.7;
            
            document.getElementById('voice_to_text').checked = config.features.voice_to_text;
            document.getElementById('multi_language').checked = config.features.multi_language;
            document.getElementById('analytics').checked = config.features.analytics;
            document.getElementById('premium_features').checked = config.features.premium_features;
            document.getElementById('theme').value = config.ui.theme;
        }
    } catch (error) {
        console.error('Error loading config:', error);
    }
}

async function checkAIStatus() {
    try {
        const response = await fetch('/chatbot/settings/test-openai');
        const data = await response.json();
        
        const statusElement = document.getElementById('ai-status');
        const aiStatus = data.data.ai_status;
        
        let statusClass = 'bg-red-100 text-red-800';
        let statusText = 'Disconnected';
        
        if (aiStatus.configured) {
            if (aiStatus.status === 'connected') {
                statusClass = 'bg-green-100 text-green-800';
                statusText = 'Connected';
            } else {
                statusClass = 'bg-red-100 text-red-800';
                statusText = 'Error: ' + aiStatus.message;
            }
        }
        
        statusElement.innerHTML = `
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${statusClass}">
                ${statusText}
            </span>
        `;
    } catch (error) {
        console.error('Error checking AI status:', error);
    }
}

async function saveOpenAIConfig() {
    try {
        const formData = new FormData(document.getElementById('openai-form'));
        const response = await fetch('/chatbot/settings/openai', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('OpenAI configuration saved successfully!');
            checkAIStatus();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error saving configuration: ' + error.message);
    }
}

async function saveFeaturesConfig() {
    try {
        const formData = new FormData(document.getElementById('features-form'));
        const response = await fetch('/chatbot/settings/features', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Features configuration saved successfully!');
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error saving features: ' + error.message);
    }
}

async function testOpenAIConnection() {
    try {
        const response = await fetch('/chatbot/settings/test-openai');
        const data = await response.json();
        
        if (data.success) {
            const aiStatus = data.data.ai_status;
            
            if (aiStatus.configured && aiStatus.status === 'connected') {
                alert('OpenAI connection successful!');
            } else {
                alert('OpenAI connection failed: ' + aiStatus.message);
            }
            
            checkAIStatus();
        } else {
            alert('Error testing connection: ' + data.message);
        }
    } catch (error) {
        alert('Error testing connection: ' + error.message);
    }
}
</script>
@endsection 