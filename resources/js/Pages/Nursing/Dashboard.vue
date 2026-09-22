<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface DashboardStats {
    total_students?: number;
    active_placements?: number;
    total_facilities?: number;
    pending_logbooks?: number;
    total_clinical_hours?: number;
    pending_cpd?: number;
    competent_skills?: number;
    total_skills?: number;
    assigned_students?: number;
    total_assessments?: number;
    students_needing_remediation?: number;
    total_logbooks?: number;
    approved_logbooks?: number;
    submitted_logbooks?: number;
    cpd_hours_this_year?: number;
}

interface LogbookEntry {
    id: number;
    date: string;
    hours: number;
    status: string;
    student?: { name: string };
    placement?: { facility?: { name: string } };
}

interface Placement {
    id: number;
    status: string;
    start_date: string;
    end_date: string;
    student?: { name: string };
    facility?: { name: string };
    instructor?: { first_name: string; last_name: string };
}

const props = defineProps<{
    stats: DashboardStats;
    recent_logbooks?: LogbookEntry[];
    active_placements?: Placement[];
    my_students?: Placement[];
    pending_logbooks?: LogbookEntry[];
    current_placement?: Placement;
    message?: string;
}>();

const statusColors: Record<string, string> = {
    draft: 'bg-gray-100 text-gray-800',
    submitted: 'bg-blue-100 text-blue-800',
    under_review: 'bg-yellow-100 text-yellow-800',
    approved: 'bg-green-100 text-green-800',
    returned: 'bg-orange-100 text-orange-800',
    rejected: 'bg-red-100 text-red-800',
    planned: 'bg-purple-100 text-purple-800',
    active: 'bg-green-100 text-green-800',
    completed: 'bg-blue-100 text-blue-800',
    cancelled: 'bg-red-100 text-red-800',
};
</script>

<template>
    <Head title="Nursing Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Nursing Education & Clinical Management</h2>
                <div class="flex gap-2">
                    <Link :href="route('nursing.facilities.index')" class="inline-flex items-center px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">
                        Facilities
                    </Link>
                    <Link :href="route('nursing.placements.index')" class="inline-flex items-center px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">
                        Placements
                    </Link>
                    <Link :href="route('nursing.logbook.index')" class="inline-flex items-center px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">
                        Logbook
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- No Profile Message -->
                <div v-if="message" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-yellow-800">{{ message }}</p>
                </div>
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Active Placements -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Active Placements</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.active_placements || stats.assigned_students || 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Clinical Hours -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Clinical Hours</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.total_clinical_hours || 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Logbooks -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pending Logbooks</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.pending_logbooks || 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Skills Competency -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Skills Competent</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.competent_skills || 0 }}<span class="text-lg text-gray-400">/{{ stats.total_skills || 0 }}</span></p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Placement (Students) -->
                <div v-if="current_placement" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Current Placement</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Facility</p>
                            <p class="font-medium">{{ current_placement.facility?.name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Period</p>
                            <p class="font-medium">{{ current_placement.start_date }} - {{ current_placement.end_date }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Instructor</p>
                            <p class="font-medium">{{ current_placement.instructor?.first_name }} {{ current_placement.instructor?.last_name }}</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
                    <Link :href="route('nursing.logbook.create')" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:shadow-md transition">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">New Logbook</p>
                    </Link>
                    <Link :href="route('nursing.skills.index')" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:shadow-md transition">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Skills</p>
                    </Link>
                    <Link :href="route('nursing.reference.index')" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:shadow-md transition">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Reference</p>
                    </Link>
                    <Link :href="route('nursing.scenarios.index')" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:shadow-md transition">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Simulator</p>
                    </Link>
                    <Link :href="route('nursing.calculators.index')" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:shadow-md transition">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Calculators</p>
                    </Link>
                    <Link :href="route('nursing.reports.index')" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:shadow-md transition">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Reports</p>
                    </Link>
                </div>

                <!-- Recent Logbooks & Active Placements -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Recent Logbooks -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800">Recent Logbooks</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div v-if="!recent_logbooks?.length && !pending_logbooks?.length" class="p-6 text-center text-gray-500">
                                No logbook entries found.
                            </div>
                            <div v-for="logbook in (pending_logbooks || recent_logbooks || [])" :key="logbook.id" class="p-4 hover:bg-gray-50">
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

                    <!-- Active Placements / My Students -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-800">{{ my_students ? 'My Students' : 'Active Placements' }}</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            <div v-if="!(active_placements || my_students)?.length" class="p-6 text-center text-gray-500">
                                No active placements found.
                            </div>
                            <div v-for="placement in (my_students || active_placements || [])" :key="placement.id" class="p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-800">{{ placement.student?.name }}</p>
                                        <p class="text-sm text-gray-500">{{ placement.facility?.name }} - {{ placement.start_date }} to {{ placement.end_date }}</p>
                                    </div>
                                    <Link :href="route('nursing.placements.show', placement.id)" class="text-brand-blue text-sm font-medium hover:underline">
                                        View
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
