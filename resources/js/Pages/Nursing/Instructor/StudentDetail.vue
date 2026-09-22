<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Student {
    id: number;
    name: string;
    student_id: string;
}

interface Placement {
    id: number;
    start_date: string;
    end_date: string;
    required_hours: number;
    facility?: { name: string };
    department?: { name: string };
}

interface LogbookEntry {
    id: number;
    date: string;
    hours: number;
    status: string;
    activity?: string;
}

interface SkillEntry {
    id: number;
    status: string;
    skill?: { name: string; category?: { name: string } };
}

interface HoursEntry {
    id: number;
    date: string;
    hours: number;
    status: string;
    shift?: string;
}

const props = defineProps<{
    student: Student;
    placement: Placement;
    logbooks: LogbookEntry[];
    skills: SkillEntry[];
    hours: HoursEntry[];
    completedHours: number;
}>();

const progressPercentage = computed(() => {
    if (!props.placement.required_hours) return 0;
    return Math.min(100, Math.round((props.completedHours / props.placement.required_hours) * 100));
});

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
};

const skillStats = computed(() => {
    const total = props.skills.length;
    const competent = props.skills.filter(s => s.status === 'competent').length;
    const inProgress = props.skills.filter(s => s.status === 'in_progress').length;
    const notStarted = total - competent - inProgress;
    return { total, competent, inProgress, notStarted };
});
</script>

<template>
    <Head :title="`Student: ${student.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.instructor.students')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">{{ student.name }}</h2>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Student Info & Placement -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Student Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Student Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Name</p>
                                <p class="font-medium text-gray-800">{{ student.name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Student ID</p>
                                <p class="font-medium text-gray-800">{{ student.student_id }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Placement Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Placement Details</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500">Facility</p>
                                <p class="font-medium text-gray-800">{{ placement.facility?.name || 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Department</p>
                                <p class="font-medium text-gray-800">{{ placement.department?.name || 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Period</p>
                                <p class="font-medium text-gray-800">{{ placement.start_date }} to {{ placement.end_date }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Hours Progress</p>
                                <p class="font-medium text-gray-800 mb-2">{{ completedHours }} / {{ placement.required_hours }}h</p>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div
                                        class="bg-brand-blue h-2.5 rounded-full transition-all duration-300"
                                        :style="{ width: progressPercentage + '%' }"
                                    ></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ progressPercentage }}% complete</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Skill Progress -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Skill Progress</h3>
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="bg-green-50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-green-600">{{ skillStats.competent }}</p>
                            <p class="text-xs text-green-700">Competent</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-yellow-600">{{ skillStats.inProgress }}</p>
                            <p class="text-xs text-yellow-700">In Progress</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-gray-600">{{ skillStats.notStarted }}</p>
                            <p class="text-xs text-gray-700">Not Started</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Skill</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="skill in skills" :key="skill.id" class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-600">{{ skill.skill?.category?.name }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ skill.skill?.name }}</td>
                                    <td class="px-4 py-3">
                                        <span :class="[statusColors[skill.status] || 'bg-gray-100 text-gray-800', 'px-2 py-1 text-xs font-medium rounded-full']">
                                            {{ skill.status?.replace('_', ' ') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="!skills.length">
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500 text-sm">No skills tracked.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Logbooks -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Logbooks</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div v-if="!logbooks.length" class="p-6 text-center text-gray-500">No logbook entries.</div>
                            <div v-for="logbook in logbooks" :key="logbook.id" class="p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ logbook.date }} - {{ logbook.hours }}h</p>
                                        <p v-if="logbook.activity" class="text-xs text-gray-500 line-clamp-1">{{ logbook.activity }}</p>
                                    </div>
                                    <span :class="[statusColors[logbook.status], 'px-2 py-1 text-xs font-medium rounded-full']">
                                        {{ logbook.status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hours Log -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800">Hours Log</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div v-if="!hours.length" class="p-6 text-center text-gray-500">No hours logged.</div>
                            <div v-for="hour in hours" :key="hour.id" class="p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">{{ hour.date }} - {{ hour.hours }}h</p>
                                        <p v-if="hour.shift" class="text-xs text-gray-500">{{ hour.shift }}</p>
                                    </div>
                                    <span :class="[statusColors[hour.status], 'px-2 py-1 text-xs font-medium rounded-full']">
                                        {{ hour.status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
