<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const reports = [
    {
        title: 'Student Clinical Report',
        description: 'Comprehensive report for individual student clinical performance including logbooks, skills, hours, and assessments.',
        icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
        color: 'blue',
        route: 'nursing.reports.student',
        routeParam: true,
    },
    {
        title: 'Clinical Hours Report',
        description: 'Track and summarize clinical hours across all students with breakdowns by placement and facility.',
        icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        color: 'green',
        route: 'nursing.reports.clinical-hours',
    },
    {
        title: 'Skills Competency Report',
        description: 'Overview of skills competency status across all students with percentage tracking and matrix view.',
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        color: 'purple',
        route: 'nursing.reports.skills-competency',
    },
    {
        title: 'Attendance Report',
        description: 'Attendance summary by placement facility showing hours logged and days present for all students.',
        icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        color: 'orange',
        route: 'nursing.reports.attendance',
    },
];

const colorMap: Record<string, { bg: string; text: string; light: string }> = {
    blue: { bg: 'bg-blue-500', text: 'text-blue-600', light: 'bg-blue-100' },
    green: { bg: 'bg-green-500', text: 'text-green-600', light: 'bg-green-100' },
    purple: { bg: 'bg-purple-500', text: 'text-purple-600', light: 'bg-purple-100' },
    orange: { bg: 'bg-orange-500', text: 'text-orange-600', light: 'bg-orange-100' },
};
</script>

<template>
    <Head title="Reports" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">Reports</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Student Clinical Report -->
                    <Link
                        :href="route('nursing.reports.student', 1)"
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group"
                    >
                        <div class="flex items-start gap-4">
                            <div :class="[colorMap.blue.light, 'w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition']">
                                <svg :class="[colorMap.blue.text, 'w-6 h-6']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="reports[0].icon" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ reports[0].title }}</h3>
                                <p class="text-sm text-gray-500">{{ reports[0].description }}</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </Link>

                    <!-- Clinical Hours Report -->
                    <Link
                        :href="route(reports[1].route)"
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group"
                    >
                        <div class="flex items-start gap-4">
                            <div :class="[colorMap.green.light, 'w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition']">
                                <svg :class="[colorMap.green.text, 'w-6 h-6']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="reports[1].icon" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ reports[1].title }}</h3>
                                <p class="text-sm text-gray-500">{{ reports[1].description }}</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </Link>

                    <!-- Skills Competency Report -->
                    <Link
                        :href="route(reports[2].route)"
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group"
                    >
                        <div class="flex items-start gap-4">
                            <div :class="[colorMap.purple.light, 'w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition']">
                                <svg :class="[colorMap.purple.text, 'w-6 h-6']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="reports[2].icon" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ reports[2].title }}</h3>
                                <p class="text-sm text-gray-500">{{ reports[2].description }}</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </Link>

                    <!-- Attendance Report -->
                    <Link
                        :href="route(reports[3].route)"
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition group"
                    >
                        <div class="flex items-start gap-4">
                            <div :class="[colorMap.orange.light, 'w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition']">
                                <svg :class="[colorMap.orange.text, 'w-6 h-6']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="reports[3].icon" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ reports[3].title }}</h3>
                                <p class="text-sm text-gray-500">{{ reports[3].description }}</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
