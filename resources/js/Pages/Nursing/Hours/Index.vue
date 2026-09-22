<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface HoursEntry {
    id: number;
    date: string;
    hours: number;
    shift?: string;
    status: string;
    student?: { name: string };
    placement?: { facility?: { name: string } };
}

interface Placement {
    id: number;
    facility?: { name: string };
}

const props = defineProps<{
    hours: {
        data: HoursEntry[];
        current_page: number;
        last_page: number;
        total: number;
    };
    placements: Placement[];
}>();

const facilityFilter = ref(new URLSearchParams(window.location.search).get('placement_id') || '');

const applyFilters = () => {
    router.get(route('nursing.hours.index'), {
        placement_id: facilityFilter.value || undefined,
    }, { preserveState: true, replace: true });
};

const goToPage = (page: number) => {
    router.get(route('nursing.hours.index'), {
        placement_id: facilityFilter.value || undefined,
        page,
    }, { preserveState: true, replace: true });
};

const statusColors: Record<string, string> = {
    draft: 'bg-gray-100 text-gray-800',
    submitted: 'bg-blue-100 text-blue-800',
    approved: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800',
};
</script>

<template>
    <Head title="Clinical Hours" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">Clinical Hours</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
                    <div class="flex gap-4">
                        <select v-model="facilityFilter" @change="applyFilters" class="w-full max-w-xs rounded-lg border-gray-300 shadow-sm text-sm focus:border-brand-blue focus:ring-brand-blue">
                            <option value="">All Facilities</option>
                            <option v-for="p in placements" :key="p.id" :value="p.id">{{ p.facility?.name }}</option>
                        </select>
                    </div>
                </div>

                <!-- Hours Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shift</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="entry in hours.data" :key="entry.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ entry.student?.name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ entry.date }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ entry.hours }}h</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ entry.shift || '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ entry.placement?.facility?.name || '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="[statusColors[entry.status] || 'bg-gray-100 text-gray-800', 'px-2 py-1 text-xs font-medium rounded-full']">
                                        {{ entry.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <Link :href="route('nursing.hours.show', entry.id)" class="text-brand-blue hover:underline">View</Link>
                                </td>
                            </tr>
                            <tr v-if="!hours.data.length">
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    No hours entries found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="hours.last_page > 1" class="mt-4 flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        Page {{ hours.current_page }} of {{ hours.last_page }} ({{ hours.total }} total)
                    </p>
                    <div class="flex gap-2">
                        <button
                            @click="goToPage(hours.current_page - 1)"
                            :disabled="hours.current_page <= 1"
                            class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Previous
                        </button>
                        <button
                            @click="goToPage(hours.current_page + 1)"
                            :disabled="hours.current_page >= hours.last_page"
                            class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
