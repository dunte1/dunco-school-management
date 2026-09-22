<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    scenarios: {
        data: Array<{
            id: number;
            title: string;
            description?: string;
            difficulty: string;
            category: string;
            questions_count?: number;
            author?: { name: string };
        }>;
        current_page: number;
        last_page: number;
        total: number;
    };
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
    <Head title="Clinical Scenarios" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Clinical Scenarios</h2>
                <Link :href="route('nursing.scenarios.create')" class="inline-flex items-center px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Scenario
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="scenario in scenarios.data" :key="scenario.id" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                        <div class="mb-3 flex items-center gap-2">
                            <span :class="[difficultyColors[scenario.difficulty] || 'bg-gray-100 text-gray-800', 'px-2 py-1 text-xs font-medium rounded-full']">
                                {{ scenario.difficulty }}
                            </span>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">{{ scenario.category }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ scenario.title }}</h3>
                        <p v-if="scenario.description" class="text-sm text-gray-600 line-clamp-3 mb-4">{{ scenario.description }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span v-if="scenario.author">By {{ scenario.author.name }}</span>
                            <span v-if="scenario.questions_count">{{ scenario.questions_count }} questions</span>
                        </div>
                        <Link :href="route('nursing.scenarios.show', scenario.id)" class="block text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                            View Details
                        </Link>
                    </div>
                </div>

                <div v-if="!scenarios.data.length" class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <p class="text-gray-500">No clinical scenarios found.</p>
                </div>

                <div v-if="scenarios.last_page > 1" class="mt-6 flex items-center justify-between text-sm text-gray-600">
                    <span>Page {{ scenarios.current_page }} of {{ scenarios.last_page }} ({{ scenarios.total }} total)</span>
                    <div class="flex gap-2">
                        <Link v-if="scenarios.current_page > 1" :href="route('nursing.scenarios.index', { page: scenarios.current_page - 1 })" class="px-3 py-1 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                            Previous
                        </Link>
                        <Link v-if="scenarios.current_page < scenarios.last_page" :href="route('nursing.scenarios.index', { page: scenarios.current_page + 1 })" class="px-3 py-1 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                            Next
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
