<template>
  <div class="max-w-3xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold text-navy-900">Categories</h1>
      <button @click="showAddCategoryModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold shadow">Add Category</button>
    </div>
    <AddCategoryModal :show="showAddCategoryModal" @close="showAddCategoryModal = false" @saved="handleCategorySaved" />
    <table class="min-w-full bg-white rounded-xl shadow overflow-hidden">
      <thead>
        <tr class="bg-blue-50 text-blue-900">
          <th class="px-4 py-2 text-left">Name</th>
          <th class="px-4 py-2 text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="category in categories" :key="category.id" class="border-b last:border-b-0 hover:bg-blue-50">
          <td class="px-4 py-2">{{ category.name }}</td>
          <td class="px-4 py-2 text-right">
            <inertia-link :href="`/library/categories/${category.id}/edit`" class="text-blue-600 hover:underline font-semibold mr-4">Edit</inertia-link>
            <button @click="confirmDelete(category)" class="text-red-500 hover:underline font-semibold">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
      <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-sm">
        <h2 class="text-lg font-bold mb-4">Delete Category</h2>
        <p>Are you sure you want to delete <span class="font-semibold">{{ categoryToDelete?.name }}</span>?</p>
        <div class="mt-6 flex justify-end gap-3">
          <button @click="showDeleteModal = false" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Cancel</button>
          <button @click="deleteCategory" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AddCategoryModal from '@/Components/AddCategoryModal.vue';
const props = defineProps({ categories: Array });
const showDeleteModal = ref(false);
const showAddCategoryModal = ref(false);
const categoryToDelete = ref(null);
function confirmDelete(category) {
  categoryToDelete.value = category;
  showDeleteModal.value = true;
}
function deleteCategory() {
  router.delete(`/library/categories/${categoryToDelete.value.id}`);
  showDeleteModal.value = false;
}
function handleCategorySaved() {
  // Reload the page to fetch new categories
  router.reload();
}
</script> 