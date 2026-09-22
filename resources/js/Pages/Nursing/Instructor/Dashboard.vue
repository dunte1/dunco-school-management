<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Stats {
    assigned_students?: number;
    pending_reviews?: number;
    total_assessments?: number;
    students_needing_remediation?: number;
}

interface LogbookEntry {
    id: number;
    date: string;
    hours: number;
    status: string;
    student?: { name: string };
    placement?: { facility?: { name: string } };
}

interface Student {
    id: number;
    student?: { name: string };
    facility?: { name: string };
}

defineProps<{
    stats: Stats;
    pending_logbooks: LogbookEntry[];
    my_students: Student[];
}>();

const statusColors: Record<string, string> = {
    draft: 'bg-gray-100 text-gray-800',
    submitted: 'bg-blue-100 text-blue-800',
    under_review: 'bg-yellow-100 text-yellow-800',
    approved: 'bg-green-100 text-green-800',
    returned: 'bg-orange-100 text-orange-800',
    rejected: 'bg-red-100 text-red-800',
};

const statCards = [
    { key: 'assigned_students', label: 'Assigned Students', icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', color: 'blue' },
    { key: 'pending_reviews', label: 'Pending Reviews', icon: 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', color: 'yellow' },
    { key: 'total_assessments', label: 'Total Assessments', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', color: 'green' },
    { key: 'students_needing_remediation', label: 'Need Remediation', icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', color: 'red' },
];

const colorMap: Record<string, { bg: string; icon: string }> = {
    blue: { bg: 'bg-blue-100', icon: 'text-blue-600' },
    yellow: { bg: 'bg-yellow-100', icon: 'text-yellow-600' },
    green: { bg: 'bg-green-100', icon: 'text-green-600' },
    red: { bg: 'bg-red-100', icon: 'text-red-600' },
};
</script>

<template>
    <Head title="Instructor Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">Instructor Dashboard</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div
                        v-for="card in statCards"
                        :key="card.key"
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"
                    >
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">{{ card.label }}</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1">{{ (stats as any)[card.key] || 0 }}</p>
                            </div>
                            <div :class="['w-12 h-12 rounded-lg flex items-center justify-center', colorMap[card.color].bg]">
                                <svg :class="['w-6 h-6', colorMap[card.color].icon]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="card.icon" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Pending Logbook Reviews -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">Pending Logbook Reviews</h3>
                                <Link :href="route('nursing.logbook.pending')" class="text-brand-blue text-sm font-medium hover:underline">
                                    View All
                                </Link>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div v-if="!pending_logbooks.length" class="p-6 text-center text-gray-500">
                                No pending reviews.
                            </div>
                            <div v-for="logbook in pending_logbooks" :key="logbook.id" class="p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ logbook.student?.name }}</p>
                                        <p class="text-sm text-gray-500">{{ logbook.date }} - {{ logbook.hours }}h at {{ logbook.placement?.facility?.name }}</p>
                                    </div>
                                    <span :class="[statusColors[logbook.status], 'px-2 py-1 text-xs font-medium rounded-full']">
                                        {{ logbook.status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- My Students -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800">My Students</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div v-if="!my_students.length" class="p-6 text-center text-gray-500">
                                No students assigned.
                            </div>
                            <div v-for="student in my_students" :key="student.id" class="p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ student.student?.name }}</p>
                                        <p class="text-sm text-gray-500">{{ student.facility?.name }}</p>
                                    </div>
                                    <Link
                                        :href="route('nursing.instructor.student', student.id)"
                                        class="text-brand-blue text-sm font-medium hover:underline"
                                    >
                                        View Profile
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
