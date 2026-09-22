<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    assessment: {
        id: number;
        score: number;
        maximum_score: number;
        percentage: number;
        result: string;
        feedback?: string;
        recommendation?: string;
        assessed_at: string;
        student?: { name: string };
        skill?: { name: string };
        assessor?: { name: string };
        rubric?: { name: string };
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
    return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
};
</script>

<template>
    <Head title="Skill Assessment Detail" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.skill-assessments.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">Skill Assessment</h2>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ assessment.skill?.name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">Student: {{ assessment.student?.name }}</p>
                        </div>
                        <span :class="[resultColors[assessment.result] || 'bg-gray-100 text-gray-800', 'px-3 py-1 text-sm font-medium rounded-full']">
                            {{ assessment.result }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-500">Score</p>
                            <p class="text-2xl font-bold text-gray-900">{{ assessment.score }}<span class="text-lg text-gray-400">/{{ assessment.maximum_score }}</span></p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Percentage</p>
                            <p class="text-2xl font-bold text-gray-900">{{ assessment.percentage }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Assessor</p>
                            <p class="font-medium">{{ assessment.assessor?.name || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Rubric</p>
                            <p class="font-medium">{{ assessment.rubric?.name || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Assessed On</p>
                            <p class="font-medium">{{ formatDate(assessment.assessed_at) }}</p>
                        </div>
                    </div>

                    <div v-if="assessment.feedback" class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Feedback</h4>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-700">{{ assessment.feedback }}</p>
                        </div>
                    </div>

                    <div v-if="assessment.recommendation" class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Recommendation</h4>
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-700">{{ assessment.recommendation }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
