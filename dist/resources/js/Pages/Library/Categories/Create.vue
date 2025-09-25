<template>
  <div class="max-w-lg mx-auto py-8">
    <h1 class="text-2xl font-bold text-navy-900 mb-6">Add Category</h1>
    <form @submit.prevent="submit">
      <div class="mb-4">
        <label class="block text-blue-900 font-semibold mb-2">Name</label>
        <input v-model="form.name" type="text" class="w-full border border-blue-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-400" required />
      </div>
      <div class="flex gap-3 mt-6">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold shadow">Save</button>
        <inertia-link href="/library/categories" class="px-5 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 font-semibold">Cancel</inertia-link>
      </div>
    </form>
  </div>
</template>
<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';
const form = reactive({ name: '' });
function submit() {
  router.post('/library/categories', form);
}
</script> 