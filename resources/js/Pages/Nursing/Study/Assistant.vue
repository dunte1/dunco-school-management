<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, nextTick } from 'vue';

interface ChatMessage {
    id: number;
    role: 'user' | 'assistant';
    content: string;
    timestamp: Date;
}

const question = ref('');
const loading = ref(false);
const messages = ref<ChatMessage[]>([]);
const chatContainer = ref<HTMLElement | null>(null);
const error = ref('');

const scrollToBottom = async () => {
    await nextTick();
    if (chatContainer.value) {
        chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
    }
};

const sendMessage = async () => {
    const q = question.value.trim();
    if (!q || loading.value) return;

    error.value = '';

    const userMessage: ChatMessage = {
        id: Date.now(),
        role: 'user',
        content: q,
        timestamp: new Date(),
    };
    messages.value.push(userMessage);
    question.value = '';
    loading.value = true;
    await scrollToBottom();

    try {
        const response = await fetch(route('nursing.study-assistant.ask'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ question: q }),
        });

        const data = await response.json();

        if (!response.ok) {
            error.value = data.message || 'Failed to get a response. Please try again.';
        } else {
            const assistantMessage: ChatMessage = {
                id: Date.now() + 1,
                role: 'assistant',
                content: data.answer || data.response || data.message || 'No response received.',
                timestamp: new Date(),
            };
            messages.value.push(assistantMessage);
        }
    } catch {
        error.value = 'Network error. Please check your connection and try again.';
    } finally {
        loading.value = false;
        await scrollToBottom();
    }
};

const handleKeydown = (e: KeyboardEvent) => {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
};

const clearChat = () => {
    messages.value = [];
    error.value = '';
};

const suggestedQuestions = [
    'What are the steps for wound dressing?',
    'Explain the nursing process in detail.',
    'What are the vital signs normal ranges?',
    'How to calculate IV flow rate?',
    'What is the priority in patient assessment?',
];

const askSuggested = (q: string) => {
    question.value = q;
    sendMessage();
};
</script>

<template>
    <Head title="Study Assistant" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="route('nursing.study.index')" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </Link>
                    <h2 class="text-xl font-semibold text-gray-800">AI Study Assistant</h2>
                </div>
                <button
                    v-if="messages.length > 0"
                    @click="clearChat"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Clear Chat
                </button>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <!-- Welcome / Empty State -->
                <div v-if="messages.length === 0" class="mb-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-brand-blue rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800">Ask me anything about nursing</h3>
                                <p class="text-sm text-gray-500">I can help explain concepts, procedures, and study topics.</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-600 mb-2">Try asking:</p>
                            <div class="flex flex-wrap gap-2">
                                <button
                                    v-for="suggestion in suggestedQuestions"
                                    :key="suggestion"
                                    @click="askSuggested(suggestion)"
                                    class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 hover:bg-brand-blue-light hover:border-brand-blue hover:text-brand-blue transition text-left"
                                >
                                    {{ suggestion }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages -->
                <div
                    v-if="messages.length > 0"
                    ref="chatContainer"
                    class="space-y-4 mb-6 max-h-[500px] overflow-y-auto"
                >
                    <div
                        v-for="msg in messages"
                        :key="msg.id"
                        :class="[
                            'flex',
                            msg.role === 'user' ? 'justify-end' : 'justify-start'
                        ]"
                    >
                        <div
                            :class="[
                                'max-w-[80%] rounded-2xl px-5 py-3',
                                msg.role === 'user'
                                    ? 'bg-brand-blue text-white rounded-br-md'
                                    : 'bg-white border border-gray-100 shadow-sm text-gray-800 rounded-bl-md'
                            ]"
                        >
                            <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ msg.content }}</p>
                            <p
                                :class="[
                                    'text-xs mt-1',
                                    msg.role === 'user' ? 'text-white/60' : 'text-gray-400'
                                ]"
                            >
                                {{ msg.timestamp.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) }}
                            </p>
                        </div>
                    </div>

                    <!-- Loading Indicator -->
                    <div v-if="loading" class="flex justify-start">
                        <div class="bg-white border border-gray-100 shadow-sm rounded-2xl rounded-bl-md px-5 py-3">
                            <div class="flex items-center gap-2">
                                <div class="flex gap-1">
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                                    <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                                </div>
                                <span class="text-sm text-gray-400">Thinking...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Error Message -->
                <div v-if="error" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-sm text-red-600">{{ error }}</p>
                </div>

                <!-- Input Area -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-end gap-3">
                        <textarea
                            v-model="question"
                            @keydown="handleKeydown"
                            placeholder="Ask a nursing question..."
                            rows="2"
                            class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm resize-none"
                            :disabled="loading"
                        ></textarea>
                        <button
                            @click="sendMessage"
                            :disabled="!question.trim() || loading"
                            class="px-4 py-3 bg-brand-blue text-white rounded-lg hover:bg-brand-blue-dark transition disabled:opacity-50 disabled:cursor-not-allowed flex-shrink-0"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Press Enter to send. This is an AI assistant for educational purposes only.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
