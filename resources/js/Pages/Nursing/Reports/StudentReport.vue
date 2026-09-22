<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Student {
    id: number;
    name: string;
    student_id: string;
}

interface Placement {
    facility?: { name: string };
    department?: { name: string };
    ward?: { name: string };
}

interface LogbookEntry {
    id: number;
    date: string;
    hours: number;
    status: string;
    placement?: { facility?: { name: string } };
}

interface StudentSkill {
    id: number;
    status: string;
    skill?: { name: string; category?: { name: string } };
}

interface HourEntry {
    id: number;
    date: string;
    hours: number;
    status: string;
}

interface Assessment {
    id: number;
    score: number;
    percentage: number;
    result: string;
    skill?: { name: string };
}

interface Stats {
    total_logbooks: number;
    approved_logbooks: number;
    total_hours: number;
    competent_skills: number;
    total_skills: number;
    average_score: number;
}

const props = defineProps<{
    student: Student;
    placement?: Placement;
    logbooks: LogbookEntry[];
    skills: StudentSkill[];
    hours: HourEntry[];
    assessments: Assessment[];
    stats: Stats;
}>();

const statusColors: Record<string, string> = {
    draft: 'bg-gray-100 text-gray-800',
    submitted: 'bg-blue-100 text-blue-800',
    under_review: 'bg-yellow-100 text-yellow-800',
    approved: 'bg-green-100 text-green-800',
    returned: 'bg-orange-100 text-orange-800',
    rejected: 'bg-red-100 text-red-800',
    competent: 'bg-green-100 text-green-800',
    in_progress: 'bg-yellow-100 text-yellow-800',
    not_started: 'bg-gray-100 text-gray-800',
    remediation: 'bg-red-100 text-red-800',
    pass: 'bg-green-100 text-green-800',
    fail: 'bg-red-100 text-red-800',
    borderline: 'bg-yellow-100 text-yellow-800',
};

const competencyRate = () => {
    if (props.stats.total_skills === 0) return 0;
    return Math.round((props.stats.competent_skills / props.stats.total_skills) * 100);
};

const approvalRate = () => {
    if (props.stats.total_logbooks === 0) return 0;
    return Math.round((props.stats.approved_logbooks / props.stats.total_logbooks) * 100);
};
</script>

<template>
    <Head :title="`Report - ${student.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="route('nursing.reports.index')" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </Link>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ student.name }}</h2>
                        <p class="text-sm text-gray-500">Student ID: {{ student.student_id }}</p>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Placement Info -->
                <div v-if="placement" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Current Placement</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs text-gray-400">Facility</p>
                            <p class="font-medium text-gray-800">{{ placement.facility?.name || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Department</p>
                            <p class="font-medium text-gray-800">{{ placement.department?.name || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400">Ward</p>
                            <p class="font-medium text-gray-800">{{ placement.ward?.name || 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ stats.total_logbooks }}</p>
                        <p class="text-xs text-gray-500 mt-1">Total Logbooks</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
                        <p class="text-2xl font-bold text-green-600">{{ stats.approved_logbooks }}</p>
                        <p class="text-xs text-gray-500 mt-1">Approved</p>
                        <p class="text-xs text-gray-400">{{ approvalRate() }}% rate</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ stats.total_hours }}</p>
                        <p class="text-xs text-gray-500 mt-1">Clinical Hours</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
                        <p class="text-2xl font-bold text-purple-600">{{ stats.competent_skills }}<span class="text-lg text-gray-400">/{{ stats.total_skills }}</span></p>
                        <p class="text-xs text-gray-500 mt-1">Competent Skills</p>
                        <p class="text-xs text-gray-400">{{ competencyRate() }}%</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
                        <p class="text-2xl font-bold text-indigo-600">{{ stats.average_score }}%</p>
                        <p class="text-xs text-gray-500 mt-1">Avg Score</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center">
                        <p class="text-2xl font-bold" :class="competencyRate() >= 80 ? 'text-green-600' : competencyRate() >= 60 ? 'text-yellow-600' : 'text-red-600'">
                            {{ competencyRate() >= 80 ? 'Good' : competencyRate() >= 60 ? 'Fair' : 'At Risk' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Overall Status</p>
                    </div>
                </div>

                <!-- Skills Competency -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Skills Competency</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div v-if="!skills.length" class="p-6 text-center text-gray-500">
                            No skills recorded yet.
                        </div>
                        <div v-for="studentSkill in skills" :key="studentSkill.id" class="p-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-800">{{ studentSkill.skill?.name }}</p>
                                    <p class="text-xs text-gray-500">{{ studentSkill.skill?.category?.name }}</p>
                                </div>
                                <span :class="[statusColors[studentSkill.status] || 'bg-gray-100 text-gray-800', 'px-2 py-1 text-xs font-medium rounded-full']">
                                    {{ studentSkill.status?.replace('_', ' ') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assessments -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Assessments</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div v-if="!assessments.length" class="p-6 text-center text-gray-500">
                            No assessments recorded yet.
                        </div>
                        <div v-for="assessment in assessments" :key="assessment.id" class="p-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-800">{{ assessment.skill?.name }}</p>
                                    <p class="text-xs text-gray-500">Score: {{ assessment.score }} ({{ assessment.percentage }}%)</p>
                                </div>
                                <span :class="[statusColors[assessment.result] || 'bg-gray-100 text-gray-800', 'px-2 py-1 text-xs font-medium rounded-full capitalize']">
                                    {{ assessment.result }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logbook Entries -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Logbook Entries</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Facility</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-if="!logbooks.length">
                                    <td colspan="4" class="px-6 py-6 text-center text-gray-500">No logbook entries found.</td>
                                </tr>
                                <tr v-for="logbook in logbooks" :key="logbook.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ logbook.date }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ logbook.hours }}h</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ logbook.placement?.facility?.name || 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <span :class="[statusColors[logbook.status], 'px-2 py-1 text-xs font-medium rounded-full capitalize']">
                                            {{ logbook.status?.replace('_', ' ') }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Clinical Hours -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Clinical Hours Log</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-if="!hours.length">
                                    <td colspan="3" class="px-6 py-6 text-center text-gray-500">No hours logged yet.</td>
                                </tr>
                                <tr v-for="hour in hours" :key="hour.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ hour.date }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ hour.hours }}h</td>
                                    <td class="px-6 py-4">
                                        <span :class="[statusColors[hour.status], 'px-2 py-1 text-xs font-medium rounded-full capitalize']">
                                            {{ hour.status?.replace('_', ' ') }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
