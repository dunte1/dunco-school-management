<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    facility: {
        id: number;
        name: string;
        code?: string;
        type: string;
        address?: string;
        city?: string;
        state?: string;
        country?: string;
        phone?: string;
        email?: string;
        contact_person?: string;
        contact_person_phone?: string;
        notes?: string;
        is_active: boolean;
    };
}>();

const form = useForm({
    name: props.facility.name,
    code: props.facility.code || '',
    type: props.facility.type,
    address: props.facility.address || '',
    city: props.facility.city || '',
    state: props.facility.state || '',
    country: props.facility.country || '',
    phone: props.facility.phone || '',
    email: props.facility.email || '',
    contact_person: props.facility.contact_person || '',
    contact_person_phone: props.facility.contact_person_phone || '',
    notes: props.facility.notes || '',
    is_active: props.facility.is_active,
});

const submit = () => {
    form.put(route('nursing.facilities.update', props.facility.id));
};
</script>

<template>
    <Head :title="'Edit ' + facility.name" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.facilities.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">Edit {{ facility.name }}</h2>
            </div>
        </template>
        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Facility Name *</label>
                            <input v-model="form.name" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Code</label>
                            <input v-model="form.code" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                            <select v-model="form.type" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                                <option value="hospital">Hospital</option>
                                <option value="health_centre">Health Centre</option>
                                <option value="clinic">Clinic</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input v-model="form.phone" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input v-model="form.email" type="email" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                            <input v-model="form.contact_person" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea v-model="form.address" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input v-model="form.city" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input v-model="form.country" type="text" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea v-model="form.notes" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2">
                                <input v-model="form.is_active" type="checkbox" class="rounded border-gray-300 text-brand-blue focus:ring-brand-blue" />
                                <span class="text-sm font-medium text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                    <button type="submit" :disabled="form.processing" class="px-6 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Update Facility' }}
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
