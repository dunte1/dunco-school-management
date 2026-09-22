<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';

interface CpdActivity {
    id: number;
    activity_name: string;
    provider?: string;
    type: string;
    activity_date: string;
    hours: number;
    status: string;
    notes?: string;
}

const props = defineProps<{
    activities: {
        data: CpdActivity[];
        current_page: number;
        last_page: number;
        total: number;
    };
    totalHours: number;
    targetHours: number;
}>();

const progressPercentage = computed(() => {
    if (!props.targetHours) return 0;
    return Math.min(100, Math.round((props.totalHours / props.targetHours) * 100));
});

const statusColors: Record<string, string> = {
    pending: 'bg-yellow-100 text-yellow-800',
    approved: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800',
};

const typeLabels: Record<string, string> = {
    workshop: 'Workshop',
    conference: 'Conference',
    seminar: 'Seminar',
    online: 'Online Course',
    certification: 'Certification',
    other: 'Other',
};

const goToPage = (page: number) => {
    router.get(route('nursing.cpd.index'), { page }, { preserveState: true, replace: true });
};
</script>

<template>
    <Head title="CPD Activities" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Continuing Professional Development</h2>
                <Link
                    :href="route('nursing.cpd.create')"
                    class="inline-flex items-center px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Log Activity
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Progress Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-semibold text-gray-800">Hours Progress</h3>
                        <span class="text-sm text-gray-500">{{ totalHours }} / {{ targetHours }} hours</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div
                            class="h-3 rounded-full transition-all duration-300"
                            :class="progressPercentage >= 100 ? 'bg-green-500' : 'bg-brand-blue'"
                            :style="{ width: progressPercentage + '%' }"
                        ></div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">{{ progressPercentage }}% of target completed</p>
                </div>

                <!-- Activities Table -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="activity in activities.data" :key="activity.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ activity.activity_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ typeLabels[activity.type] || activity.type }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ activity.provider || '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ activity.activity_date }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ activity.hours }}h</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="[statusColors[activity.status] || 'bg-gray-100 text-gray-800', 'px-2 py-1 text-xs font-medium rounded-full']">
                                        {{ activity.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <Link :href="route('nursing.cpd.show', activity.id)" class="text-brand-blue hover:underline">
                                        View
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="!activities.data.length">
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    No CPD activities found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="activities.last_page > 1" class="mt-4 flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        Showing page {{ activities.current_page }} of {{ activities.last_page }} ({{ activities.total }} total)
                    </p>
                    <div class="flex gap-2">
                        <button
                            @click="goToPage(activities.current_page - 1)"
                            :disabled="activities.current_page <= 1"
                            class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Previous
                        </button>
                        <button
                            @click="goToPage(activities.current_page + 1)"
                            :disabled="activities.current_page >= activities.last_page"
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
