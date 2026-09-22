<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps<{
    settings: Record<string, string | number>;
}>();

const form = reactive({ ...props.settings });

const submit = () => {
    router.put(route('nursing.settings.update'), form, { preserveState: true });
};
</script>

<template>
    <Head title="Nursing Settings" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">Nursing Module Settings</h2>
        </template>

        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Clinical Hours</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Required Hours per Placement</label>
                                    <input v-model="form.clinical_hours_per_placement" type="number" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Required Hours per Semester</label>
                                    <input v-model="form.clinical_hours_per_semester" type="number" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Hours per Day</label>
                                    <input v-model="form.max_hours_per_day" type="number" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Attendance</h3>
                            <div class="max-w-xs">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Passing Percentage (%)</label>
                                <input v-model="form.attendance_passing" type="number" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">CPD</h3>
                            <div class="max-w-xs">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Target Hours per Year</label>
                                <input v-model="form.cpd_target_hours" type="number" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Logbook</h3>
                            <div class="max-w-xs">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Allow Backdating (days)</label>
                                <input v-model="form.logbook_backdating_days" type="number" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">File Uploads</h3>
                            <div class="max-w-xs">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Max Upload Size (KB)</label>
                                <input v-model="form.max_upload_size" type="number" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <button @click="submit" class="px-6 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">
                                Save Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
