<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    assessments: {
        data: Array<{
            id: number;
            score: number;
            percentage: number;
            result: string;
            assessed_at: string;
            student?: { name: string };
            skill?: { name: string };
            assessor?: { name: string };
        }>;
        current_page: number;
        last_page: number;
        total: number;
    };
}>();

const resultColors: Record<string, string> = {
    pass: 'bg-green-100 text-green-800',
    fail: 'bg-red-100 text-red-800',
    partial: 'bg-yellow-100 text-yellow-800',
    competent: 'bg-green-100 text-green-800',
    not_competent: 'bg-red-100 text-red-800',
    pending: 'bg-gray-100 text-gray-800',
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};
</script>

<template>
    <Head title="Skill Assessments" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Skill Assessments</h2>
                <Link :href="route('nursing.skill-assessments.create')" class="inline-flex items-center px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Assessment
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skill</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Result</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assessor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="assessment in assessments.data" :key="assessment.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ assessment.student?.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ assessment.skill?.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ assessment.score }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="[resultColors[assessment.result] || 'bg-gray-100 text-gray-800', 'px-2 py-1 text-xs font-medium rounded-full']">
                                            {{ assessment.result }} ({{ assessment.percentage }}%)
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ assessment.assessor?.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatDate(assessment.assessed_at) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <Link :href="route('nursing.skill-assessments.show', assessment.id)" class="text-brand-blue text-sm font-medium hover:underline">
                                            View
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="!assessments.data.length">
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">No assessments found.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="assessments.last_page > 1" class="p-4 border-t border-gray-100 flex items-center justify-between text-sm text-gray-600">
                        <span>Page {{ assessments.current_page }} of {{ assessments.last_page }} ({{ assessments.total }} total)</span>
                        <div class="flex gap-2">
                            <Link v-if="assessments.current_page > 1" :href="route('nursing.skill-assessments.index', { page: assessments.current_page - 1 })" class="px-3 py-1 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                Previous
                            </Link>
                            <Link v-if="assessments.current_page < assessments.last_page" :href="route('nursing.skill-assessments.index', { page: assessments.current_page + 1 })" class="px-3 py-1 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
                                Next
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
