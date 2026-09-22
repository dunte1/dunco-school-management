<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    skill: {
        id: number;
        name: string;
        description?: string;
        category?: { name: string };
        learning_objectives?: string[];
        equipment?: string[];
        procedure_reference?: string;
        safety_considerations?: string;
        assessment_criteria?: string;
        status: string;
    };
    studentSkill?: {
        id: number;
        status: string;
        notes?: string;
        awardedBy?: { name: string };
    };
}>();

const statusColors: Record<string, string> = {
    not_started: 'bg-gray-100 text-gray-800',
    learning: 'bg-yellow-100 text-yellow-800',
    observed: 'bg-blue-100 text-blue-800',
    assisted: 'bg-indigo-100 text-indigo-800',
    performed_supervised: 'bg-purple-100 text-purple-800',
    competent: 'bg-green-100 text-green-800',
};

const statusOptions = [
    { value: 'not_started', label: 'Not Started' },
    { value: 'learning', label: 'Learning' },
    { value: 'observed', label: 'Observed' },
    { value: 'assisted', label: 'Assisted' },
    { value: 'performed_supervised', label: 'Performed (Supervised)' },
    { value: 'competent', label: 'Competent' },
];

const form = useForm({
    status: props.studentSkill?.status || 'not_started',
    notes: props.studentSkill?.notes || '',
});

const submitStatus = () => {
    form.put(route('nursing.skills.update-status', props.skill.id));
};
</script>

<template>
    <Head :title="'Skill - ' + skill.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.skills.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">{{ skill.name }}</h2>
                <span v-if="skill.category" :class="[statusColors[studentSkill?.status || skill.status] || 'bg-gray-100 text-gray-800', 'px-3 py-1 text-sm font-medium rounded-full']">
                    {{ skill.category.name }}
                </span>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ skill.name }}</h3>
                            <p v-if="skill.category" class="text-sm text-gray-500">Category: {{ skill.category.name }}</p>
                        </div>
                        <span :class="[statusColors[studentSkill?.status || skill.status] || 'bg-gray-100 text-gray-800', 'px-3 py-1 text-sm font-medium rounded-full']">
                            {{ studentSkill?.status || skill.status }}
                        </span>
                    </div>

                    <p v-if="skill.description" class="text-sm text-gray-700 mb-6">{{ skill.description }}</p>

                    <div v-if="skill.learning_objectives?.length" class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Learning Objectives</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li v-for="(obj, i) in skill.learning_objectives" :key="i" class="text-sm text-gray-600">{{ obj }}</li>
                        </ul>
                    </div>

                    <div v-if="skill.equipment?.length" class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Equipment Required</h4>
                        <ul class="list-disc list-inside space-y-1">
                            <li v-for="(item, i) in skill.equipment" :key="i" class="text-sm text-gray-600">{{ item }}</li>
                        </ul>
                    </div>

                    <div v-if="skill.procedure_reference" class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Procedure Reference</h4>
                        <p class="text-sm text-gray-600">{{ skill.procedure_reference }}</p>
                    </div>

                    <div v-if="skill.safety_considerations" class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Safety Considerations</h4>
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-700">{{ skill.safety_considerations }}</p>
                        </div>
                    </div>

                    <div v-if="skill.assessment_criteria" class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Assessment Criteria</h4>
                        <p class="text-sm text-gray-600">{{ skill.assessment_criteria }}</p>
                    </div>
                </div>

                <!-- Status Update Form -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Update Your Progress</h4>
                    <div v-if="studentSkill?.awardedBy" class="text-xs text-gray-500 mb-4">Last updated by {{ studentSkill.awardedBy.name }}</div>
                    <form @submit.prevent="submitStatus" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select v-model="form.status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                                <option v-for="opt in statusOptions" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea v-model="form.notes" rows="3" placeholder="Add any notes about your progress..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm"></textarea>
                        </div>
                        <button type="submit" :disabled="form.processing" class="px-6 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition disabled:opacity-50">
                            {{ form.processing ? 'Saving...' : 'Update Status' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
