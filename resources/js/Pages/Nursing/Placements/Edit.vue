<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    placement: { id: number; facility_id: number; department_id: number; ward_id?: number; instructor_id?: number; start_date: string; end_date: string; required_hours: number; status: string; notes?: string };
    facilities: Array<{ id: number; name: string; departments?: Array<{ id: number; name: string; wards?: Array<{ id: number; name: string }> }> }>;
    students: Array<{ id: number; name: string }>;
    instructors: Array<{ id: number; first_name: string; last_name: string }>;
}>();

const form = useForm({
    facility_id: props.placement.facility_id,
    department_id: props.placement.department_id,
    ward_id: props.placement.ward_id || '',
    instructor_id: props.placement.instructor_id || '',
    start_date: props.placement.start_date,
    end_date: props.placement.end_date,
    required_hours: props.placement.required_hours,
    status: props.placement.status,
    notes: props.placement.notes || '',
});

const submit = () => {
    form.put(route('nursing.placements.update', props.placement.id));
};
</script>

<template>
    <Head title="Edit Placement" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.placements.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">Edit Placement</h2>
            </div>
        </template>
        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Facility *</label>
                            <select v-model="form.facility_id" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" required>
                                <option v-for="f in facilities" :key="f.id" :value="f.id">{{ f.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instructor</label>
                            <select v-model="form.instructor_id" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                                <option value="">None</option>
                                <option v-for="i in instructors" :key="i.id" :value="i.id">{{ i.first_name }} {{ i.last_name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                            <input v-model="form.start_date" type="date" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
                            <input v-model="form.end_date" type="date" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Required Hours</label>
                            <input v-model="form.required_hours" type="number" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select v-model="form.status" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                                <option value="planned">Planned</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea v-model="form.notes" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm text-sm"></textarea>
                        </div>
                    </div>
                    <button type="submit" :disabled="form.processing" class="px-6 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Update Placement' }}
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
