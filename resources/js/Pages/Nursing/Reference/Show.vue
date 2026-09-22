<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps<{
    article: {
        id: number;
        title: string;
        content: string;
        author?: string;
        source?: string;
        version: number;
        review_date?: string;
        status: string;
        view_count: number;
        category?: { name: string };
        tags?: string[];
    };
}>();

const statusColors: Record<string, string> = {
    published: 'bg-green-100 text-green-800',
    draft: 'bg-gray-100 text-gray-800',
    archived: 'bg-orange-100 text-orange-800',
};

const formatDate = (date?: string) => {
    if (!date) return 'N/A';
    return new Date(date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
};
</script>

<template>
    <Head :title="'Reference - ' + article.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.reference.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">Reference Article</h2>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ article.title }}</h3>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span v-if="article.category" class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">{{ article.category.name }}</span>
                                <span :class="[statusColors[article.status] || 'bg-gray-100 text-gray-800', 'px-2 py-1 text-xs font-medium rounded-full']">{{ article.status }}</span>
                                <span class="text-xs text-gray-500">v{{ article.version }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <Link :href="route('nursing.reference.edit', article.id)" class="px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">Edit</Link>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-6">
                        <span v-if="article.author">By {{ article.author }}</span>
                        <span v-if="article.source">Source: {{ article.source }}</span>
                        <span>Views: {{ article.view_count }}</span>
                        <span v-if="article.review_date">Review Date: {{ formatDate(article.review_date) }}</span>
                    </div>

                    <div v-if="article.tags?.length" class="flex flex-wrap gap-2 mb-6">
                        <span v-for="tag in article.tags" :key="tag" class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600">{{ tag }}</span>
                    </div>

                    <div class="prose prose-sm max-w-none text-gray-700" v-html="article.content"></div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
