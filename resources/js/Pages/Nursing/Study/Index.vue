<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Category {
    id: number;
    name: string;
    skills_count?: number;
}

interface ReferenceCategory {
    id: number;
    name: string;
    articles_count?: number;
}

interface FeaturedArticle {
    id: number;
    title: string;
    category?: { name: string };
}

const props = defineProps<{
    categories: Category[];
    referenceCategories: ReferenceCategory[];
    featuredArticles: FeaturedArticle[];
}>();
</script>

<template>
    <Head title="Study Center" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">Study Center</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Quick Links -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <Link :href="route('nursing.study.flashcards')" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group">
                        <div class="w-12 h-12 bg-brand-blue rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Flashcards</h3>
                        <p class="text-sm text-gray-500">Review key nursing concepts with flip cards</p>
                    </Link>

                    <Link :href="route('nursing.study.quizzes')" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group">
                        <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Quizzes</h3>
                        <p class="text-sm text-gray-500">Test your knowledge on clinical skills</p>
                    </Link>

                    <Link :href="route('nursing.study-assistant.index')" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group">
                        <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-1">Study Assistant</h3>
                        <p class="text-sm text-gray-500">Ask AI-powered questions about nursing</p>
                    </Link>
                </div>

                <!-- Skill Categories for Flashcards -->
                <div v-if="categories.length" class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Study by Skill Category</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <Link
                            v-for="category in categories"
                            :key="category.id"
                            :href="route('nursing.study.flashcards')"
                            class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition flex items-center justify-between"
                        >
                            <div>
                                <p class="font-medium text-gray-800">{{ category.name }}</p>
                                <p v-if="category.skills_count" class="text-sm text-gray-500">{{ category.skills_count }} skills</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                </div>

                <!-- Reference Categories -->
                <div v-if="referenceCategories.length" class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Reference Articles</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <Link
                            v-for="refCat in referenceCategories"
                            :key="refCat.id"
                            :href="route('nursing.reference.index')"
                            class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition flex items-center justify-between"
                        >
                            <div>
                                <p class="font-medium text-gray-800">{{ refCat.name }}</p>
                                <p v-if="refCat.articles_count" class="text-sm text-gray-500">{{ refCat.articles_count }} articles</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </Link>
                    </div>
                </div>

                <!-- Featured Articles -->
                <div v-if="featuredArticles.length">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Featured Articles</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div
                            v-for="article in featuredArticles"
                            :key="article.id"
                            class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition"
                        >
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-brand-blue-light text-brand-blue">{{ article.category?.name }}</span>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Featured</span>
                            </div>
                            <h4 class="font-semibold text-gray-800">{{ article.title }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
