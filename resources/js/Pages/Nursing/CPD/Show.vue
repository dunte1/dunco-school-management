<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface CpdActivity {
    id: number;
    activity_name: string;
    provider?: string;
    type: string;
    activity_date: string;
    hours: number;
    status: string;
    certificate_path?: string;
    notes?: string;
    user?: { name: string };
    approvedBy?: { name: string };
    approved_at?: string;
}

defineProps<{
    activity: CpdActivity;
}>();

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
</script>

<template>
    <Head :title="activity.activity_name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.cpd.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">{{ activity.activity_name }}</h2>
                <span :class="[statusColors[activity.status] || 'bg-gray-100 text-gray-800', 'px-2 py-1 text-xs font-medium rounded-full']">
                    {{ activity.status }}
                </span>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Activity Name</p>
                            <p class="font-medium text-gray-800">{{ activity.activity_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Provider</p>
                            <p class="font-medium text-gray-800">{{ activity.provider || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Type</p>
                            <p class="font-medium text-gray-800">{{ typeLabels[activity.type] || activity.type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date</p>
                            <p class="font-medium text-gray-800">{{ activity.activity_date }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Hours</p>
                            <p class="font-medium text-gray-800">{{ activity.hours }}h</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Logged By</p>
                            <p class="font-medium text-gray-800">{{ activity.user?.name || 'N/A' }}</p>
                        </div>
                    </div>

                    <div v-if="activity.notes" class="border-t border-gray-100 pt-4">
                        <p class="text-sm text-gray-500 mb-1">Notes</p>
                        <p class="text-gray-700 text-sm whitespace-pre-wrap">{{ activity.notes }}</p>
                    </div>

                    <div v-if="activity.certificate_path" class="border-t border-gray-100 pt-4">
                        <p class="text-sm text-gray-500 mb-2">Certificate</p>
                        <a
                            :href="`/storage/${activity.certificate_path}`"
                            target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            View Certificate
                        </a>
                    </div>

                    <div v-if="activity.approved_at" class="border-t border-gray-100 pt-4">
                        <p class="text-sm text-gray-500 mb-1">Approval Details</p>
                        <p class="text-sm text-gray-700">
                            Approved by <span class="font-medium">{{ activity.approvedBy?.name }}</span>
                            on <span class="font-medium">{{ activity.approved_at }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
