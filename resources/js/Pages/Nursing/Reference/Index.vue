<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface ReferenceArticle {
    id: number;
    title: string;
    content: string;
    author?: string;
    view_count: number;
    version: number;
    is_featured: boolean;
    category?: { name: string };
}

interface ReferenceCategory {
    id: number;
    name: string;
}

const props = defineProps<{
    articles: {
        data: ReferenceArticle[];
        current_page: number;
        last_page: number;
        total: number;
    };
    categories: ReferenceCategory[];
}>();

const search = ref(new URLSearchParams(window.location.search).get('search') || '');
const categoryFilter = ref(new URLSearchParams(window.location.search).get('category_id') || '');

const applyFilters = () => {
    router.get(route('nursing.reference.index'), {
        search: search.value || undefined,
        category_id: categoryFilter.value || undefined,
    }, { preserveState: true, replace: true });
};
</script>

<template>
    <Head title="Nursing Reference Center" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Nursing Reference Center</h2>
                <Link :href="route('nursing.reference.create')" class="inline-flex items-center px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Article
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Categories -->
                <div class="flex flex-wrap gap-2 mb-6">
                    <button @click="categoryFilter = ''; applyFilters()" :class="[!categoryFilter ? 'bg-brand-blue text-white' : 'bg-white text-gray-700 border border-gray-300', 'px-4 py-2 rounded-lg text-sm font-medium hover:bg-brand-blue hover:text-white transition']">
                        All
                    </button>
                    <button v-for="cat in categories" :key="cat.id" @click="categoryFilter = String(cat.id); applyFilters()" :class="[categoryFilter == String(cat.id) ? 'bg-brand-blue text-white' : 'bg-white text-gray-700 border border-gray-300', 'px-4 py-2 rounded-lg text-sm font-medium hover:bg-brand-blue hover:text-white transition']">
                        {{ cat.name }}
                    </button>
                </div>

                <!-- Search -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
                    <input v-model="search" @keyup.enter="applyFilters" type="text" placeholder="Search articles..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                </div>

                <!-- Articles -->
                <div class="space-y-4">
                    <div v-for="article in articles.data" :key="article.id" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">{{ article.category?.name }}</span>
                                    <span v-if="article.is_featured" class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Featured</span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ article.title }}</h3>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-2">{{ article.content }}</p>
                                <div class="flex items-center gap-4 text-xs text-gray-500">
                                    <span v-if="article.author">By {{ article.author }}</span>
                                    <span>Views: {{ article.view_count }}</span>
                                    <span>v{{ article.version }}</span>
                                </div>
                            </div>
                            <Link :href="route('nursing.reference.show', article.id)" class="ml-4 text-brand-blue text-sm font-medium hover:underline whitespace-nowrap">
                                Read
                            </Link>
                        </div>
                    </div>

                    <div v-if="!articles.data.length" class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                        <p class="text-gray-500">No articles found.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
