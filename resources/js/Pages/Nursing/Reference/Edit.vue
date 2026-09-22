<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps<{
    article: {
        id: number;
        category_id: number;
        title: string;
        content: string;
        author?: string;
        source?: string;
        tags?: string[];
    };
    categories: Array<{ id: number; name: string }>;
}>();

const form = useForm({
    category_id: props.article.category_id,
    title: props.article.title,
    content: props.article.content,
    author: props.article.author || '',
    source: props.article.source || '',
    tags: props.article.tags?.join(', ') || '',
});

const submit = () => {
    form.put(route('nursing.reference.update', props.article.id));
};
</script>

<template>
    <Head title="Edit Reference Article" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.reference.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">Edit Reference Article</h2>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                            <select v-model="form.category_id" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" required>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Author</label>
                            <input v-model="form.author" type="text" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                            <input v-model="form.title" type="text" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" required />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Content *</label>
                            <textarea v-model="form.content" rows="12" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                            <input v-model="form.source" type="text" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                            <input v-model="form.tags" type="text" placeholder="Comma-separated tags" class="w-full rounded-lg border-gray-300 shadow-sm text-sm" />
                        </div>
                    </div>
                    <button type="submit" :disabled="form.processing" class="px-6 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Update Article' }}
                    </button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
