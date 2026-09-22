<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Question {
    id: number;
    question: string;
    choices: string[];
    correct_choice_index: number;
    explanation?: string;
}

const props = defineProps<{
    scenario: {
        id: number;
        title: string;
        questions: Question[];
    };
    attempt: {
        score: number;
        total_questions: number;
        percentage: number;
        answers?: Record<number, number>;
    };
}>();

const sortedQuestions = [...props.scenario.questions].sort((a, b) => a.id - b.id);

const isCorrect = (questionId: number) => {
    const userAnswer = props.attempt.answers?.[questionId];
    const question = props.scenario.questions.find(q => q.id === questionId);
    return userAnswer !== undefined && question ? userAnswer === question.correct_choice_index : false;
};

const getScoreColor = () => {
    if (props.attempt.percentage >= 80) return 'text-green-600';
    if (props.attempt.percentage >= 50) return 'text-yellow-600';
    return 'text-red-600';
};

const getScoreBg = () => {
    if (props.attempt.percentage >= 80) return 'bg-green-50 border-green-200';
    if (props.attempt.percentage >= 50) return 'bg-yellow-50 border-yellow-200';
    return 'bg-red-50 border-red-200';
};
</script>

<template>
    <Head :title="`Results: ${scenario.title}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <Link :href="route('nursing.scenarios.index')" class="text-brand-blue hover:underline text-sm">&larr; Back</Link>
                <h2 class="text-xl font-semibold text-gray-800">Results: {{ scenario.title }}</h2>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <!-- Score Summary -->
                <div :class="['rounded-xl border-2 p-6 mb-6', getScoreBg()]">
                    <div class="text-center">
                        <p class="text-sm font-medium text-gray-600 mb-2">Your Score</p>
                        <p :class="['text-5xl font-bold', getScoreColor()]">{{ attempt.percentage }}%</p>
                        <p class="text-gray-600 mt-2">{{ attempt.score }} out of {{ attempt.total_questions }} correct</p>
                    </div>
                </div>

                <!-- Questions Review -->
                <div class="space-y-4">
                    <div
                        v-for="(question, idx) in sortedQuestions"
                        :key="question.id"
                        :class="[
                            'rounded-xl border-2 p-6',
                            isCorrect(question.id) ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'
                        ]"
                    >
                        <div class="flex items-start gap-3 mb-4">
                            <span
                                :class="[
                                    'w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold shrink-0',
                                    isCorrect(question.id) ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
                                ]"
                            >
                                {{ isCorrect(question.id) ? '✓' : '✗' }}
                            </span>
                            <div class="flex-1">
                                <span class="text-xs font-medium text-gray-500">Question {{ idx + 1 }}</span>
                                <h3 class="text-gray-800 font-medium">{{ question.question }}</h3>
                            </div>
                        </div>

                        <div class="space-y-2 ml-11">
                            <div
                                v-for="(choice, cIdx) in question.choices"
                                :key="cIdx"
                                :class="[
                                    'flex items-center gap-2 p-3 rounded-lg text-sm',
                                    cIdx === question.correct_choice_index
                                        ? 'bg-green-100 text-green-800 font-medium'
                                        : attempt.answers?.[question.id] === cIdx
                                            ? 'bg-red-100 text-red-800 line-through'
                                            : 'text-gray-600'
                                ]"
                            >
                                <span class="w-6 h-6 rounded-full border flex items-center justify-center text-xs shrink-0"
                                    :class="[
                                        cIdx === question.correct_choice_index
                                            ? 'border-green-500 text-green-700'
                                            : attempt.answers?.[question.id] === cIdx
                                                ? 'border-red-400 text-red-600'
                                                : 'border-gray-300'
                                    ]"
                                >
                                    {{ String.fromCharCode(65 + cIdx) }}
                                </span>
                                {{ choice }}
                            </div>
                        </div>

                        <div v-if="question.explanation" class="mt-4 ml-11 p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <p class="text-sm text-blue-800">
                                <span class="font-semibold">Explanation:</span> {{ question.explanation }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-center gap-4">
                    <Link
                        :href="route('nursing.scenarios.attempt', scenario.id)"
                        class="px-6 py-2.5 bg-brand-blue text-white rounded-lg text-sm font-medium hover:bg-brand-blue-dark transition"
                    >
                        Retake Scenario
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
