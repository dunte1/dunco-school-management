<template>
  <div class="max-w-3xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-navy-900">Authors</h1>
      <button @click="showAddAuthorModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold shadow">Add Author</button>
    </div>
    <AddAuthorModal :show="showAddAuthorModal" @close="showAddAuthorModal = false" @saved="handleAuthorSaved" />
    <table class="min-w-full bg-white rounded-xl shadow overflow-hidden">
      <thead>
        <tr class="bg-blue-50 text-blue-900">
          <th class="px-4 py-2 text-left">Name</th>
          <th class="px-4 py-2 text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="author in authors" :key="author.id" class="border-b last:border-b-0 hover:bg-blue-50">
          <td class="px-4 py-2">{{ author.name }}</td>
          <td class="px-4 py-2 text-right">
            <inertia-link :href="`/library/authors/${author.id}/edit`" class="text-blue-600 hover:underline font-semibold mr-4">Edit</inertia-link>
            <button @click="confirmDelete(author)" class="text-red-500 hover:underline font-semibold">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
      <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-sm">
        <h2 class="text-lg font-bold mb-4">Delete Author</h2>
        <p>Are you sure you want to delete <span class="font-semibold">{{ authorToDelete?.name }}</span>?</p>
        <div class="mt-6 flex justify-end gap-3">
          <button @click="showDeleteModal = false" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
          <button @click="deleteAuthor" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AddAuthorModal from '@/Components/AddAuthorModal.vue';
const props = defineProps({ authors: Array });
const showAddAuthorModal = ref(false);
const showDeleteModal = ref(false);
const authorToDelete = ref(null);
function handleAuthorSaved() {
  router.reload();
}
function confirmDelete(author) {
  authorToDelete.value = author;
  showDeleteModal.value = true;
}
function deleteAuthor() {
  router.delete(`/library/authors/${authorToDelete.value.id}`);
  showDeleteModal.value = false;
}
</script> 