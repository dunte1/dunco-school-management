<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    facility: {
        id: number;
        name: string;
        code?: string;
        type: string;
        address?: string;
        city?: string;
        country?: string;
        phone?: string;
        email?: string;
        contact_person?: string;
        contact_person_phone?: string;
        notes?: string;
        is_active: boolean;
        departments?: Array<{ id: number; name: string; wards_count?: number }>;
        wards?: Array<{ id: number; name: string; department?: { name: string } }>;
    };
}>();

const typeLabels: Record<string, string> = { hospital: 'Hospital', health_centre: 'Health Centre', clinic: 'Clinic', other: 'Other' };
</script>

<template>
    <Head :title="facility.name" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.facilities.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">{{ facility.name }}</h2>
            </div>
        </template>
        <div class="py-6">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">{{ typeLabels[facility.type] || facility.type }}</span>
                            <span :class="[facility.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800', 'ml-2 px-2 py-1 text-xs font-medium rounded-full']">
                                {{ facility.is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <Link :href="route('nursing.facilities.edit', facility.id)" class="px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">Edit</Link>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div v-if="facility.code"><span class="text-gray-500">Code:</span> <span class="font-medium">{{ facility.code }}</span></div>
                        <div v-if="facility.phone"><span class="text-gray-500">Phone:</span> <span class="font-medium">{{ facility.phone }}</span></div>
                        <div v-if="facility.email"><span class="text-gray-500">Email:</span> <span class="font-medium">{{ facility.email }}</span></div>
                        <div v-if="facility.contact_person"><span class="text-gray-500">Contact:</span> <span class="font-medium">{{ facility.contact_person }}</span></div>
                        <div v-if="facility.address" class="md:col-span-2"><span class="text-gray-500">Address:</span> <span class="font-medium">{{ facility.address }}{{ facility.city ? ', ' + facility.city : '' }}{{ facility.country ? ', ' + facility.country : '' }}</span></div>
                        <div v-if="facility.notes" class="md:col-span-2"><span class="text-gray-500">Notes:</span> <span class="font-medium">{{ facility.notes }}</span></div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Departments ({{ facility.departments?.length || 0 }})</h3>
                    <div v-if="facility.departments?.length" class="space-y-2">
                        <div v-for="dept in facility.departments" :key="dept.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="font-medium text-gray-800">{{ dept.name }}</span>
                            <span class="text-sm text-gray-500">{{ dept.wards_count || 0 }} wards</span>
                        </div>
                    </div>
                    <p v-else class="text-gray-500 text-sm">No departments configured.</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Wards ({{ facility.wards?.length || 0 }})</h3>
                    <div v-if="facility.wards?.length" class="space-y-2">
                        <div v-for="ward in facility.wards" :key="ward.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="font-medium text-gray-800">{{ ward.name }}</span>
                            <span class="text-sm text-gray-500">{{ ward.department?.name }}</span>
                        </div>
                    </div>
                    <p v-else class="text-gray-500 text-sm">No wards configured.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
