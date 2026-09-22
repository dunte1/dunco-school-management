<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

interface Question {
    id: number;
    question: string;
    choices: string[];
    order: number;
}

const props = defineProps<{
    scenario: {
        id: number;
        title: string;
        questions: Question[];
    };
}>();

const currentIndex = ref(0);
const answers = ref<Record<number, number>>({});

const sortedQuestions = computed(() =>
    [...props.scenario.questions].sort((a, b) => a.order - b.order)
);

const currentQuestion = computed(() => sortedQuestions.value[currentIndex.value]);
const isFirst = computed(() => currentIndex.value === 0);
const isLast = computed(() => currentIndex.value === sortedQuestions.value.length - 1);
const progress = computed(() => ((currentIndex.value + 1) / sortedQuestions.value.length) * 100);
const answeredCount = computed(() => Object.keys(answers.value).length);

const form = useForm({
    answers: answers.value as Record<number, number>,
});

const selectChoice = (choiceIndex: number) => {
    answers.value[currentQuestion.value.id] = choiceIndex;
};

const prev = () => {
    if (!isFirst.value) currentIndex.value--;
};

const next = () => {
    if (!isLast.value) currentIndex.value++;
};

const submit = () => {
    form.answers = { ...answers.value };
    form.post(route('nursing.scenarios.submit', props.scenario.id));
};
</script>

<template>
    <Head :title="`Scenario: ${scenario.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.scenarios.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">{{ scenario.title }}</h2>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <!-- Progress Bar -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">
                            Question {{ currentIndex + 1 }} of {{ sortedQuestions.length }}
                        </span>
                        <span class="text-sm text-gray-500">
                            {{ answeredCount }}/{{ sortedQuestions.length }} answered
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-brand-blue h-2 rounded-full transition-all duration-300" :style="{ width: progress + '%' }"></div>
                    </div>
                </div>

                <!-- Question Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
                    <div class="mb-6">
                        <span class="inline-block px-3 py-1 bg-brand-blue/10 text-brand-blue text-sm font-medium rounded-full mb-3">
                            Question {{ currentQuestion.order }}
                        </span>
                        <h3 class="text-lg font-semibold text-gray-800">{{ currentQuestion.question }}</h3>
                    </div>

                    <div class="space-y-3">
                        <label
                            v-for="(choice, idx) in currentQuestion.choices"
                            :key="idx"
                            :class="[
                                'flex items-center gap-3 p-4 rounded-lg border-2 cursor-pointer transition-all',
                                answers[currentQuestion.id] === idx
                                    ? 'border-brand-blue bg-brand-blue/5'
                                    : 'border-gray-200 hover:border-gray-300'
                            ]"
                        >
                            <input
                                type="radio"
                                :name="`question_${currentQuestion.id}`"
                                :value="idx"
                                :checked="answers[currentQuestion.id] === idx"
                                @change="selectChoice(idx)"
                                class="w-4 h-4 text-brand-blue focus:ring-brand-blue border-gray-300"
                            />
                            <span class="text-sm font-medium text-gray-700">{{ choice }}</span>
                        </label>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex items-center justify-between">
                    <button
                        @click="prev"
                        :disabled="isFirst"
                        class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Previous
                    </button>

                    <div v-if="isLast" class="flex gap-3">
                        <button
                            @click="submit"
                            :disabled="answeredCount < sortedQuestions.length || form.processing"
                            class="px-6 py-2.5 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ form.processing ? 'Submitting...' : 'Submit Answers' }}
                        </button>
                    </div>
                    <button
                        v-else
                        @click="next"
                        class="px-5 py-2.5 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
