<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    logbook: { id: number; placement_id: number; date: string; shift?: string; hours: number; activity?: string; procedure?: string; learning_objective?: string; reflection?: string; challenges?: string };
    placements: Array<{ id: number; facility?: { name: string } }>;
}>();

const form = useForm({
    placement_id: props.logbook.placement_id,
    date: props.logbook.date,
    shift: props.logbook.shift || '',
    hours: props.logbook.hours,
    activity: props.logbook.activity || '',
    procedure: props.logbook.procedure || '',
    learning_objective: props.logbook.learning_objective || '',
    reflection: props.logbook.reflection || '',
    challenges: props.logbook.challenges || '',
    evidence: '',
});

const submit = () => { form.put(route('nursing.logbook.update', props.logbook.id)); };
</script>

<template>
    <Head title="Edit Logbook" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.logbook.show', logbook.id)" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">Edit Logbook Entry</h2>
            </div>
        </template>
        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Placement *</label>
                            <select v-model="form.placement_id" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" required>
                                <option v-for="p in placements" :key="p.id" :value="p.id">{{ p.facility?.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                            <input v-model="form.date" type="date" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
                            <select v-model="form.shift" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                                <option value="">Select...</option>
                                <option value="Morning">Morning</option>
                                <option value="Afternoon">Afternoon</option>
                                <option value="Night">Night</option>
                                <option value="Full Day">Full Day</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hours *</label>
                            <input v-model="form.hours" type="number" min="0.5" max="16" step="0.5" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" required />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Activity</label>
                            <textarea v-model="form.activity" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm text-sm"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Procedure</label>
                            <textarea v-model="form.procedure" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm text-sm"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reflection</label>
                            <textarea v-model="form.reflection" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm text-sm"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Challenges</label>
                            <textarea v-model="form.challenges" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm text-sm"></textarea>
                        </div>
                    </div>
                    <button type="submit" :disabled="form.processing" class="px-6 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Update Entry' }}
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
