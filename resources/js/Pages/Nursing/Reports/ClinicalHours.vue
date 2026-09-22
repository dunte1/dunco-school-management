<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface HourEntry {
    id: number;
    date: string;
    hours: number;
    shift?: string;
    student?: { name: string };
    placement?: { facility?: { name: string } };
}

interface SummaryEntry {
    student?: { name: string };
    total_hours: number;
    days: number;
}

const props = defineProps<{
    hours: HourEntry[];
    summary: SummaryEntry[];
}>();

const totalHours = () => props.summary.reduce((sum, s) => sum + s.total_hours, 0);
const totalDays = () => props.summary.reduce((sum, s) => sum + s.days, 0);
const avgHoursPerDay = () => {
    const days = totalDays();
    return days > 0 ? (totalHours() / days).toFixed(1) : '0';
};

const shiftColors: Record<string, string> = {
    morning: 'bg-green-100 text-green-800',
    afternoon: 'bg-blue-100 text-blue-800',
    night: 'bg-purple-100 text-purple-800',
    day: 'bg-green-100 text-green-800',
    evening: 'bg-blue-100 text-blue-800',
};
</script>

<template>
    <Head title="Clinical Hours Report" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="route('nursing.reports.index')" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </Link>
                    <h2 class="text-xl font-semibold text-gray-800">Clinical Hours Report</h2>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Summary Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                        <p class="text-2xl font-bold text-gray-900">{{ summary.length }}</p>
                        <p class="text-xs text-gray-500 mt-1">Students</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ totalHours() }}</p>
                        <p class="text-xs text-gray-500 mt-1">Total Hours</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                        <p class="text-2xl font-bold text-green-600">{{ totalDays() }}</p>
                        <p class="text-xs text-gray-500 mt-1">Total Days</p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 text-center">
                        <p class="text-2xl font-bold text-purple-600">{{ avgHoursPerDay() }}</p>
                        <p class="text-xs text-gray-500 mt-1">Avg Hours/Day</p>
                    </div>
                </div>

                <!-- Summary Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Student Summary</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Hours</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days Present</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg Hours/Day</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-if="!summary.length">
                                    <td colspan="4" class="px-6 py-6 text-center text-gray-500">No summary data available.</td>
                                </tr>
                                <tr v-for="(entry, index) in summary" :key="index" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ entry.student?.name || 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ entry.total_hours }}h</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ entry.days }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800">
                                        {{ entry.days > 0 ? (entry.total_hours / entry.days).toFixed(1) : '0' }}h
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Detailed Hours -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-800">Detailed Hours Log</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Facility</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Shift</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr v-if="!hours.length">
                                    <td colspan="5" class="px-6 py-6 text-center text-gray-500">No hours logged yet.</td>
                                </tr>
                                <tr v-for="entry in hours" :key="entry.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-800">{{ entry.date }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ entry.student?.name || 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ entry.placement?.facility?.name || 'N/A' }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            v-if="entry.shift"
                                            :class="[shiftColors[entry.shift.toLowerCase()] || 'bg-gray-100 text-gray-800', 'px-2 py-1 text-xs font-medium rounded-full capitalize']"
                                        >
                                            {{ entry.shift }}
                                        </span>
                                        <span v-else class="text-gray-400 text-xs">-</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800">{{ entry.hours }}h</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
