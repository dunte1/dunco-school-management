<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    facilities: Array<{ id: number; name: string; departments?: Array<{ id: number; name: string; wards?: Array<{ id: number; name: string }> }> }>;
    students: Array<{ id: number; name: string; student_id: string }>;
    instructors: Array<{ id: number; first_name: string; last_name: string; staff_id: string }>;
}>();

const form = useForm({
    student_id: '',
    facility_id: '',
    department_id: '',
    ward_id: '',
    instructor_id: '',
    start_date: '',
    end_date: '',
    required_hours: 200,
    notes: '',
});

const selectedFacility = () => props.facilities.find(f => f.id === Number(form.facility_id));

const submit = () => {
    form.post(route('nursing.placements.store'));
};
</script>

<template>
    <Head title="New Placement" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.placements.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">New Clinical Placement</h2>
            </div>
        </template>
        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Student *</label>
                            <select v-model="form.student_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" required>
                                <option value="">Select student...</option>
                                <option v-for="s in students" :key="s.id" :value="s.id">{{ s.name }} ({{ s.student_id }})</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Facility *</label>
                            <select v-model="form.facility_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" required>
                                <option value="">Select facility...</option>
                                <option v-for="f in facilities" :key="f.id" :value="f.id">{{ f.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Department *</label>
                            <select v-model="form.department_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" required>
                                <option value="">Select department...</option>
                                <option v-for="d in selectedFacility()?.departments || []" :key="d.id" :value="d.id">{{ d.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ward</label>
                            <select v-model="form.ward_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                                <option value="">Select ward...</option>
                                <option v-for="w in selectedFacility()?.departments?.find(d => d.id === Number(form.department_id))?.wards || []" :key="w.id" :value="w.id">{{ w.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instructor</label>
                            <select v-model="form.instructor_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                                <option value="">Select instructor...</option>
                                <option v-for="i in instructors" :key="i.id" :value="i.id">{{ i.first_name }} {{ i.last_name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Required Hours</label>
                            <input v-model="form.required_hours" type="number" min="1" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                            <input v-model="form.start_date" type="date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date *</label>
                            <input v-model="form.end_date" type="date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" required />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea v-model="form.notes" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm"></textarea>
                        </div>
                    </div>
                    <button type="submit" :disabled="form.processing" class="px-6 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition disabled:opacity-50">
                        {{ form.processing ? 'Creating...' : 'Create Placement' }}
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
