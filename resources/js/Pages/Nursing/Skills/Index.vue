<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Skill {
    id: number;
    name: string;
    description?: string;
    category?: { name: string };
    learning_objectives?: string[];
}

interface SkillCategory {
    id: number;
    name: string;
}

const props = defineProps<{
    skills: {
        data: Skill[];
        current_page: number;
        last_page: number;
        total: number;
    };
    categories: SkillCategory[];
}>();

const search = ref(new URLSearchParams(window.location.search).get('search') || '');
const categoryFilter = ref(new URLSearchParams(window.location.search).get('category_id') || '');

const applyFilters = () => {
    router.get(route('nursing.skills.index'), {
        search: search.value || undefined,
        category_id: categoryFilter.value || undefined,
    }, { preserveState: true, replace: true });
};
</script>

<template>
    <Head title="Nursing Skills" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800">Nursing Skills Library</h2>
                <Link :href="route('nursing.skills.my-progress')" class="inline-flex items-center px-4 py-2 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition">
                    My Progress
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <input v-model="search" @keyup.enter="applyFilters" type="text" placeholder="Search skills..." class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm" />
                        </div>
                        <div>
                            <select v-model="categoryFilter" @change="applyFilters" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm">
                                <option value="">All Categories</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                        </div>
                        <div>
                            <button @click="applyFilters" class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Apply Filters</button>
                        </div>
                    </div>
                </div>

                <!-- Skills Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="skill in skills.data" :key="skill.id" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
                        <div class="mb-3">
                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-brand-blue-light text-brand-blue">{{ skill.category?.name }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ skill.name }}</h3>
                        <p v-if="skill.description" class="text-sm text-gray-600 line-clamp-3 mb-4">{{ skill.description }}</p>
                        <div v-if="skill.learning_objectives" class="text-xs text-gray-500 mb-4">
                            {{ skill.learning_objectives.length }} learning objectives
                        </div>
                        <Link :href="route('nursing.skills.show', skill.id)" class="block text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                            View Details
                        </Link>
                    </div>
                </div>

                <div v-if="!skills.data.length" class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <p class="text-gray-500">No skills found.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
