<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Skill {
    id: number;
    name: string;
    category?: { name: string };
}

interface StudentSkill {
    id: number;
    status: string;
    student?: { name: string };
    skill?: { name: string; category?: { name: string } };
}

interface SummaryEntry {
    student?: { name: string };
    competent: number;
    total: number;
    percentage: number;
}

const props = defineProps<{
    skills: Skill[];
    studentSkills: StudentSkill[];
    summary: SummaryEntry[];
}>();

const statusColors: Record<string, string> = {
    competent: 'bg-green-100 text-green-800',
    in_progress: 'bg-yellow-100 text-yellow-800',
    not_started: 'bg-gray-100 text-gray-600',
    remediation: 'bg-red-100 text-red-800',
};

const getSkillStatus = (studentName: string, skillName: string): string => {
    const found = props.studentSkills.find(
        ss => ss.student?.name === studentName && ss.skill?.name === skillName
    );
    return found?.status || 'not_started';
};

const getStatusColor = (status: string): string => {
    return statusColors[status] || 'bg-gray-100 text-gray-600';
};

const uniqueStudentNames = () => {
    const names = new Set<string>();
    props.summary.forEach(s => { if (s.student?.name) names.add(s.student.name); });
    props.studentSkills.forEach(ss => { if (ss.student?.name) names.add(ss.student.name); });
    return Array.from(names);
};

const uniqueSkillNames = () => {
    return props.skills.map(s => s.name);
};

const groupedSkills = () => {
    const groups: Record<string, Skill[]> = {};
    props.skills.forEach(skill => {
        const cat = skill.category?.name || 'Uncategorized';
        if (!groups[cat]) groups[cat] = [];
        groups[cat].push(skill);
    });
    return groups;
};
</script>

<template>
    <Head title="Skills Competency Report" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="route('nursing.reports.index')" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </Link>
                    <h2 class="text-xl font-semibold text-gray-800">Skills Competency Report</h2>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Overall Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ summary.length }}</p>
                        <p class="text-xs text-gray-500 mt-1">Students</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ skills.length }}</p>
                        <p class="text-xs text-gray-500 mt-1">Total Skills</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                        <p class="text-2xl font-bold text-green-600">
                            {{ summary.reduce((s, e) => s + e.competent, 0) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Total Competent</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                        <p class="text-2xl font-bold text-blue-600">
                            {{ summary.length > 0 ? Math.round(summary.reduce((s, e) => s + e.percentage, 0) / summary.length) : 0 }}%
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Avg Competency</p>
                    </div>
                </div>

                <!-- Summary Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Student Competency Summary</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Competent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Percentage</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-if="!summary.length">
                                    <td colspan="5" class="px-6 py-6 text-center text-gray-500">No competency data available.</td>
                                </tr>
                                <tr v-for="(entry, index) in summary" :key="index" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ entry.student?.name || 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-green-600 font-medium">{{ entry.competent }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ entry.total }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                                <div
                                                    class="h-2 rounded-full transition-all"
                                                    :class="entry.percentage >= 80 ? 'bg-green-500' : entry.percentage >= 60 ? 'bg-yellow-500' : 'bg-red-500'"
                                                    :style="{ width: entry.percentage + '%' }"
                                                ></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700">{{ entry.percentage }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            :class="[
                                                entry.percentage >= 80 ? 'bg-green-100 text-green-800' : entry.percentage >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800',
                                                'px-2 py-1 text-xs font-medium rounded-full'
                                            ]"
                                        >
                                            {{ entry.percentage >= 80 ? 'On Track' : entry.percentage >= 60 ? 'Needs Improvement' : 'At Risk' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Skills Matrix by Category -->
                <div v-if="Object.keys(groupedSkills()).length > 0" class="space-y-6">
                    <h3 class="text-lg font-semibold text-gray-800">Skills Matrix by Category</h3>

                    <div v-for="(catSkills, category) in groupedSkills()" :key="category" class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-4 border-b border-gray-100">
                            <h4 class="font-semibold text-gray-800">{{ category }}</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Skill</th>
                                        <th
                                            v-for="student in uniqueStudentNames()"
                                            :key="student"
                                            class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase max-w-[120px]"
                                        >
                                            {{ student.split(' ')[0] }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <tr v-for="skill in catSkills" :key="skill.id" class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-800 max-w-[200px]">{{ skill.name }}</td>
                                        <td
                                            v-for="student in uniqueStudentNames()"
                                            :key="student"
                                            class="px-3 py-3 text-center"
                                        >
                                            <span
                                                :class="[
                                                    getStatusColor(getSkillStatus(student, skill.name)),
                                                    'inline-block px-2 py-1 text-xs font-medium rounded-full'
                                                ]"
                                            >
                                                {{ getSkillStatus(student, skill.name).replace('_', ' ') }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
