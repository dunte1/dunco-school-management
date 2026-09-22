<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Facility {
    id: number;
    school_id: number;
    name: string;
    code?: string;
    type: string;
    address?: string;
    city?: string;
    phone?: string;
    contact_person?: string;
    departments_count?: number;
    wards_count?: number;
}

const props = defineProps<{
    facilities: {
        data: Facility[];
        current_page: number;
        last_page: number;
        total: number;
    };
}>();

const search = ref(new URLSearchParams(window.location.search).get('search') || '');
const typeFilter = ref(new URLSearchParams(window.location.search).get('type') || '');

const applyFilters = () => {
    router.get(route('nursing.facilities.index'), {
        search: search.value || undefined,
        type: typeFilter.value || undefined,
    }, { preserveState: true, replace: true });
};

const typeLabels: Record<string, string> = {
    hospital: 'Hospital',
    health_centre: 'Health Centre',
    clinic: 'Clinic',
    other: 'Other',
};

const typeColors: Record<string, string> = {
    hospital: 'bg-blue-100 text-blue-800',
    health_centre: 'bg-green-100 text-green-800',
    clinic: 'bg-purple-100 text-purple-800',
    other: 'bg-gray-100 text-gray-800',
};
</script>

<template>
    <Head title="Clinical Facilities" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Clinical Facilities</h2>
                <Link :href="route('nursing.facilities.create')" class="inline-flex items-center px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Facility
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <input
                                v-model="search"
                                @keyup.enter="applyFilters"
                                type="text"
                                placeholder="Search facilities..."
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm"
                            />
                        </div>
                        <div>
                            <select
                                v-model="typeFilter"
                                @change="applyFilters"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm"
                            >
                                <option value="">All Types</option>
                                <option value="hospital">Hospital</option>
                                <option value="health_centre">Health Centre</option>
                                <option value="clinic">Clinic</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <button @click="applyFilters" class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Facilities Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="facility in facilities.data" :key="facility.id" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ facility.name }}</h3>
                                <p v-if="facility.code" class="text-sm text-gray-500">Code: {{ facility.code }}</p>
                            </div>
                            <span :class="[typeColors[facility.type], 'px-2 py-1 text-xs font-medium rounded-full']">
                                {{ typeLabels[facility.type] || facility.type }}
                            </span>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600 mb-4">
                            <p v-if="facility.address">{{ facility.address }}{{ facility.city ? ', ' + facility.city : '' }}</p>
                            <p v-if="facility.phone">Phone: {{ facility.phone }}</p>
                            <p v-if="facility.contact_person">Contact: {{ facility.contact_person }}</p>
                            <div class="flex gap-4 text-xs text-gray-500">
                                <span>{{ facility.departments_count || 0 }} departments</span>
                                <span>{{ facility.wards_count || 0 }} wards</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <Link :href="route('nursing.facilities.show', facility.id)" class="flex-1 text-center px-3 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">
                                View
                            </Link>
                            <Link :href="route('nursing.facilities.edit', facility.id)" class="flex-1 text-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                                Edit
                            </Link>
                        </div>
                    </div>
                </div>

                <div v-if="!facilities.data.length" class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="text-gray-500">No facilities found.</p>
                    <Link :href="route('nursing.facilities.create')" class="mt-4 inline-flex items-center px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">
                        Add Your First Facility
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
