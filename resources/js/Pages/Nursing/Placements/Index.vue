<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Placement {
    id: number;
    status: string;
    start_date: string;
    end_date: string;
    required_hours: number;
    completed_hours?: number;
    progress_percentage?: number;
    student?: { name: string };
    facility?: { name: string };
    department?: { name: string };
}

interface Facility {
    id: number;
    name: string;
}

const props = defineProps<{
    placements: {
        data: Placement[];
        current_page: number;
        last_page: number;
        total: number;
    };
    facilities: Facility[];
}>();

const statusFilter = ref(new URLSearchParams(window.location.search).get('status') || '');
const facilityFilter = ref(new URLSearchParams(window.location.search).get('facility_id') || '');

const applyFilters = () => {
    router.get(route('nursing.placements.index'), {
        status: statusFilter.value || undefined,
        facility_id: facilityFilter.value || undefined,
    }, { preserveState: true, replace: true });
};

const statusColors: Record<string, string> = {
    planned: 'bg-purple-100 text-purple-800',
    active: 'bg-green-100 text-green-800',
    completed: 'bg-blue-100 text-blue-800',
    cancelled: 'bg-red-100 text-red-800',
};
</script>

<template>
    <Head title="Clinical Placements" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Clinical Placements</h2>
                <Link :href="route('nursing.placements.create')" class="inline-flex items-center px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Placement
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <select v-model="statusFilter" @change="applyFilters" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                                <option value="">All Statuses</option>
                                <option value="planned">Planned</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div>
                            <select v-model="facilityFilter" @change="applyFilters" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                                <option value="">All Facilities</option>
                                <option v-for="f in facilities" :key="f.id" :value="f.id">{{ f.name }}</option>
                            </select>
                        </div>
                        <div>
                            <button @click="applyFilters" class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Placements Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="placement in placements.data" :key="placement.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ placement.student?.name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ placement.facility?.name }}</div>
                                    <div class="text-xs text-gray-500">{{ placement.department?.name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ placement.start_date }}</div>
                                    <div class="text-xs text-gray-500">to {{ placement.end_date }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ placement.completed_hours || 0 }} / {{ placement.required_hours }}</div>
                                    <div class="w-24 bg-gray-200 rounded-full h-1.5 mt-1">
                                        <div class="bg-brand-blue h-1.5 rounded-full" :style="{ width: (placement.progress_percentage || 0) + '%' }"></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="[statusColors[placement.status], 'px-2 py-1 text-xs font-medium rounded-full']">
                                        {{ placement.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <Link :href="route('nursing.placements.show', placement.id)" class="text-brand-blue hover:underline">View</Link>
                                </td>
                            </tr>
                            <tr v-if="!placements.data.length">
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">No placements found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
