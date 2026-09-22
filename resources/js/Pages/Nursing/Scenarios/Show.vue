<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    scenario: {
        id: number;
        title: string;
        description?: string;
        difficulty: string;
        category: string;
        patient_information?: string;
        observations?: string;
        learning_objectives?: string;
        questions?: Array<{ id: number; question: string; choices: string[] }>;
        author?: { name: string };
    };
    questionCount: number;
    previousAttempts: number;
}>();

const difficultyColors: Record<string, string> = {
    beginner: 'bg-green-100 text-green-800',
    intermediate: 'bg-yellow-100 text-yellow-800',
    advanced: 'bg-red-100 text-red-800',
    easy: 'bg-green-100 text-green-800',
    medium: 'bg-yellow-100 text-yellow-800',
    hard: 'bg-red-100 text-red-800',
};
</script>

<template>
    <Head :title="'Scenario - ' + scenario.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.scenarios.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">{{ scenario.title }}</h2>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <span :class="[difficultyColors[scenario.difficulty] || 'bg-gray-100 text-gray-800', 'px-3 py-1 text-sm font-medium rounded-full']">
                                {{ scenario.difficulty }}
                            </span>
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-indigo-100 text-indigo-800">{{ scenario.category }}</span>
                        </div>
                        <div class="flex gap-2">
                            <Link :href="route('nursing.scenarios.edit', scenario.id)" class="px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">Edit</Link>
                            <Link :href="route('nursing.scenarios.attempt', scenario.id)" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition">Start Attempt</Link>
                        </div>
                    </div>

                    <p v-if="scenario.description" class="text-sm text-gray-700 mb-6">{{ scenario.description }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-sm">
                        <div>
                            <p class="text-gray-500">Questions</p>
                            <p class="font-medium">{{ questionCount }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Previous Attempts</p>
                            <p class="font-medium">{{ previousAttempts }}</p>
                        </div>
                        <div v-if="scenario.author">
                            <p class="text-gray-500">Author</p>
                            <p class="font-medium">{{ scenario.author.name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Patient Information -->
                <div v-if="scenario.patient_information" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Patient Information</h4>
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800 whitespace-pre-line">{{ scenario.patient_information }}</p>
                    </div>
                </div>

                <!-- Observations -->
                <div v-if="scenario.observations" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Observations</h4>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ scenario.observations }}</p>
                </div>

                <!-- Learning Objectives -->
                <div v-if="scenario.learning_objectives" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Learning Objectives</h4>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ scenario.learning_objectives }}</p>
                </div>

                <!-- Questions Preview -->
                <div v-if="scenario.questions?.length" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3">Questions ({{ scenario.questions.length }})</h4>
                    <div class="space-y-4">
                        <div v-for="(q, i) in scenario.questions" :key="q.id" class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-800 mb-2">{{ i + 1 }}. {{ q.question }}</p>
                            <ul class="space-y-1">
                                <li v-for="(choice, ci) in q.choices" :key="ci" class="text-sm text-gray-600 pl-4">
                                    {{ String.fromCharCode(65 + ci) }}. {{ choice }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
