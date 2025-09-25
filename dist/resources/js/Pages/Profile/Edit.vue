<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, usePage } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();

const user = usePage().props.auth.user;
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="py-10 bg-gradient-to-br from-blue-50/60 to-blue-100/80 min-h-screen">
            <div class="mx-auto max-w-2xl flex flex-col items-center gap-6">
                <!-- Premium Avatar Card -->
                <div class="relative flex flex-col items-center justify-center w-full mb-2">
                    <div class="glass-card rounded-2xl shadow-lg p-6 flex flex-col items-center w-full">
                        <div class="relative mb-3">
                            <img :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=1ea7ff&color=fff&rounded=true&size=96`" alt="Avatar" class="rounded-full border-4 border-white shadow-lg" width="96" height="96">
                            <button class="absolute bottom-2 right-2 bg-white text-blue-600 rounded-full p-2 shadow hover:bg-blue-100 transition" title="Change Avatar" style="font-size:1.1rem;">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                        <div class="text-center">
                            <h2 class="font-bold text-2xl text-gray-900 dark:text-gray-100 mb-1">{{ user.name }}</h2>
                            <div class="text-gray-500 dark:text-gray-400 text-sm">{{ user.email }}</div>
                        </div>
                    </div>
                </div>
                <!-- Profile Sections -->
                <div class="w-full space-y-6">
                    <div class="glass-card bg-white/80 dark:bg-gray-800/80 p-6 shadow-lg rounded-2xl">
                        <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" class="max-w-xl mx-auto" />
                    </div>
                    <div class="glass-card bg-white/80 dark:bg-gray-800/80 p-6 shadow-lg rounded-2xl">
                        <UpdatePasswordForm class="max-w-xl mx-auto" />
                    </div>
                    <div class="glass-card bg-white/80 dark:bg-gray-800/80 p-6 shadow-lg rounded-2xl">
                        <DeleteUserForm class="max-w-xl mx-auto" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.glass-card {
    background: rgba(255,255,255,0.85);
    box-shadow: 0 8px 32px 0 rgba(30,167,255,0.10), 0 1.5px 6px 0 rgba(21,101,192,0.10);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(30,167,255,0.10);
}
</style>
