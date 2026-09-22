<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    activity_name: '',
    provider: '',
    type: '',
    activity_date: '',
    hours: '',
    certificate: null as File | null,
    notes: '',
});

const certificateInput = ref<HTMLInputElement | null>(null);

const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files.length > 0) {
        form.certificate = target.files[0];
    }
};

const submit = () => {
    form.post(route('nursing.cpd.store'), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Log CPD Activity" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.cpd.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">Log CPD Activity</h2>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Activity Name *</label>
                            <input
                                v-model="form.activity_name"
                                type="text"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-brand-blue focus:ring-brand-blue"
                                placeholder="e.g. Cardiac Care Workshop"
                                required
                            />
                            <p v-if="form.errors.activity_name" class="text-red-500 text-xs mt-1">{{ form.errors.activity_name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Provider</label>
                            <input
                                v-model="form.provider"
                                type="text"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-brand-blue focus:ring-brand-blue"
                                placeholder="e.g. Nigerian Nursing Council"
                            />
                            <p v-if="form.errors.provider" class="text-red-500 text-xs mt-1">{{ form.errors.provider }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                            <select
                                v-model="form.type"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-brand-blue focus:ring-brand-blue"
                                required
                            >
                                <option value="">Select type...</option>
                                <option value="workshop">Workshop</option>
                                <option value="conference">Conference</option>
                                <option value="seminar">Seminar</option>
                                <option value="online">Online Course</option>
                                <option value="certification">Certification</option>
                                <option value="other">Other</option>
                            </select>
                            <p v-if="form.errors.type" class="text-red-500 text-xs mt-1">{{ form.errors.type }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Activity Date *</label>
                            <input
                                v-model="form.activity_date"
                                type="date"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-brand-blue focus:ring-brand-blue"
                                required
                            />
                            <p v-if="form.errors.activity_date" class="text-red-500 text-xs mt-1">{{ form.errors.activity_date }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hours *</label>
                            <input
                                v-model="form.hours"
                                type="number"
                                min="0.5"
                                step="0.5"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-brand-blue focus:ring-brand-blue"
                                placeholder="e.g. 8"
                                required
                            />
                            <p v-if="form.errors.hours" class="text-red-500 text-xs mt-1">{{ form.errors.hours }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Certificate</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-brand-blue transition">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 justify-center">
                                        <label
                                            class="relative cursor-pointer rounded-md font-medium text-brand-blue hover:text-brand-blue-dark"
                                        >
                                            <span>Upload certificate</span>
                                            <input
                                                ref="certificateInput"
                                                type="file"
                                                class="sr-only"
                                                accept=".pdf,.jpg,.jpeg,.png"
                                                @change="handleFileChange"
                                            />
                                        </label>
                                    </div>
                                    <p v-if="form.certificate" class="text-sm text-green-600">{{ form.certificate.name }}</p>
                                    <p class="text-xs text-gray-500">PDF, PNG, JPG up to 5MB</p>
                                </div>
                            </div>
                            <p v-if="form.errors.certificate" class="text-red-500 text-xs mt-1">{{ form.errors.certificate }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea
                                v-model="form.notes"
                                rows="3"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-brand-blue focus:ring-brand-blue"
                                placeholder="Any additional notes..."
                            ></textarea>
                            <p v-if="form.errors.notes" class="text-red-500 text-xs mt-1">{{ form.errors.notes }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-2.5 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Saving...' : 'Save Activity' }}
                        </button>
                        <Link
                            :href="route('nursing.cpd.index')"
                            class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
