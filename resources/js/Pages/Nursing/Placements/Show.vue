<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    placement: {
        id: number;
        status: string;
        start_date: string;
        end_date: string;
        required_hours: number;
        notes?: string;
        student?: { name: string; student_id: string };
        facility?: { name: string; phone?: string; address?: string };
        department?: { name: string };
        ward?: { name: string };
        instructor?: { first_name: string; last_name: string; email?: string };
        completed_hours?: number;
        remaining_hours?: number;
        progress_percentage?: number;
    };
    completedHours: number;
    remainingHours: number;
    progressPercentage: number;
}>();

const statusColors: Record<string, string> = {
    planned: 'bg-purple-100 text-purple-800',
    active: 'bg-green-100 text-green-800',
    completed: 'bg-blue-100 text-blue-800',
    cancelled: 'bg-red-100 text-red-800',
};
</script>

<template>
    <Head :title="'Placement - ' + (placement.student?.name || '')" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.placements.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">Placement Details</h2>
            </div>
        </template>
        <div class="py-6">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ placement.student?.name }}</h3>
                            <p class="text-sm text-gray-500">{{ placement.student?.student_id }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span :class="[statusColors[placement.status], 'px-3 py-1 text-sm font-medium rounded-full']">{{ placement.status }}</span>
                            <Link :href="route('nursing.placements.edit', placement.id)" class="px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">Edit</Link>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-500">Facility</p>
                            <p class="font-medium">{{ placement.facility?.name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Department</p>
                            <p class="font-medium">{{ placement.department?.name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Ward</p>
                            <p class="font-medium">{{ placement.ward?.name || 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Instructor</p>
                            <p class="font-medium">{{ placement.instructor?.first_name }} {{ placement.instructor?.last_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Period</p>
                            <p class="font-medium">{{ placement.start_date }} to {{ placement.end_date }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Required Hours</p>
                            <p class="font-medium">{{ placement.required_hours }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Clinical Hours Progress</span>
                            <span class="text-sm text-gray-600">{{ completedHours }} / {{ placement.required_hours }} ({{ progressPercentage }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-brand-blue h-3 rounded-full transition-all duration-300" :style="{ width: progressPercentage + '%' }"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ remainingHours }} hours remaining</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
