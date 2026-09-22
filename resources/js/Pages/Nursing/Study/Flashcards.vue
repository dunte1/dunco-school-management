<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Skill {
    id: number;
    name: string;
    description?: string;
    category?: { name: string };
}

const props = defineProps<{
    skills: Skill[];
}>();

const currentIndex = ref(0);
const flipped = ref(false);

const currentSkill = computed(() => props.skills[currentIndex.value] || null);
const totalSkills = computed(() => props.skills.length);
const progress = computed(() => totalSkills.value > 0 ? ((currentIndex.value + 1) / totalSkills.value) * 100 : 0);

const flipCard = () => {
    flipped.value = !flipped.value;
};

const nextCard = () => {
    if (currentIndex.value < totalSkills.value - 1) {
        currentIndex.value++;
        flipped.value = false;
    }
};

const prevCard = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
        flipped.value = false;
    }
};

const shuffleCards = () => {
    const shuffled = [...props.skills].sort(() => Math.random() - 0.5);
    props.skills.splice(0, props.skills.length, ...shuffled);
    currentIndex.value = 0;
    flipped.value = false;
};
</script>

<template>
    <Head title="Flashcards" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="route('nursing.study.index')" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </Link>
                    <h2 class="text-xl font-semibold text-gray-800">Flashcards</h2>
                </div>
                <button @click="shuffleCards" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Shuffle
                </button>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <!-- Progress -->
                <div v-if="totalSkills > 0" class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-600">Card {{ currentIndex + 1 }} of {{ totalSkills }}</span>
                        <span class="text-sm text-gray-500">{{ Math.round(progress) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-brand-blue h-2 rounded-full transition-all duration-300" :style="{ width: progress + '%' }"></div>
                    </div>
                </div>

                <!-- Flashcard -->
                <div v-if="currentSkill" class="mb-8">
                    <div
                        @click="flipCard"
                        class="relative w-full h-80 cursor-pointer perspective-1000"
                    >
                        <div
                            :class="[
                                'absolute inset-0 w-full h-full transition-transform duration-500 transform-style-preserve-3d',
                                flipped ? 'rotate-y-180' : ''
                            ]"
                        >
                            <!-- Front -->
                            <div class="absolute inset-0 w-full h-full backface-hidden bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center p-8">
                                <span v-if="currentSkill.category" class="px-3 py-1 text-xs font-medium rounded-full bg-brand-blue-light text-brand-blue mb-4">
                                    {{ currentSkill.category.name }}
                                </span>
                                <h3 class="text-2xl font-bold text-gray-800 text-center">{{ currentSkill.name }}</h3>
                                <p class="text-sm text-gray-400 mt-4">Click to reveal answer</p>
                            </div>

                            <!-- Back -->
                            <div class="absolute inset-0 w-full h-full backface-hidden rotate-y-180 bg-brand-blue rounded-xl shadow-sm flex flex-col items-center justify-center p-8">
                                <h3 class="text-xl font-bold text-white text-center mb-4">{{ currentSkill.name }}</h3>
                                <p class="text-white/90 text-center leading-relaxed">
                                    {{ currentSkill.description || 'No description available for this skill.' }}
                                </p>
                                <p class="text-sm text-white/60 mt-4">Click to flip back</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="!totalSkills" class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <p class="text-gray-500 text-lg">No flashcards available yet.</p>
                    <p class="text-gray-400 text-sm mt-1">Skills will appear here once they are added to the system.</p>
                </div>

                <!-- Navigation -->
                <div v-if="totalSkills > 0" class="flex items-center justify-between">
                    <button
                        @click="prevCard"
                        :disabled="currentIndex === 0"
                        class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Previous
                    </button>

                    <div class="flex gap-1">
                        <div
                            v-for="(_, index) in totalSkills"
                            :key="index"
                            :class="[
                                'w-2 h-2 rounded-full transition',
                                index === currentIndex ? 'bg-brand-blue' : 'bg-gray-300'
                            ]"
                        ></div>
                    </div>

                    <button
                        @click="nextCard"
                        :disabled="currentIndex === totalSkills - 1"
                        class="inline-flex items-center px-6 py-3 bg-brand-blue text-white rounded-xl text-sm font-medium hover:bg-brand-blue-dark transition disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        Next
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.perspective-1000 {
    perspective: 1000px;
}
.transform-style-preserve-3d {
    transform-style: preserve-3d;
}
.backface-hidden {
    backface-visibility: hidden;
}
.rotate-y-180 {
    transform: rotateY(180deg);
}
</style>
