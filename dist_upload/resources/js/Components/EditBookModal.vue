<template>
  <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 relative">
      <button @click="$emit('close')" class="absolute top-4 right-4 bg-blue-600 hover:bg-blue-700 text-white rounded-full w-10 h-10 flex items-center justify-center shadow focus:outline-none focus:ring-2 focus:ring-blue-400 z-10">
        <i class="fas fa-times"></i>
      </button>
      <h2 class="text-xl font-bold text-navy-900 mb-4">Edit Book</h2>
      <form @submit.prevent="submit">
        <div class="mb-3">
          <label class="block font-semibold text-blue-900 mb-1">Title</label>
          <input v-model="form.title" type="text" class="w-full border border-blue-300 rounded-lg px-3 py-2" required />
        </div>
        <div class="mb-3">
          <label class="block font-semibold text-blue-900 mb-1">Author</label>
          <select v-model="form.author_id" class="w-full border border-blue-300 rounded-lg px-3 py-2">
            <option value="">Select Author</option>
            <option v-for="a in authors" :key="a.id" :value="a.id">{{ a.name }}</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="block font-semibold text-blue-900 mb-1">Category</label>
          <select v-model="form.category_id" class="w-full border border-blue-300 rounded-lg px-3 py-2">
            <option value="">Select Category</option>
            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="block font-semibold text-blue-900 mb-1">Publisher</label>
          <select v-model="form.publisher_id" class="w-full border border-blue-300 rounded-lg px-3 py-2">
            <option value="">Select Publisher</option>
            <option v-for="p in publishers" :key="p.id" :value="p.id">{{ p.name }}</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="block font-semibold text-blue-900 mb-1">ISBN</label>
          <input v-model="form.isbn" type="text" class="w-full border border-blue-300 rounded-lg px-3 py-2" required />
        </div>
        <div class="mb-3">
          <label class="block font-semibold text-blue-900 mb-1">Status</label>
          <select v-model="form.status" class="w-full border border-blue-300 rounded-lg px-3 py-2">
            <option value="available">Available</option>
            <option value="borrowed">Borrowed</option>
            <option value="reserved">Reserved</option>
            <option value="lost">Lost</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="block font-semibold text-blue-900 mb-1">Description</label>
          <textarea v-model="form.description" class="w-full border border-blue-300 rounded-lg px-3 py-2"></textarea>
        </div>
        <div class="flex gap-3 mt-6 justify-end">
          <button type="button" @click="$emit('close')" class="px-5 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 font-semibold">Cancel</button>
          <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold shadow">Save</button>
        </div>
      </form>
    </div>
  </div>
</template>
<script setup>
import { reactive, watch } from 'vue';
import { router } from '@inertiajs/vue3';
const props = defineProps({ book: Object, categories: Array, authors: Array, publishers: Array });
const form = reactive({
  title: props.book.title,
  author_id: props.book.author_id,
  category_id: props.book.category_id,
  publisher_id: props.book.publisher_id,
  isbn: props.book.isbn,
  status: props.book.status,
  description: props.book.description,
});
function submit() {
  router.put(`/library/books/${props.book.id}`, form, {
    onSuccess: () => {
      // Close modal after save
      emit('close');
    },
  });
}
const emit = defineEmits(['close']);
</script> 