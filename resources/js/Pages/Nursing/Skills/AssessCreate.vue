<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    skills: Array<{ id: number; name: string; category?: { name: string } }>;
    rubrics: Array<{ id: number; name: string }>;
    students: Array<{ id: number; name: string; student_id: string }>;
}>();

const form = useForm({
    student_id: '',
    skill_id: '',
    rubric_id: '',
    score: '',
    maximum_score: '',
    feedback: '',
    recommendation: '',
});

const submit = () => {
    form.post(route('nursing.skill-assessments.store'));
};
</script>

<template>
    <Head title="Create Skill Assessment" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.skill-assessments.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">Create Skill Assessment</h2>
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Skill *</label>
                            <select v-model="form.skill_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" required>
                                <option value="">Select skill...</option>
                                <option v-for="s in skills" :key="s.id" :value="s.id">{{ s.name }}{{ s.category ? ' (' + s.category.name + ')' : '' }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rubric</label>
                            <select v-model="form.rubric_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                                <option value="">Select rubric...</option>
                                <option v-for="r in rubrics" :key="r.id" :value="r.id">{{ r.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Score *</label>
                            <input v-model="form.score" type="number" min="0" step="0.01" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Score *</label>
                            <input v-model="form.maximum_score" type="number" min="1" step="0.01" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" required />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Feedback</label>
                            <textarea v-model="form.feedback" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Recommendation</label>
                            <textarea v-model="form.recommendation" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm"></textarea>
                        </div>
                    </div>
                    <button type="submit" :disabled="form.processing" class="px-6 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition disabled:opacity-50">
                        {{ form.processing ? 'Creating...' : 'Create Assessment' }}
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
