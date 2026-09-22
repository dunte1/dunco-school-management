<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps<{
    logbooks: { data: Array<{ id: number; date: string; hours: number; status: string; submitted_at?: string; student?: { name: string }; placement?: { facility?: { name: string } } }>; last_page: number; current_page: number; total: number };
}>();

const statusColors: Record<string, string> = { submitted: 'bg-blue-100 text-blue-800', under_review: 'bg-yellow-100 text-yellow-800' };
</script>

<template>
    <Head title="Pending Reviews" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">Pending Logbook Reviews</h2>
        </template>
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Facility</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="log in logbooks.data" :key="log.id" class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ log.student?.name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ log.date }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ log.hours }}h</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ log.placement?.facility?.name }}</td>
                                <td class="px-6 py-4"><span :class="[statusColors[log.status], 'px-2 py-1 text-xs font-medium rounded-full']">{{ log.status }}</span></td>
                                <td class="px-6 py-4 text-sm"><Link :href="route('nursing.logbook.show', log.id)" class="text-brand-blue hover:underline">Review</Link></td>
                            </tr>
                            <tr v-if="!logbooks.data.length"><td colspan="6" class="px-6 py-12 text-center text-gray-500">No pending reviews.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
