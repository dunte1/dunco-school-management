<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface StudentSkill {
    id: number;
    status: string;
    skill?: { name: string; category?: { name: string } };
    placement?: { facility?: { name: string } };
}

const props = defineProps<{
    studentSkills: StudentSkill[];
    categories: Array<{ id: number; name: string; skills?: Array<{ id: number; name: string }> }>;
    stats: { total: number; competent: number; in_progress: number; remediation: number };
}>();

const statusColors: Record<string, string> = {
    not_started: 'bg-gray-100 text-gray-800',
    learning: 'bg-yellow-100 text-yellow-800',
    observed: 'bg-blue-100 text-blue-800',
    assisted: 'bg-indigo-100 text-indigo-800',
    performed_supervised: 'bg-purple-100 text-purple-800',
    competent: 'bg-green-100 text-green-800',
};

const statusLabels: Record<string, string> = {
    not_started: 'Not Started',
    learning: 'Learning',
    observed: 'Observed',
    assisted: 'Assisted',
    performed_supervised: 'Performed (Supervised)',
    competent: 'Competent',
};

const getSkillStatus = (skillId: number): string => {
    const found = props.studentSkills.find(ss => ss.skill && (ss.skill as any).id === skillId);
    return found?.status || 'not_started';
};

const groupedByCategory = () => {
    return props.categories.map(cat => ({
        ...cat,
        skills_with_status: (cat.skills || []).map(skill => ({
            ...skill,
            status: getSkillStatus(skill.id),
        })),
    }));
};
</script>

<template>
    <Head title="My Skill Progress" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.skills.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">My Skill Progress</h2>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Skills</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.total }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Competent</p>
                                <p class="text-3xl font-bold text-green-600 mt-1">{{ stats.competent }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">In Progress</p>
                                <p class="text-3xl font-bold text-yellow-600 mt-1">{{ stats.in_progress }}</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Remediation</p>
                                <p class="text-3xl font-bold text-red-600 mt-1">{{ stats.remediation }}</p>
                            </div>
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Skills by Category -->
                <div class="space-y-6">
                    <div v-for="cat in groupedByCategory()" :key="cat.id" class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800">{{ cat.name }}</h3>
                            <p class="text-sm text-gray-500">{{ cat.skills_with_status.length }} skills</p>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div v-for="skill in cat.skills_with_status" :key="skill.id" class="p-4 hover:bg-gray-50 flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-800">{{ skill.name }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span :class="[statusColors[skill.status], 'px-3 py-1 text-xs font-medium rounded-full']">
                                        {{ statusLabels[skill.status] }}
                                    </span>
                                    <Link :href="route('nursing.skills.show', skill.id)" class="text-brand-blue text-sm font-medium hover:underline">
                                        View
                                    </Link>
                                </div>
                            </div>
                            <div v-if="!cat.skills_with_status.length" class="p-4 text-center text-sm text-gray-500">
                                No skills in this category.
                            </div>
                        </div>
                    </div>

                    <div v-if="!categories.length" class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                        <p class="text-gray-500">No skill categories available.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
