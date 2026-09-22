<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface LogbookEntry {
    id: number;
    date: string;
    hours: number;
    status: string;
    activity?: string;
    shift?: string;
    student?: { name: string };
    placement?: { facility?: { name: string } };
}

const props = defineProps<{
    logbooks: {
        data: LogbookEntry[];
        current_page: number;
        last_page: number;
        total: number;
    };
}>();

const statusFilter = ref(new URLSearchParams(window.location.search).get('status') || '');

const applyFilters = () => {
    router.get(route('nursing.logbook.index'), {
        status: statusFilter.value || undefined,
    }, { preserveState: true, replace: true });
};

const statusColors: Record<string, string> = {
    draft: 'bg-gray-100 text-gray-800',
    submitted: 'bg-blue-100 text-blue-800',
    under_review: 'bg-yellow-100 text-yellow-800',
    approved: 'bg-green-100 text-green-800',
    returned: 'bg-orange-100 text-orange-800',
    rejected: 'bg-red-100 text-red-800',
};
</script>

<template>
    <Head title="Clinical Logbook" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Clinical Logbook</h2>
                <Link :href="route('nursing.logbook.create')" class="inline-flex items-center px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Entry
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
                    <div class="flex gap-4">
                        <select v-model="statusFilter" @change="applyFilters" class="rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                            <option value="">All Statuses</option>
                            <option value="draft">Draft</option>
                            <option value="submitted">Submitted</option>
                            <option value="under_review">Under Review</option>
                            <option value="approved">Approved</option>
                            <option value="returned">Returned</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>

                <!-- Logbook Entries -->
                <div class="space-y-4">
                    <div v-for="logbook in logbooks.data" :key="logbook.id" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ logbook.student?.name }}</h3>
                                    <span :class="[statusColors[logbook.status], 'px-2 py-1 text-xs font-medium rounded-full']">
                                        {{ logbook.status }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                                    <div>
                                        <span class="text-gray-500">Date:</span>
                                        <span class="ml-1 font-medium">{{ logbook.date }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Hours:</span>
                                        <span class="ml-1 font-medium">{{ logbook.hours }}h</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Facility:</span>
                                        <span class="ml-1 font-medium">{{ logbook.placement?.facility?.name }}</span>
                                    </div>
                                    <div v-if="logbook.shift">
                                        <span class="text-gray-500">Shift:</span>
                                        <span class="ml-1 font-medium">{{ logbook.shift }}</span>
                                    </div>
                                </div>
                                <p v-if="logbook.activity" class="mt-2 text-sm text-gray-600 line-clamp-2">{{ logbook.activity }}</p>
                            </div>
                            <Link :href="route('nursing.logbook.show', logbook.id)" class="ml-4 text-brand-blue text-sm font-medium hover:underline whitespace-nowrap">
                                View Details
                            </Link>
                        </div>
                    </div>

                    <div v-if="!logbooks.data.length" class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500">No logbook entries found.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
