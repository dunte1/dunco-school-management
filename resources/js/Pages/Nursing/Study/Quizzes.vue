<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Skill {
    id: number;
    name: string;
    description?: string;
    category?: { name: string };
}

interface SkillCategory {
    id: number;
    name: string;
}

const props = defineProps<{
    skills: Skill[];
    categories: SkillCategory[];
}>();

const currentIndex = ref(0);
const results = ref<Record<number, 'knew' | 'study'>>({});
const submitted = ref(false);
const submitting = ref(false);
const selectedCategory = ref<string>('');
const activeQuiz = ref(false);

const filteredSkills = computed(() => {
    if (!selectedCategory.value) return props.skills;
    return props.skills.filter(s => s.category?.name === selectedCategory.value);
});

const currentSkill = computed(() => filteredSkills.value[currentIndex.value] || null);
const totalSkills = computed(() => filteredSkills.value.length);
const progress = computed(() => totalSkills.value > 0 ? ((currentIndex.value + 1) / totalSkills.value) * 100 : 0);
const knownCount = computed(() => Object.values(results.value).filter(r => r === 'knew').length);
const studyCount = computed(() => Object.values(results.value).filter(r => r === 'study').length);

const startQuiz = () => {
    activeQuiz.value = true;
    currentIndex.value = 0;
    results.value = {};
    submitted.value = false;
};

const markKnew = () => {
    if (currentSkill.value) {
        results.value[currentSkill.value.id] = 'knew';
        goNext();
    }
};

const markStudy = () => {
    if (currentSkill.value) {
        results.value[currentSkill.value.id] = 'study';
        goNext();
    }
};

const goNext = () => {
    if (currentIndex.value < totalSkills.value - 1) {
        currentIndex.value++;
    } else {
        submitResults();
    }
};

const goPrev = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    }
};

const submitResults = async () => {
    submitting.value = true;
    try {
        router.post(route('nursing.study.quiz.submit'), {
            results: results.value,
            category: selectedCategory.value || undefined,
        }, {
            onFinish: () => {
                submitted.value = true;
                submitting.value = false;
            },
        });
    } catch {
        submitting.value = false;
    }
};

const resetQuiz = () => {
    activeQuiz.value = false;
    currentIndex.value = 0;
    results.value = {};
    submitted.value = false;
};
</script>

<template>
    <Head title="Quizzes" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="route('nursing.study.index')" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </Link>
                    <h2 class="text-xl font-semibold text-gray-800">Self-Assessment Quiz</h2>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <!-- Setup Screen -->
                <div v-if="!activeQuiz && !submitted" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Start a Quiz Session</h3>
                    <p class="text-gray-600 text-sm mb-6">
                        Test your knowledge on nursing skills. For each skill, indicate whether you knew it or need to study it more.
                    </p>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Category (optional)</label>
                        <select
                            v-model="selectedCategory"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue text-sm"
                        >
                            <option value="">All Categories ({{ skills.length }} skills)</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.name">
                                {{ cat.name }} ({{ skills.filter(s => s.category?.name === cat.name).length }} skills)
                            </option>
                        </select>
                    </div>

                    <div class="flex items-center gap-4">
                        <button
                            @click="startQuiz"
                            :disabled="filteredSkills.length === 0"
                            class="px-6 py-3 bg-brand-blue text-white rounded-xl text-sm font-medium hover:bg-brand-blue-dark transition disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            Start Quiz ({{ filteredSkills.length }} skills)
                        </button>
                        <Link :href="route('nursing.study.index')" class="text-gray-500 text-sm hover:text-gray-700 transition">
                            Back to Study Center
                        </Link>
                    </div>

                    <div v-if="filteredSkills.length === 0" class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">No skills available for the selected category.</p>
                    </div>
                </div>

                <!-- Quiz In Progress -->
                <div v-if="activeQuiz && !submitted && currentSkill">
                    <!-- Progress Bar -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600">Question {{ currentIndex + 1 }} of {{ totalSkills }}</span>
                            <span class="text-sm text-gray-500">{{ Math.round(progress) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-brand-blue h-2 rounded-full transition-all duration-300" :style="{ width: progress + '%' }"></div>
                        </div>
                    </div>

                    <!-- Question Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span v-if="currentSkill.category" class="px-3 py-1 text-xs font-medium rounded-full bg-brand-blue-light text-brand-blue">
                                {{ currentSkill.category.name }}
                            </span>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ currentSkill.name }}</h3>
                        <div v-if="currentSkill.description" class="bg-gray-50 rounded-lg p-4">
                            <p class="text-gray-700 leading-relaxed">{{ currentSkill.description }}</p>
                        </div>
                        <p v-else class="text-gray-400 italic">No description available. Do you know this skill?</p>
                    </div>

                    <!-- Answer Buttons -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <button
                            @click="markStudy"
                            class="flex items-center justify-center gap-2 px-6 py-4 bg-orange-50 border-2 border-orange-200 rounded-xl text-orange-700 font-medium hover:bg-orange-100 hover:border-orange-300 transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            I Need to Study This
                        </button>
                        <button
                            @click="markKnew"
                            class="flex items-center justify-center gap-2 px-6 py-4 bg-green-50 border-2 border-green-200 rounded-xl text-green-700 font-medium hover:bg-green-100 hover:border-green-300 transition"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            I Knew This
                        </button>
                    </div>

                    <!-- Navigation -->
                    <div class="flex items-center justify-between">
                        <button
                            @click="goPrev"
                            :disabled="currentIndex === 0"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition disabled:opacity-40 disabled:cursor-not-allowed"
                        >
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Previous
                        </button>
                        <button
                            @click="resetQuiz"
                            class="text-sm text-gray-500 hover:text-gray-700 transition"
                        >
                            End Quiz
                        </button>
                    </div>
                </div>

                <!-- Results Screen -->
                <div v-if="submitted" class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Quiz Results</h3>

                        <div class="grid grid-cols-3 gap-4 mb-8">
                            <div class="text-center p-4 bg-blue-50 rounded-xl">
                                <p class="text-3xl font-bold text-blue-600">{{ totalSkills }}</p>
                                <p class="text-sm text-gray-600 mt-1">Total Skills</p>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-xl">
                                <p class="text-3xl font-bold text-green-600">{{ knownCount }}</p>
                                <p class="text-sm text-gray-600 mt-1">Knew It</p>
                            </div>
                            <div class="text-center p-4 bg-orange-50 rounded-xl">
                                <p class="text-3xl font-bold text-orange-600">{{ studyCount }}</p>
                                <p class="text-sm text-gray-600 mt-1">Need to Study</p>
                            </div>
                        </div>

                        <div v-if="totalSkills > 0" class="mb-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-600">Confidence Score</span>
                                <span class="text-sm font-medium text-gray-800">{{ Math.round((knownCount / totalSkills) * 100) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-green-500 h-3 rounded-full transition-all" :style="{ width: (knownCount / totalSkills) * 100 + '%' }"></div>
                            </div>
                        </div>

                        <!-- Skills to Review -->
                        <div v-if="studyCount > 0">
                            <h4 class="font-medium text-gray-800 mb-3">Skills to Review</h4>
                            <div class="space-y-2">
                                <div
                                    v-for="skill in skills.filter(s => results[s.id] === 'study')"
                                    :key="skill.id"
                                    class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-100"
                                >
                                    <div>
                                        <p class="font-medium text-gray-800">{{ skill.name }}</p>
                                        <p v-if="skill.category" class="text-xs text-gray-500">{{ skill.category.name }}</p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-700">Review</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-8">
                            <button
                                @click="resetQuiz"
                                class="px-6 py-3 bg-brand-blue text-white rounded-xl text-sm font-medium hover:bg-brand-blue-dark transition"
                            >
                                Take Another Quiz
                            </button>
                            <Link
                                :href="route('nursing.study.flashcards')"
                                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition"
                            >
                                Review Flashcards
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
