<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps<{
    logbook: {
        id: number;
        date: string;
        shift?: string;
        hours: number;
        activity?: string;
        procedure?: string;
        learning_objective?: string;
        reflection?: string;
        challenges?: string;
        status: string;
        submitted_at?: string;
        reviewed_at?: string;
        approved_at?: string;
        review_comments?: string;
        student?: { name: string };
        placement?: { facility?: { name: string }; department?: { name: string } };
        reviewer?: { name: string };
        approver?: { name: string };
    };
}>();

const statusColors: Record<string, string> = {
    draft: 'bg-gray-100 text-gray-800', submitted: 'bg-blue-100 text-blue-800',
    under_review: 'bg-yellow-100 text-yellow-800', approved: 'bg-green-100 text-green-800',
    returned: 'bg-orange-100 text-orange-800', rejected: 'bg-red-100 text-red-800',
};

const submitEntry = () => {
    router.post(route('nursing.logbook.submit', props.logbook.id));
};

const approveEntry = () => {
    router.post(route('nursing.logbook.approve', props.logbook.id));
};
</script>

<template>
    <Head title="Logbook Entry" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.logbook.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">Logbook Entry</h2>
            </div>
        </template>
        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ logbook.student?.name }}</h3>
                            <p class="text-sm text-gray-500">{{ logbook.date }} &middot; {{ logbook.hours }}h &middot; {{ logbook.placement?.facility?.name }}</p>
                        </div>
                        <span :class="[statusColors[logbook.status], 'px-3 py-1 text-sm font-medium rounded-full']">{{ logbook.status }}</span>
                    </div>

                    <div class="space-y-4 text-sm">
                        <div v-if="logbook.shift"><span class="text-gray-500">Shift:</span> <span class="font-medium">{{ logbook.shift }}</span></div>
                        <div v-if="logbook.activity"><span class="text-gray-500 font-medium">Activity:</span><p class="mt-1 text-gray-700">{{ logbook.activity }}</p></div>
                        <div v-if="logbook.procedure"><span class="text-gray-500 font-medium">Procedure:</span><p class="mt-1 text-gray-700">{{ logbook.procedure }}</p></div>
                        <div v-if="logbook.learning_objective"><span class="text-gray-500 font-medium">Learning Objective:</span><p class="mt-1 text-gray-700">{{ logbook.learning_objective }}</p></div>
                        <div v-if="logbook.reflection"><span class="text-gray-500 font-medium">Reflection:</span><p class="mt-1 text-gray-700">{{ logbook.reflection }}</p></div>
                        <div v-if="logbook.challenges"><span class="text-gray-500 font-medium">Challenges:</span><p class="mt-1 text-gray-700">{{ logbook.challenges }}</p></div>
                    </div>

                    <div v-if="logbook.review_comments" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm font-medium text-yellow-800">Review Comments:</p>
                        <p class="text-sm text-yellow-700 mt-1">{{ logbook.review_comments }}</p>
                    </div>

                    <div class="flex gap-2 mt-6">
                        <Link v-if="logbook.status === 'draft' || logbook.status === 'returned'" :href="route('nursing.logbook.edit', logbook.id)" class="px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">Edit</Link>
                        <button v-if="logbook.status === 'draft' || logbook.status === 'returned'" @click="submitEntry" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition">Submit for Review</button>
                        <button v-if="logbook.status === 'submitted' || logbook.status === 'under_review'" @click="approveEntry" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition">Approve</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
