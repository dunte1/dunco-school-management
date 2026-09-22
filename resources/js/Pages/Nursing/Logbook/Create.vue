<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    placements: Array<{ id: number; facility?: { name: string }; department?: { name: string }; ward?: { name: string } }>;
    placement?: { id: number } | null;
}>();

const form = useForm({
    placement_id: props.placement?.id || '',
    date: new Date().toISOString().split('T')[0],
    shift: '',
    hours: 8,
    activity: '',
    procedure: '',
    learning_objective: '',
    reflection: '',
    challenges: '',
    evidence: '',
});

const submit = () => { form.post(route('nursing.logbook.store')); };
</script>

<template>
    <Head title="New Logbook Entry" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.logbook.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">New Logbook Entry</h2>
            </div>
        </template>
        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Placement *</label>
                            <select v-model="form.placement_id" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" required>
                                <option value="">Select placement...</option>
                                <option v-for="p in placements" :key="p.id" :value="p.id">{{ p.facility?.name }} - {{ p.department?.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                            <input v-model="form.date" type="date" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Shift</label>
                            <select v-model="form.shift" class="w-full rounded-lg border-gray-300 shadow-sm text-sm">
                                <option value="">Select shift...</option>
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
                            <textarea v-model="form.activity" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" placeholder="Describe your clinical activity..."></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Procedure</label>
                            <textarea v-model="form.procedure" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" placeholder="Procedures performed or observed..."></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Learning Objective</label>
                            <textarea v-model="form.learning_objective" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm text-sm"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reflection</label>
                            <textarea v-model="form.reflection" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" placeholder="Reflect on your experience..."></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Challenges</label>
                            <textarea v-model="form.challenges" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm text-sm"></textarea>
                        </div>
                    </div>
                    <button type="submit" :disabled="form.processing" class="px-6 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Save Entry' }}
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
